<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CambioDistribuidora extends Model
{
    protected $table = 'cambios_distribuidora';

    protected $fillable = [
        'cliente_id',
        'distribuidora_origen_id',
        'distribuidora_destino_id',
        'coordinador_id',
        'token_origen',
        'token_origen_usado',
        'token_origen_expira_at',
        'token_destino',
        'token_destino_usado',
        'token_destino_expira_at',
        'estado',
        'motivo_cancelacion',
        'fecha_solicitud',
        'fecha_validacion_coord',
        'fecha_completado',
    ];

    protected $casts = [
        'token_origen_usado'     => 'boolean',
        'token_destino_usado'    => 'boolean',
        'token_origen_expira_at' => 'datetime',
        'token_destino_expira_at'=> 'datetime',
        'fecha_solicitud'        => 'datetime',
        'fecha_validacion_coord' => 'datetime',
        'fecha_completado'       => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function distribuidoraOrigen(): BelongsTo
    {
        return $this->belongsTo(Distribuidora::class, 'distribuidora_origen_id');
    }

    public function distribuidoraDestino(): BelongsTo
    {
        return $this->belongsTo(Distribuidora::class, 'distribuidora_destino_id');
    }

    public function coordinador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinador_id');
    }

    /* ── Helpers ── */

    public function tokenOrigenExpirado(): bool
    {
        return $this->token_origen_expira_at->isPast();
    }

    public function tokenDestinoExpirado(): bool
    {
        return $this->token_destino_expira_at?->isPast() ?? false;
    }
}