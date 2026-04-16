<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Vale;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Distribuidora;
use App\Models\DetalleVale;
use App\Models\Producto;
use App\Models\Relacion;

class ValesController
{
    public function listaVales(){
        $vales = Vale::all();

        return response()->json([
            'mensaje' => 'si jalo',
            'vales'   => $vales
        ],200);
    }

    public function valesPorDistribuidora()
    {
        $distribuidora = Distribuidora::where('usuario_id', auth()->id())->firstOrFail();

        $vales = Vale::where('distribuidor_id', $distribuidora->id)
            ->with('cliente.persona', 'producto')
            ->get();

        return view('distribuidora.vales', compact('vales'));
    }

    public function crearPrevale(Request $request)
    {
        // 1. Validamos TODO (Datos de Persona, Cliente y Vale)
        $datos = $request->validate([
            // Datos Persona
            'nombre'            => 'required|string|max:100',
            'apellido'          => 'required|string|max:100',
            'sexo'              => 'required|in:F,M,O',
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
        ]);

        // 2. Iniciamos la transacción para que si algo falla, no se cree nada
        return DB::transaction(function () use ($datos) {

            $producto = Producto::findOrFail($datos['producto_id']);            
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
                'distribuidor_id' => $datos['distribuidor_id'], //$distribuidora,
                'producto_id'     => $datos['producto_id'],
                'estado'          => $datos['estado'],
                'fecha_emision'   => now(),
            ]);

            $monto = $producto->monto; 
            $comision = $producto->porcentaje_comision;
            $interes_quincenal = $producto->interes_quincenal;   
            
            $seguro = $producto->seguro;
            $quincenas = $producto->quincenas;

            $monto_comision = $monto * $comision;
            $monto_comision_quincenal = $monto * $interes_quincenal;
            $total_comision_quincenal = $monto_comision_quincenal * $quincenas;

            $TOTAL = $monto + $monto_comision + $seguro + $total_comision_quincenal;
            $pago = $TOTAL / $quincenas;

            $vale->load('distribuidora.usuario.persona');
            
            $detalle_vale = DetalleVale::create([
                'relacion_id'                 => null,
                'vale_id'                     => $vale->id,
                'monto'                       => $producto->monto,
                'porcentaje_comision'         => $producto->porcentaje_comision,
                'monto_comision_calculada'    => $TOTAL,
                'interes_quincenal'           => $producto->interes_quincenal,
                'quincenas'                   => $producto->quincenas,
                'seguro'                      => $producto->seguro,
                'comision'                    => $monto_comision_quincenal,
                'pago'                        => $pago,
                'nombre_cliente'              => $persona->nombre,
                'nombre_distribuidora'        => $vale->distribuidora->usuario->persona->nombre,
                'fecha_emision'               => $vale->fecha_emision,
                'producto_folio'              => $vale->folio,
            ]);

            return response()->json([
                'mensaje' => 'Registro completo: Persona, Cliente y Vale creados.',
                'vale'    => $vale,
                'cliente' => $cliente
            ], 201);
        });
    }
}
