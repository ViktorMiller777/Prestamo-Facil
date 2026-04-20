<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Documento extends Model
{
    protected $table = 'documentos';

    protected $fillable = [
        'tipo',
        'archivo_path',
        'documentable_id',
        'documentable_type',
    ];

    /**
     * Get the parent documentable model (Cliente, Distribuidora, etc.).
     */
    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
