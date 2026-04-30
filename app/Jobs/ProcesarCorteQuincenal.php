<?php

namespace App\Jobs;

use App\Models\Relacion;
use App\Models\DetalleVale;
use App\Models\Distribuidora;
use App\Models\Configuracion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcesarCorteQuincenal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        DB::transaction(function () {
            
            // IDs de distribuidoras que ya tienen una relación existente
            $idsConRelacion = Relacion::pluck('num_distribuidora')->unique()->toArray();

            // 1. Buscamos distribuidoras con detalles pendientes O con relación existente (para actualizar pago)
            $distribuidoras = Distribuidora::where(function ($q) use ($idsConRelacion) {
                $q->whereHas('vale.detalleVale', function ($inner) {
                    $inner->whereNull('relacion_id');
                })->orWhereIn('id', $idsConRelacion);
            })->get();

            foreach ($distribuidoras as $dist) {
                
                // 2. Obtener la última relación para saber en qué número de pago vamos
                $ultimaRelacion = Relacion::where('num_distribuidora', $dist->id)->orderBy('id', 'desc')->first();
                
                // 3. Obtener TODOS los vales activos de esta distribuidora para incluirlos en la nueva relación
                $valesActivos = \App\Models\Vale::where('distribuidor_id', $dist->id)
                    ->where('estado', 'activo')
                    ->get();
                
                if ($valesActivos->isEmpty()) {
                    continue;
                }

                // Usamos el primer detalle de cada vale como "plantilla" para los datos financieros
                $detallesPlantilla = [];
                foreach ($valesActivos as $v) {
                    $plantilla = DetalleVale::where('vale_id', $v->id)->first();
                    if ($plantilla) {
                        $detallesPlantilla[] = $plantilla;
                    }
                }

                // 3. Cálculos globales para la nueva relación de esta distribuidora
                $limite = $dist->linea_credito;
                
                $totalOcupado = DetalleVale::whereHas('vale', function ($query) use ($dist) {
                    $query->where('distribuidor_id', $dist->id)
                          ->where('estado', 'activo');
                })->sum('monto');
                
                $disponible = $limite - $totalOcupado;

                // Porcentaje de comisión de la categoría de la distribuidora
                $porcentajeCategoria = $dist->categoria->porcentaje_comision ?? 0;

                // Sumatorias de los detalles para el total de la relación
                $totalAbonoQuincenal = 0;
                $sumaMontosOriginales = 0;
                $sumaComisiones = 0;
                $categoria = 0;

                foreach ($detallesPlantilla as $detalle) {
                    // Sumamos el pago completo de cada vale (que ya incluye capital, interés y seguros)
                    $totalAbonoQuincenal += $detalle->pago;
                    $sumaMontosOriginales += $detalle->monto;
                    $sumaComisiones += $detalle->porcentaje_comision ?? 0;
                    
                    // Cálculo: (Monto × % Categoría) / Quincenas
                    $comisionTotal = $detalle->monto * ($porcentajeCategoria / 100);
                    $quincenas = $detalle->quincenas > 0 ? $detalle->quincenas : 1;
                    $categoria += $comisionTotal / $quincenas;
                }
                
                $fechaLimite = \Carbon\Carbon::parse(
                    Configuracion::where('clave', 'fecha_corte')->value('valor')
                );

                $pagoAnticipado = $fechaLimite->copy()->subDays(3);
                $montoRecargoBase = (float) Configuracion::obtener('recargos', 300);
                
                // REGLA DE BONO POR CATEGORÍA:
                if (now()->startOfDay() >= $fechaLimite->startOfDay()) {
                    $recargo = count($detallesPlantilla) * $montoRecargoBase;
                    $categoria = 0; // Pierde el bono por pago a destiempo
                } else {
                    $recargo = 0;
                }

                $totalPagos = DetalleVale::whereHas('vale', function ($query) use ($dist) {
                    $query->where('distribuidor_id', $dist->id)
                          ->where('estado', 'activo');
                })->sum('pago');
                
                $puntosGanados = floor(($totalPagos / 1200) * 3);

                // Calcular el número de pago actual y deuda anterior
                $adeudoAnterior = 0;
                if ($ultimaRelacion) {
                    $partes = explode('/', $ultimaRelacion->pagos_realizados);
                    $numAnterior = (int) ($partes[0] ?? 0);
                    $quincenasTotal = (isset($partes[1]) && (int)$partes[1] > 0) ? (int)$partes[1] : 8;
                    $numeroDePago = min($numAnterior + 1, $quincenasTotal);
                    
                    // Lógica de Deuda: Si el total de la anterior es mayor a lo pagado
                    $adeudoAnterior = max(0, $ultimaRelacion->total_pagar - ($ultimaRelacion->monto_pagado ?? 0));
                } else {
                    $numeroDePago = 1;
                    $quincenasTotal = 8; // Valor por defecto si no hay previa
                }

                $quincenasMax = collect($detallesPlantilla)->max('quincenas') ?? $quincenasTotal;

                // El total a pagar final incluye el adeudo anterior
                $totalAPagarFinal = ($totalAbonoQuincenal - $categoria) + $recargo + $adeudoAnterior;

                // 4. CREAR LA NUEVA RELACIÓN (Historial)
                $relacion = Relacion::create([
                    'num_distribuidora'    => $dist->id,
                    'nombre_distribuidora' => $dist->usuario->persona->nombre . ' ' . $dist->usuario->persona->apellido,
                    'domicilio'            => $dist->domicilio,
                    'limite_de_credito'    => $limite,
                    'credito_disponible'   => $disponible,
                    'puntos'               => $puntosGanados,

                    'referencia_de_pago'   => 'CQ-' . $dist->id . '-' . now()->format('YmdHis'), 
                    'fecha_limite_pago'    => $fechaLimite,
                    'pago_anticipado'      => $pagoAnticipado->format('Y-m-d'),
                    
                    'adeudo_anterior'      => $adeudoAnterior,
                    'total_pagar'          => $totalAPagarFinal,

                    'pagos_realizados'     => $numeroDePago . '/' . $quincenasMax,
                    'categoria'            => $categoria,
                    'recargos'             => $recargo,
                    'total'                => $totalAPagarFinal,
                    'totales'              => $sumaMontosOriginales,

                    'nombre_empresa'       => "PF Prestamo Facil SA",
                    'convenio'             => "1628789",
                    'clabe'                => $dist->clabe ?? '12345678901234567890',
                    'monto_pagado'         => 0, // Inicia en 0
                    'saldo_pendiente'      => $totalAPagarFinal, // Todo está pendiente al inicio
                ]);

                // 5. VINCULAR O DUPLICAR DETALLES A LA NUEVA RELACIÓN
                foreach ($detallesPlantilla as $plantilla) {
                    if ($plantilla->relacion_id === null) {
                        // Es un detalle nuevo (primera vez), lo vinculamos directamente
                        $plantilla->update(['relacion_id' => $relacion->id]);
                    } else {
                        // Ya estuvo en una relación anterior, duplicamos para el historial de esta quincena
                        $nuevoDetalle = $plantilla->replicate();
                        $nuevoDetalle->relacion_id = $relacion->id;
                        $nuevoDetalle->save();
                    }
                }

                // Sumar los puntos obtenidos a la distribuidora
                $dist->increment('puntos', $puntosGanados);
            }

        });
    }
}