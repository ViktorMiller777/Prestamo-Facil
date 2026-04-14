<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\User;
use App\Models\Distribuidora;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class DistribuidorasController
{

    public function listaDistribuidoras()
    {
        $distribuidoras = Distribuidora::with('usuario.persona')->get();
        return view('gerente.distribuidora', compact('distribuidoras'));
    }

    public function distribuidorasInactivas(){
        $distribuidoras = Distribuidora::where('estado', 'inactivo')->with('usuario.persona')->get();

        return view('verificador.notificaciones', compact('distribuidoras'));
        // return response()->json([
        //     'mensaje' => 'exito!',
        //     'distribuidoras' => $distribuidoras
        // ],200);
    }

    public function detalle($id)
    {
        $distribuidora = Distribuidora::where('id', $id)
            ->with('usuario.persona')
            ->firstOrFail();

        return view('verificador.datos-distribuidora', compact('distribuidora'));
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



    public function crearDistribuidora(Request $request)
    {
        $datos = $request->validate([
            // Datos personales (Distribuidora Titular)
            'persona.nombre'            => 'required|string|max:100',
            'persona.apellido'          => 'required|string|max:100',
            'persona.sexo'              => 'required|in:F,M,O',
            'persona.fecha_nacimiento'  => 'required|date|before:today',
            'persona.CURP'              => 'required|string|size:18|unique:personas,CURP',
            'persona.RFC'               => 'nullable|string|min:12|max:13|unique:personas,RFC',
            'persona.telefono_personal' => 'nullable|string|max:15|unique:personas,telefono_personal',
            'persona.celular'           => 'required|string|max:15|unique:personas,celular',

            // Datos del usuario
            'usuario.sucursal_id'       => 'required|exists:sucursales,id',
            'usuario.role_id'           => 'required|exists:roles,id',
            'usuario.email'             => 'required|email|unique:usuarios,email',
            'usuario.password'          => 'required|min:8',

            // Datos distribuidora
            'distribuidora.categoria_id'      => 'required|exists:categorias,id',
            'distribuidora.estado'            => 'required|string',
            'distribuidora.linea_credito'     => 'required|numeric',
            'distribuidora.puntos'            => 'required|integer',
            'distribuidora.geolocalizacion_lat' => 'required|numeric',
            'distribuidora.geolocalizacion_lng' => 'required|numeric',

            // Datos del familiar (Persona secundaria)
            'familiar.nombre'            => 'required|string|max:100',
            'familiar.apellido'          => 'required|string|max:100',
            'familiar.sexo'              => 'required|in:F,M,O',
            'familiar.fecha_nacimiento'  => 'required|date|before:today',
            'familiar.CURP'              => 'required|string|size:18|unique:personas,CURP', 
            'familiar.RFC'               => 'nullable|string|max:13',
            'familiar.telefono_personal' => 'nullable|string|max:15',
            'familiar.celular'           => 'required|string|max:15',
            'familiar.parentesco'        => 'required|string|max:50',

            // Datos del vehículo
            'vehiculo.marca'             => 'required|string|max:50',
            'vehiculo.modelo'            => 'required|string|max:50',
            'vehiculo.color'             => 'required|string|max:30',
            'vehiculo.numero_placas'     => 'required|string|unique:datos_vehiculos,numero_placas',

            // Datos de la filial
            'afilial.nombre'             => 'required|string|max:100',
            'afilial.antiguedad'         => 'required|date',
            'afilial.linea_credito'      => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($datos) {
                
                // 1. Crear Persona Titular
                $persona = Persona::create($datos['persona']);

                // 2. Crear Usuario
                $usuario = User::create([
                    'persona_id'  => $persona->id,
                    'sucursal_id' => $datos['usuario']['sucursal_id'],
                    'role_id'     => $datos['usuario']['role_id'],
                    'email'       => $datos['usuario']['email'],
                    'password'    => Hash::make($datos['usuario']['password']),
                ]);

                // 3. Crear Distribuidora
                $distribuidora = Distribuidora::create([
                    'usuario_id'          => $usuario->id,
                    'categoria_id'        => $datos['distribuidora']['categoria_id'],
                    'estado'              => $datos['distribuidora']['estado'],
                    'linea_credito'       => $datos['distribuidora']['linea_credito'],
                    'puntos'              => $datos['distribuidora']['puntos'],
                    'geolocalizacion_lat' => $datos['distribuidora']['geolocalizacion_lat'],
                    'geolocalizacion_lng' => $datos['distribuidora']['geolocalizacion_lng'],
                ]);

                // 4. Crear Persona Familiar
                $personaFamiliar = Persona::create([
                    'nombre'            => $datos['familiar']['nombre'],
                    'apellido'          => $datos['familiar']['apellido'],
                    'sexo'              => $datos['familiar']['sexo'],
                    'fecha_nacimiento'  => $datos['familiar']['fecha_nacimiento'],
                    'CURP'              => $datos['familiar']['CURP'],
                    'RFC'               => $datos['familiar']['RFC'] ?? null,
                    'telefono_personal' => $datos['familiar']['telefono_personal'] ?? null,
                    'celular'           => $datos['familiar']['celular'],
                ]);

                // 5. Vincular Familiar
                DB::table('datos_familiares')->insert([
                    'distribuidor_id' => $distribuidora->id,
                    'persona_id'      => $personaFamiliar->id,
                    'parentesco'      => $datos['familiar']['parentesco'],
                ]);

                // 6. Registrar Vehículo
                DB::table('datos_vehiculos')->insert([
                    'distribuidor_id' => $distribuidora->id,
                    'marca'           => $datos['vehiculo']['marca'],
                    'modelo'          => $datos['vehiculo']['modelo'],
                    'color'           => $datos['vehiculo']['color'],
                    'numero_placas'   => $datos['vehiculo']['numero_placas'],
                ]);

                // 7. Registrar Filial
                DB::table('afiliales')->insert([
                    'distribuidor_id' => $distribuidora->id,
                    'nombre'          => $datos['afilial']['nombre'],
                    'antiguedad'      => $datos['afilial']['antiguedad'],
                    'linea_credito'   => $datos['afilial']['linea_credito'],
                ]);

                return response()->json([
                    'res' => true,
                    'mensaje' => 'Distribuidora y registros asociados creados exitosamente',
                    'id_distribuidora' => $distribuidora->id
                ], 200);
            });
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'mensaje' => 'Error al crear la distribuidora: ' . $e->getMessage()
            ], 500);
        }
    }

    public function activarDistribuidora($id)
    {
        $distribuidora = Distribuidora::findOrFail($id);
        $distribuidora->update(['estado' => 'activo']);

        return response()->json([
            'res' => true,
            'mensaje' => 'Distribuidora activada correctamente'
        ], 200);
    }
}
