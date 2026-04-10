<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\User;
use App\Models\Distribuidora; // ¡No olvides este!
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class DistribuidorasController
{
    public function listaDistribuidoras(){
        $distribuidoras = Distribuidora::with('usuario.persona')->get();

        return response()->json([
            'mensaje' => 'Lista de distribuidoras',
            'distribuidoras' => $distribuidoras
        ],200);
    }

    public function obtenerDetalleDistribuidoras()
    {
        // Usamos with() para cargar las relaciones de golpe
        // Si tus modelos tienen estos nombres de funciones, funcionará:
        $distribuidoras = Distribuidora::with([
            'usuario.persona', // Trae el usuario y su info personal
            'usuario.role',    // Trae el rol del usuario
            'categoria',       // Trae la categoría (Bronce, Plata, Oro)
            'familiar.persona', // Trae el familiar y su info personal
            'vehiculo',
            'afilial'
        ])->get();

        // Retornamos la colección para verla en Postman
        return response()->json([
            'mensaje' => 'Lista de distribuidoras con relaciones',
            'cantidad' => $distribuidoras->count(),
            'data' => $distribuidoras
        ], 200);
    }
    public function crearDistribuidora(Request $request){
        $datos = $request->validate([
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'sexo'              => 'required|in:H,M,O', // Hombre, Mujer, Otro
            'fecha_nacimiento'  => 'required|date|before:today',
            'CURP'              => 'required|string|size:18|unique:personas,CURP',
            'RFC'               => 'nullable|string|min:12|max:13|unique:personas,RFC',
            'telefono_personal' => 'nullable|string|max:15',
            'celular'           => 'required|string|max:15',

            //Datos del usuario
            'sucursal_id'       => 'required|exists:sucursales,id',
            'role_id'           => 'required|exists:roles,id',
            'email'             => 'required|email|unique:usuarios,email',
            'password'          => 'required|min:8',

            //datos distribuidora
            'categoria_id'      => 'required|exists:categorias,id',
            'estado'            => 'required',
            'linea_credito'     => 'required|numeric',
            'puntos'            => 'required|integer',
            'geolocalizacion_lat' => 'required|numeric',
            'geolocalizacion_lng' => 'required|numeric',
        ]);

        return DB::transaction(function () use ($datos){
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

            $distribuidora = Distribuidora::create([
                'usuario_id'        => $usuario->id,
                'categoria_id'      => $datos['categoria_id'],
                'estado'            => $datos['estado'],
                'linea_credito'     => $datos['linea_credito'],
                'puntos'            => $datos['puntos'],
                'geolocalizacion_lat' => $datos['geolocalizacion_lat'],
                'geolocalizacion_lng' => $datos['geolocalizacion_lng']
            ]);

            return response()->json([
                'mensaje'=>'Distribuidora creada exitosamente',
                'distribuidora' => $distribuidora,
            ],200);
        });
    }
}
