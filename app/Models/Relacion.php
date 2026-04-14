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
        //'vale_id',
        'producto',
        //'cliente',
        'pagos_realizados',
        'comision',
        'pago',
        'recargos',
        'total',
        'totales',
        'nombre_empresa',
        'convenio',
        'cable',
    ];

    public function detalle_vale(): HasMany{
        return $this->hasMany(DetalleVale::class,'relacion_id');
    }
}
