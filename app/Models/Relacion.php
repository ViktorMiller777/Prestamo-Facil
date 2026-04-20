<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Relacion extends Model
{
    protected $table = 'relaciones';

    protected $fillable = [
        'num_distribuidora',
        'nombre_distribuidora',
        'domicilio',
        'limite_de_credito',
        'credito_disponible',
        'puntos',
        
        'referencia_de_pago',
        'fecha_limite_pago',
        'pago_anticipado',
        'total_pagar',

        'pagos_realizados',

        'recargos',
        'total',
        'totales',

        //RELLENO
        'nombre_empresa',
        'convenio',
        'clabe',
    ];

    public function detalle_vale(): HasMany{
        return $this->hasMany(DetalleVale::class,'relacion_id');
    }
}
