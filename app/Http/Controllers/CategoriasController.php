<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distribuidora;

class CategoriasController extends Controller
{
    public function subirCategoria($id)
    {
        $distribuidora = Distribuidora::findOrFail($id);

        if ($distribuidora->categoria_id < 3) {
            
            $distribuidora->categoria_id += 1;
            $distribuidora->save();

            return response()->json([
                'mensaje' => 'Subiste de categoria exitosamente!',
            ],202);
        }

        return response()->json([
            'mensaje' => 'Ya se alcanzo el nivel maximo',
        ],400);
    }

    public function bajarCategoria($id)
    {
        $distribuidora = Distribuidora::findOrFail($id);

        if ($distribuidora->categoria_id > 1 ) {
            
            $distribuidora->categoria_id -= 1;
            $distribuidora->save();

            return response()->json([
                'mensaje' => 'Bajaste de categoria exitosamente!',
            ],202);
        }

        return response()->json([
            'mensaje' => 'Ya se alcanzo el nivel maximo',
        ],400);
    }
}
