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
            
            // 1. Buscamos distribuidoras con detalles de vales pendientes de corte
            $distribuidoras = Distribuidora::whereHas('vale.detalleVale', function ($query) {
                $query->whereNull('relacion_id');
            })->get();

            foreach ($distribuidoras as $dist) {
                
                // 2. Obtener todos los detalles pendientes de esta distribuidora
                $detallesPendientes = DetalleVale::whereNull('relacion_id')
                    ->whereHas('vale', function ($query) use ($dist) {
                        $query->where('distribuidor_id', $dist->id);
                    })->get();

                if ($detallesPendientes->isEmpty()) continue;

                // 3. Cálculos globales de la distribuidora para este corte
                $limite = $dist->linea_credito;
                
                // Crédito ocupado: Suma de montos de vales que están activos
                $totalOcupado = DetalleVale::whereHas('vale', function ($query) use ($dist) {
                    $query->where('distribuidor_id', $dist->id)
                          ->where('estado', 'activo');
                })->sum('monto');
                
                $disponible = $limite - $totalOcupado;

                $fechaLimite = now()->addDays(15); 
                $pagoAnticipado = $fechaLimite->copy()->subDays(3);

                // 4. Procesamos cada detalle para crear su relación individual (o agrupada)
                // Si quieres que cada vale tenga su propio contador X/Y, procesamos uno a uno:
                foreach ($detallesPendientes as $detalle) {
                    
                    // Cálculo de quincenas: buscamos cuántas relaciones previas tiene este vale_id
                    $conteoAnteriores = Relacion::whereHas('detalle_vale', function($query) use ($detalle) {
                        $query->where('vale_id', $detalle->vale_id);
                    })->count();

                    $pagoActual = $conteoAnteriores + 1;
                    $totalQuincenas = $detalle->quincenas ?? 0;
                    $formatoPagos = $pagoActual . '/' . $totalQuincenas;
                    $pagoQuincenal = $detalle->monto_comision_calculada / $totalQuincenas;

                    // Crear la Relación (Encabezado del corte)
                    $relacion = Relacion::create([
                        'num_distribuidora'    => $dist->id,
                        'nombre_distribuidora' => $dist->usuario->persona->nombre . ' ' . $dist->usuario->persona->apellido,
                        'limite_de_credito'    => $limite,
                        'credito_disponible'   => $disponible,
                        'puntos'               => $dist->puntos ?? 0,
                        'referencia_de_pago'   => 'CQ-' . $dist->id . '-' . $detalle->id . '-' . now()->format('Ymd'),
                        'fecha_limite_pago'    => $fechaLimite,
                        'pago_anticipado'      => $pagoAnticipado->format('Y-m-d'),
                        'producto'             => 'VARIOS', 
                        'cliente'              => $detalle->nombre_cliente ?? 'VARIOS',
                        'pagos_realizados'     => $formatoPagos,
                        'vale_id'              => $detalle->vale_id,
                        'total_pagar'          => $detalle->monto_comision_calculada,
                        'comision'             => $detalle->porcentaje_comision ?? 0,
                        'pago'                 => $pagoQuincenal,
                        'total'                => $detalle->monto,
                        'totales'              => $detalle->monto,
                        'nombre_empresa'       => "PF Prestamo Facil SA",
                        'convenio'             => "1628789",
                        'cable'                => $dist->clabe ?? '12345678901234567890',
                    ]);

                    // Vincular este detalle específico a la relación recién creada
                    $detalle->update([
                        'relacion_id' => $relacion->id
                    ]);
                }
            }
        });
    }
}