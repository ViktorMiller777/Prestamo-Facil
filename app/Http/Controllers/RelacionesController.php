<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function listaRelacionesAuth(){
        $distribuidoraId = Auth::user()->distribuidora->id;

        $relaciones = Relacion::where('num_distribuidora', $distribuidoraId)->get();

        return view('distribuidora.relaciones', compact('relaciones'));
    }

}
