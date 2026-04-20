<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleVale extends Model

{
    protected $table = 'detalle_vales';

    protected $fillable = [
        'relacion_id',
        'vale_id',
        'monto',
        'porcentaje_comision',
        'monto_comision_calculada',
        'interes_quincenal',
        'quincenas',
        'seguro',
        'comision',
        'pago',
        'nombre_cliente',
        'nombre_distribuidora',
        'fecha_emision',
        'producto_folio'
    ];

    public function vale(): BelongsTo{
        return $this->belongsTo(Vale::class,'vale_id');
    }
    
    public function relacion(): BelongsTo{
        return $this->belongsTo(Relacion::class,'relacion_id');
    }
}
