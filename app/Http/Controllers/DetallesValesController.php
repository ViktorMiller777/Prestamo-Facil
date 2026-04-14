<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleVale;

class DetallesValesController extends Controller
{
    public function listaDetallesVale(){
        $detalles_vales = DetalleVale::all();

        return response()->json([
            'mensaje' => 'lista detalle vales',
            'detalle_vales' => $detalles_vales
        ],200);
    }
}
