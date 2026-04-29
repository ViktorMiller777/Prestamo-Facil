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
                
                // 2. Obtener todos los detalles pendientes de esta distribuidora
                $detallesPendientes = DetalleVale::whereNull('relacion_id')
                    ->whereHas('vale', function ($query) use ($dist) {
                        $query->where('distribuidor_id', $dist->id);
                    })->get();

                // Obtener la relación existente (si existe)
                $relacionExistente = Relacion::where('num_distribuidora', $dist->id)->first();

                // Si NO hay detalles pendientes pero SÍ existe una relación: solo sumamos 1 al pago y seguimos
                if ($detallesPendientes->isEmpty()) {
                    if ($relacionExistente) {
                        $partes     = explode('/', $relacionExistente->pagos_realizados);
                        $numActual  = (int) ($partes[0] ?? 0);
                        // Aseguramos que quincenas tenga un valor válido (mínimo 1) para evitar errores
                        $quincenasVal = (isset($partes[1]) && (int)$partes[1] > 0) ? (int)$partes[1] : 8;
                        $nuevoNum   = min($numActual + 1, $quincenasVal); 
                        $relacionExistente->update(['pagos_realizados' => $nuevoNum . '/' . $quincenasVal]);
                    }
                    continue;
                }

                // 3. Cálculos globales para la ÚNICA relación de esta distribuidora
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

                foreach ($detallesPendientes as $detalle) {
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
                // $fechaLimite = now()->addDays(15); 
                //$fechaLimite = now()->subDays(1);

                $pagoAnticipado = $fechaLimite->copy()->subDays(3);
                
                $montoRecargoBase = (float) Configuracion::obtener('recargos', 300);
                
                // REGLA DE BONO POR CATEGORÍA:
                // Si hoy es igual o mayor a la fecha límite, pierde el bono (categoria = 0) y hay recargos
                if (now()->startOfDay() >= $fechaLimite->startOfDay()) {
                    $recargo = $detallesPendientes->count() * $montoRecargoBase;
                    $categoria = 0; // Pierde el bono por pago a destiempo
                } else {
                    $recargo = 0;
                    // Se mantiene el $categoria calculado en el loop de arriba
                }

                // El total a pagar es la suma de los abonos menos el descuento de categoría (si aplica), más recargos
                $totalAPagarFinal = ($totalAbonoQuincenal - $categoria) + $recargo;

                $totalPagos = DetalleVale::whereHas('vale', function ($query) use ($dist) {
                    $query->where('distribuidor_id', $dist->id)
                          ->where('estado', 'activo');
                })->sum('pago');
                
                $puntosGanados = floor(($totalPagos / 1200) * 3);

                // Calcular el número de pago actual: leer del registro existente y sumar 1
                if ($relacionExistente) {
                    $numeroDePago = (int) explode('/', $relacionExistente->pagos_realizados)[0] + 1;
                } else {
                    $numeroDePago = 1;
                }

                $quincenasMax = $detallesPendientes->max('quincenas') ?? 1;

                // 4. ACTUALIZAR O CREAR LA RELACIÓN ÚNICA (Solo una por distribuidora)
                $relacion = Relacion::updateOrCreate(
                    [
                        'num_distribuidora' => $dist->id,  // clave única para buscar
                    ],
                    [
                        'nombre_distribuidora' => $dist->usuario->persona->nombre . ' ' . $dist->usuario->persona->apellido,
                        'domicilio'            => $dist->domicilio,
                        'limite_de_credito'    => $limite,
                        'credito_disponible'   => $disponible,
                        'puntos'               => $puntosGanados,

                        'referencia_de_pago'   => 'CQ-' . $dist->id . '-' . now()->format('Ymd'),
                        'fecha_limite_pago'    => $fechaLimite,
                        'pago_anticipado'      => $pagoAnticipado->format('Y-m-d'),
                        'total_pagar'          => $totalAPagarFinal,

                        'pagos_realizados'     => $numeroDePago . '/' . $quincenasMax,
                        'categoria'            => $categoria,
                        'recargos'             => $recargo,
                        'total'                => $totalAPagarFinal,
                        'totales'              => $sumaMontosOriginales,

                        'nombre_empresa'       => "PF Prestamo Facil SA",
                        'convenio'             => "1628789",
                        'clabe'                => $dist->clabe ?? '12345678901234567890',
                    ]
                );

                // 5. VINCULAR TODOS LOS DETALLES A LA MISMA RELACIÓN
                foreach ($detallesPendientes as $detalle) {
                    $detalle->update([
                        'relacion_id' => $relacion->id
                    ]);
                }

                // Sumar los puntos obtenidos a la distribuidora
                $dist->increment('puntos', $puntosGanados);
            }
        });
    }
}