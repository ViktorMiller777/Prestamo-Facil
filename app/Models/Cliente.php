<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
        'persona_id',
        'distribuidor_id',
    ];

    public function documentos(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Documento::class, 'documentable');
    }


    public function persona(): BelongsTo{
        return $this->belongsTo(Persona::class,'persona_id');
    }

    public function distribuidora(): BelongsTo{
        return $this->belongsTo(Distribuidora::class,'distribuidor_id');
    }

    public function vale(): HasMany{
        return $this->hasOne(Vale::class,'cliente_id');
    }

    //lonleydigger
}
