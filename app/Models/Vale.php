<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vale extends Model
{
    protected $table = 'vales';

    protected $fillable = [
        'folio',
        'cliente_id',
        'distribuidor_id',
        'producto_id',
        'estado',
        'fecha_emision'
    ]; 

    public function cliente(): BelongsTo{
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

    public function distribuidora(): BelongsTo{
        return $this->belongsTo(Distribuidora::class,'distribuidor_id');
    }

    public function producto(): BelongsTo{
        return $this->belongsTo(Producto::class,'producto_id');
    }

    public function detalleVale(): HasOne{
        return $this->hasOne(DetalleVale::class,'vale_id');
    }
}
