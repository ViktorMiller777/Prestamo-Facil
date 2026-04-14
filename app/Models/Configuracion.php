<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = [
        'clave', 'valor', 'descripcion'
    ];

    /**
     * Método para obtener el valor de una configuración por su clave
     */
    public static function obtener($clave, $default = null)
    {
        // Cambiamos 'nombre' por 'clave' para que coincida con tu fillable
        $config = self::where('clave', $clave)->first();
        return $config ? $config->valor : $default;
    }
}