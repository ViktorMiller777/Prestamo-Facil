<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Vale;
use App\Models\Persona;
use App\Models\Cliente;

class ValesController
{
    public function listaVales(){
        $vales = Vale::all();

        return response()->json([
            'mensaje' => 'si jalo',
            'vales'   => $vales
        ],200);
    }


    public function crearPrevale(Request $request)
    {
        // 1. Validamos TODO (Datos de Persona, Cliente y Vale)
        $datos = $request->validate([
            // Datos Persona
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'sexo'              => 'required|in:H,M,O',
            'fecha_nacimiento'  => 'required|date',
            'CURP'              => 'required|string|size:18|unique:personas,CURP',
            'RFC'               => 'required|string|size:13|unique:personas,RFC',
            'telefono_personal' => 'required|string|max:15|unique:personas,telefono_personal',
            'celular'           => 'required|string|max:15|unique:personas,celular',           

            // Datos Cliente
            'distribuidor_id'   => 'required|exists:distribuidoras,id',
            'comprobante_domicilio' => 'required',
            'INE'               => 'required',

            // Datos Vale
            'folio'             => 'required|unique:vales,folio',
            'producto_id'       => 'required',
            'estado'            => 'required',
            'fecha_emision'     => 'required|date'
        ]);

        // 2. Iniciamos la transacción para que si algo falla, no se cree nada
        return DB::transaction(function () use ($datos) {
            
            // A. Crear Persona
            $persona = Persona::create([
                'nombre'           => $datos['nombre'],
                'apellido'         => $datos['apellido'],
                'sexo'             => $datos['sexo'],
                'fecha_nacimiento' => $datos['fecha_nacimiento'],
                'CURP'             => $datos['CURP'],
                'RFC'              => $datos['RFC'],
                'telefono_personal'=> $datos['telefono_personal'],
                'celular'          => $datos['celular'],
            ]);

            // B. Crear Cliente usando el ID de la persona recién creada
            $cliente = Cliente::create([
                'persona_id'            => $persona->id,
                'distribuidor_id'       => $datos['distribuidor_id'],
                'comprobante_domicilio' => $datos['comprobante_domicilio'],
                'INE'                   => $datos['INE']
            ]);

            // C. Crear el Vale usando el ID del cliente recién creado
            $vale = Vale::create([
                'folio'           => $datos['folio'],
                'cliente_id'      => $cliente->id, // Aquí vinculamos
                'distribuidor_id' => $datos['distribuidor_id'],
                'producto_id'     => $datos['producto_id'],
                'estado'          => $datos['estado'],
                'fecha_emision'   => $datos['fecha_emision']
            ]);

            return response()->json([
                'mensaje' => 'Registro completo: Persona, Cliente y Vale creados.',
                'vale'    => $vale,
                'cliente' => $cliente
            ], 201);
        });
    }
}
