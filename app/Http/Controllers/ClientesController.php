<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Cliente;

class ClientesController
{
    public function listaClientes(){
        $clientes = Cliente::with('persona')->get();

        return response()->json([
            'mensaje' => 'Todos los clientes',
            'clientes' => $clientes
        ],200);

        
    }


    public function clientesDistribuidora($id){
        $clientes = Cliente::where('distribuidor_id', $id)->with('persona') ->get();
        return response()->json([
            'mensaje' => 'clientes de distribuidora',
            'clientes' => $clientes
        ],200);
    }

    public function crearCliente(Request $request){
        $datos = $request->validate([
            //Datos de la persona
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'sexo'              => 'required|in:H,M,O', // Hombre, Mujer, Otro
            'fecha_nacimiento'  => 'required|date|before:today',
            'CURP'              => 'required|string|size:18|unique:personas,CURP',
            'RFC'               => 'nullable|string|min:12|max:13|unique:personas,RFC',
            'telefono_personal' => 'nullable|string|max:15',
            'celular'           => 'required|string|max:15',

            //Datos del cliente
            'distribuidor_id'   => 'required',
            'comprobante_domicilio' => 'required',
            'INE'               =>  'required'
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

            $cliente = Cliente::create([
                'persona_id'            => $persona->id,
                'distribuidor_id'       => $datos['distribuidor_id'],
                'comprobante_domicilio' => $datos['comprobante_domicilio'],
                'INE'                   => $datos['INE']
            ]);

            return response()->json([
                'mensaje' => 'Persona y Cliente creados exitosamente!',
                'user_id' => $cliente->id,
                'persona' => $persona
            ], 201);
        });
    }
}
