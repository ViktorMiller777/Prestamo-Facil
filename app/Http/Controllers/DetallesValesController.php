<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleVale;
use App\Models\Relacion;


class DetallesValesController extends Controller
{
    public function listaDetallesVale(){
        $detalles_vales = DetalleVale::all();

        return response()->json([
            'mensaje' => 'lista detalle vales',
            'detalle_vales' => $detalles_vales
        ],200);
    }

    public function verDetalleRelacion($id) {
        // 1. Obtenemos la información base de la relación
        $relacion = Relacion::findOrFail($id);

        // 2. Obtenemos todos los vales asociados a esa relación específica
        $detalles = DetalleVale::where('relacion_id', $id)->get();

        // 3. Retornamos ambos en un solo objeto JSON
        return view('distribuidora.detalle_vale', compact('relacion', 'detalles'));
        // return response()->json([
        //     'relacion' => $relacion,
        //     'detalles' => $detalles
        // ], 200);
    }
}