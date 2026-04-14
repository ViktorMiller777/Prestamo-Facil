<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Exception;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductosController
{
    public function listaProductos(){
        $productos = Producto::all();
        return match(auth()->user()->role_id) {
            4 => view('distribuidora.productos', compact('productos')),  // distribuidor
            1 => view('gerente.productos', compact('productos')),        // gerente
            default => abort(403, 'No tienes acceso.')
        };
    }


    public function crearProducto(Request $request)
    {
        try {
            $data = $request->validate([
                'monto'               => 'required|numeric|min:0.01',
                'porcentaje_comision' => 'required|numeric|between:0,100',
                'seguro'              => 'required|numeric|min:0',
                'quincenas'           => 'required|integer|min:1|max:96',
                'interes_quincenal'   => 'required|numeric|min:0',
            ]);

            $data['porcentaje_comision'] = (float) $data['porcentaje_comision'] / 100;
            $data['interes_quincenal']   = (float) $data['interes_quincenal'] / 100;

            $producto = Producto::create($data);

            return response()->json([
                'status' => 'success',
                'mensaje' => 'Producto creado exitosamente!',
                'data' => $producto,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Error de validación: faltan datos o son incorrectos',
                'errores' => $e->errors(),
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'No se pudo crear el producto',
                // Recuerda que config('app.debug') ayuda a ocultar esto en producción
                'error_detalle' => config('app.debug') ? $e->getMessage() : 'Error interno del servidor',
            ], 500);
        }
    }

    public function editarProducto(Request $request, $id){
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'monto'                => 'sometimes|numeric|min:0',
            'porcentaje_comision'  => 'sometimes|numeric|min:0|max:100',
            'seguro'               => 'sometimes|numeric|min:0',
            'quincenas'            => 'sometimes|integer|min:1',
            'interes_quincenal'    => 'sometimes|numeric|min:0',
            'activo'               => 'sometimes|boolean',
        ]);
        

        $producto->update($validated);

        return response()->json([
            'mensaje'  => 'Producto actualizado correctamente',
            'producto' => $producto
        ], 200);
    }

    public function eliminarProducto($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();

        return response()->json([
            'status'  => 'success',
            'mensaje' => 'Producto eliminado correctamente'
        ], 200);
    }
}

    
