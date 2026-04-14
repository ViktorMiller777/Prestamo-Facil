<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    protected $table = 'notificaciones';

    protected $fillable = [
        'usuario_id',
        'titulo',
        'mensaje',
        'fecha_envio',
        'leido',
    ];

    public function usuario(): BelongsTo{
        return $this->belongsTo(User::class,'usuario_id');
    }
}
