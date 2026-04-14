<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Relacion;

class RelacionesController
{
    public function listaRelaciones(){
        $relaciones = Relacion::with('distribuidora.usuario.persona')->get();

        return response()->json([
            'mensaje' => 'Consulta exitosa',
            'relaciones' => $relaciones
        ],200);
    }

    public function crearRelacion(Request $request)
    {
        // 1. Validación robusta (Si falla, Laravel devuelve 422 automáticamente)
        $datos = $request->validate([
            'distribuidor_id'   => 'required|exists:distribuidoras,id',
            'folio_referencia'  => 'required|string|unique:relaciones,folio_referencia',
            'fecha_limite_pago' => 'required|date',
            'total_a_pagar'     => 'required|numeric|min:0',
        ]);

        // 2. Creación directa
        // Usamos el retorno de create() para tener el ID y timestamps reales
        $relacion = Relacion::create($datos);

        // 3. Respuesta estandarizada con código 201 (Created)
        return response()->json([
            'mensaje'  => 'Relación creada exitosamente',
            'relacion' => $relacion
        ], 201);
    }
}
