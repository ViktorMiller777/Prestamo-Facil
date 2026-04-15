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

                // 3. Cálculos globales para la ÚNICA relación de esta distribuidora
                $limite = $dist->linea_credito;
                
                $totalOcupado = DetalleVale::whereHas('vale', function ($query) use ($dist) {
                    $query->where('distribuidor_id', $dist->id)
                          ->where('estado', 'activo');
                })->sum('monto');
                
                $disponible = $limite - $totalOcupado;

                // Sumatorias de los detalles para el total de la relación
                $totalAbonoQuincenal = 0;
                $sumaMontosOriginales = 0;
                $sumaComisiones = 0;

                foreach ($detallesPendientes as $detalle) {
                    $quincenas = $detalle->quincenas ?? 1;
                    // Sumamos el pago parcial de cada vale
                    $totalAbonoQuincenal += ($detalle->monto / $quincenas);
                    $sumaMontosOriginales += $detalle->monto;
                    $sumaComisiones += $detalle->porcentaje_comision ?? 0;
                }

                $fechaLimite = now()->addDays(15); 
                $pagoAnticipado = $fechaLimite->copy()->subDays(3);

                // 4. CREAR LA RELACIÓN ÚNICA (Solo una por distribuidora)
                $relacion = Relacion::create([
                    'num_distribuidora'    => $dist->id,
                    'nombre_distribuidora' => $dist->usuario->persona->nombre . ' ' . $dist->usuario->persona->apellido,
                    'limite_de_credito'    => $limite,
                    'credito_disponible'   => $disponible,
                    'puntos'               => $dist->puntos ?? 0,
                    'referencia_de_pago'   => 'CQ-' . $dist->id . '-' . now()->format('Ymd'),
                    'fecha_limite_pago'    => $fechaLimite,
                    'pago_anticipado'      => $pagoAnticipado->format('Y-m-d'),
                    'producto'             => 'VARIOS', 
                    'cliente'              => 'VARIOS', // Al ser varios vales, marcamos como VARIOS
                    'pagos_realizados'     => 'CORTE Q.', 
                    'vale_id'              => 0, // ID genérico ya que agrupa varios
                    'total_pagar'          => round($totalAbonoQuincenal, 2),
                    'comision'             => $sumaComisiones,
                    'pago'                 => round($totalAbonoQuincenal, 2),
                    'total'                => $sumaMontosOriginales,
                    'totales'              => $sumaMontosOriginales,
                    'nombre_empresa'       => "PF Prestamo Facil SA",
                    'convenio'             => "1628789",
                    'cable'                => $dist->clabe ?? '12345678901234567890',
                ]);

                // 5. VINCULAR TODOS LOS DETALLES A LA MISMA RELACIÓN
                foreach ($detallesPendientes as $detalle) {
                    $detalle->update([
                        'relacion_id' => $relacion->id
                    ]);
                }
            }
        });
    }
}