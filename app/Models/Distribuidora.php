<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Distribuidora extends Model
{
    protected $table = 'distribuidoras';

    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'estado',
        'linea_credito',
        'puntos',
        'domicilio',
        'geolocalizacion_lat',
        'geolocalizacion_lng',
    ];

    public function documentos(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Documento::class, 'documentable');
    }


    public function usuario(): BelongsTo{
        return $this->belongsTo(User::class,'usuario_id');
    }

    public function categoria(): BelongsTo{
        return $this->belongsTo(Categoria::class,'categoria_id');
    }

    public function vale(): HasMany{
        return $this->hasMany(Vale::class,'distribuidor_id');
    }

    public function cliente(): HasMany{
        return $this->hasMany(Cliente::class,'distribuidor_id');
    }

    public function familiar(): HasMany{
        return $this->hasMany(DatoFamilia::class,'distribuidor_id');
    }

    public function vehiculo(): HasOne{
        return $this->hasOne(DatoVehiculo::class,'distribuidor_id');
    }

    public function afilial(): HasOne{
        return $this->hasOne(Afilial::class,'distribuidor_id');
    }
}
