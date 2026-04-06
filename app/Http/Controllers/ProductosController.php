<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductosController
{
    public function listaProductos(){
        $productos = Producto::all();

        return response()->json([
            'mensaje' => 'Lista de productos',
            'productos' => $productos
        ],200);
    }


    public function crearProducto(Request $request){
        $producto = $request->validate([
            'monto'               => 'required',
            'porcentaje_comision' => 'required',
            'seguro'              => 'required',
            'quincenas'           => 'required',
            'interes_quincenal'   => 'required',
            'activo'              => 'required',
        ]);

        Producto::create($producto);

        return response()->json([
            'mensaje'=>'Producto creado exitosamente!',
            'producto'=>$producto,
        ],200);
    }
}
