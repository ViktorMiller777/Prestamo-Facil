<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'monto',
        'porcentaje_comision',
        'seguro',
        'quincenas',
        'interes_quincenal',
        
    ];
    

    public function vale(): HasMany{
        return $this->hasMany(Vale::class,'producto_id');
    }
}
