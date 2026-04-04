<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class PersonasController
{
    public function crearPersona(Request $request){
        $persona = $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'sexo'              => 'required|in:H,M,O', // Hombre, Mujer, Otro
            'fecha_nacimiento'  => 'required|date|before:today',
            'CURP'              => 'required|string|size:18|unique:personas,CURP',
            'RFC'               => 'nullable|string|min:12|max:13|unique:personas,RFC',
            'telefono_personal' => 'nullable|string|max:15',
            'celular'           => 'required|string|max:15',
        ]);

        Persona::create($persona);

        return response()->json([
            'mensaje'=>'Persona creada exitosamente!',
            'persona'=>$persona,
        ],200);
    }
}
