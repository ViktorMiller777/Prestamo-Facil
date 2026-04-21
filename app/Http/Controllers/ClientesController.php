<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\Distribuidora;

class ClientesController extends Controller
{
    public function listaClientes()
    {
        $clientes = Cliente::with('persona')->get();

        return response()->json([
            'mensaje'  => 'Todos los clientes',
            'clientes' => $clientes,
        ], 200);
    }

    public function listaClientesCoordinador(Request $request)
    {
        $query = Cliente::with(['persona', 'distribuidora.usuario.persona', 'documentos']);

        if ($request->filled('distribuidora')) {
            $nombre = $request->input('distribuidora');
            $query->whereHas('distribuidora.usuario.persona', function ($q) use ($nombre) {
                $q->where('nombre', 'like', "%{$nombre}%")
                  ->orWhere('apellido', 'like', "%{$nombre}%");
            });
        }

        $clientes = $query->paginate(5)->withQueryString();

        return view('coordinador.clientes', compact('clientes'));
    }

    /**
     * Vista de clientes de la distribuidora autenticada.
     * Carga cambioActivo para mostrar si ya tiene proceso pendiente,
     * y la lista de distribuidoras destino para el select del modal.
     */
    public function clientesDistribuidora()
    {
        $distribuidoraId = auth()->user()->distribuidora->id;

        // Cargar clientes con su cambio activo (pendiente o coordinador_validado)
        $clientes = Cliente::where('distribuidor_id', $distribuidoraId)
            ->with([
                'persona',
                'documentos',
                'cambioActivo', // relación definida en el modelo Cliente
            ])
            ->get();

        // Distribuidoras destino disponibles (activas, excluyendo la propia)
        $distribuidoras = Distribuidora::whereIn('estado', ['activo', 'moroso'])
            ->where('id', '!=', $distribuidoraId)
            ->with('usuario.persona')
            ->get();

        return view('distribuidora.clientes', compact('clientes', 'distribuidoras'));
    }

    public function crearCliente(Request $request)
    {
        $datos = $request->validate([
            'nombre'               => 'required|string|max:100',
            'apellido'             => 'required|string|max:100',
            'sexo'                 => 'required|in:M,F,O',
            'fecha_nacimiento'     => 'required|date|before:today',
            'CURP'                 => 'required|string|size:18|unique:personas,CURP',
            'RFC'                  => 'nullable|string|min:12|max:13|unique:personas,RFC',
            'telefono_personal'    => 'nullable|string|max:15',
            'celular'              => 'required|string|max:15',
            'distribuidor_id'      => 'required|exists:distribuidoras,id',
            'comprobante_domicilio'=> 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'INE'                  => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        return DB::transaction(function () use ($datos, $request) {

            $persona = Persona::create([
                'nombre'            => $datos['nombre'],
                'apellido'          => $datos['apellido'],
                'sexo'              => $datos['sexo'],
                'fecha_nacimiento'  => $datos['fecha_nacimiento'],
                'CURP'              => $datos['CURP'],
                'RFC'               => $datos['RFC'],
                'telefono_personal' => $datos['telefono_personal'],
                'celular'           => $datos['celular'],
            ]);

            $cliente = Cliente::create([
                'persona_id'      => $persona->id,
                'distribuidor_id' => $datos['distribuidor_id'],
            ]);

            if ($request->hasFile('comprobante_domicilio')) {
                $path = $request->file('comprobante_domicilio')
                    ->store('documentos/clientes/comprobantes', 'spaces');
                $cliente->documentos()->create([
                    'tipo'         => 'Comprobante Domicilio',
                    'archivo_path' => $path,
                ]);
            }

            if ($request->hasFile('INE')) {
                $path = $request->file('INE')
                    ->store('documentos/clientes/ine', 'spaces');
                $cliente->documentos()->create([
                    'tipo'         => 'INE',
                    'archivo_path' => $path,
                ]);
            }

            return response()->json([
                'mensaje'  => 'Persona y Cliente creados exitosamente!',
                'user_id'  => $cliente->id,
                'persona'  => $persona,
            ], 201);
        });
    }
}