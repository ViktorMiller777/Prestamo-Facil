<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Persona;
use App\Models\User;

class UsersController
{
    public function crearUsuario(Request $request){
        $existe = Persona::where('CURP', $request->CURP)
                 ->orWhere('RFC', $request->RFC)
                 ->exists();

        if ($existe) {
            return response()->json([
                'mensaje' => 'Datos existentes, verifica tus datos (CURP o RFC ya registrados)',
            ], 400);
        }
        
        $datos = $request->validate([
            //Datos de la persona
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'sexo'              => 'required|in:F,M,O', 
            'fecha_nacimiento'  => 'required|date|before:today',
            'CURP'              => 'required|string|size:18|unique:personas,CURP',
            'RFC'               => 'nullable|string|min:12|max:13|unique:personas,RFC',
            'telefono_personal' => 'nullable|string|max:15',
            'celular'           => 'required|string|max:15',

            //Datos del usuario
            'sucursal_id'       => 'required|exists:sucursales,id',
            'role_id'           => 'required|exists:roles,id',
            'email'             => 'required|email|unique:usuarios,email',
            'password'          => 'required|min:8'
        ]);

        return DB::transaction(function () use ($datos) {
            
            // 3. Creamos primero la Persona
            $persona = Persona::create([
                'nombre'           => $datos['nombre'],
                'apellido'         => $datos['apellido'],
                'sexo'             => $datos['sexo'],
                'fecha_nacimiento' => $datos['fecha_nacimiento'],
                'CURP'             => $datos['CURP'],
                'RFC'              => $datos['RFC'],
                'telefono_personal' => $datos['telefono_personal'],
                'celular'          => $datos['celular'],
            ]);

            $usuario = User::create([
                'persona_id'  => $persona->id,
                'sucursal_id' => $datos['sucursal_id'],
                'role_id'     => $datos['role_id'],
                'email'       => $datos['email'],
                'password'    => Hash::make($datos['password']), // ¡Siempre encripta la contraseña!
            ]);

            return response()->json([
                'mensaje' => 'Persona y Usuario creados exitosamente!',
                'user_id' => $usuario->id,
                'persona' => $persona
            ], 201);
        });
    }
}
