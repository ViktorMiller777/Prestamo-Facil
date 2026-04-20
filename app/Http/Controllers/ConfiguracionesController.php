<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionesController extends Controller
{
    /**
     * Muestra la vista de configuración con los valores actuales.
     */
    public function index()
    {
        $configuraciones = Configuracion::all();
        return view('gerente.configuracion', compact('configuraciones'));
    }

    /**
     * Actualiza múltiples configuraciones a la vez.
     */
    public function update(Request $request)
    {
        $request->validate([
            'configuraciones' => 'required|array',
            'configuraciones.*' => 'required|string',
        ]);

        foreach ($request->configuraciones as $clave => $valor) {
            Configuracion::where('clave', $clave)->update(['valor' => $valor]);
        }

        return redirect()->back()->with('success', 'Configuraciones actualizadas correctamente.');
    }

    /**
     * Almacena una nueva configuración (si es necesario).
     */
    public function store(Request $request)
    {
        $request->validate([
            'clave' => 'required|string|unique:configuraciones,clave',
            'valor' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        Configuracion::create($request->only(['clave', 'valor', 'descripcion']));

        return redirect()->back()->with('success', 'Nueva configuración guardada.');
    }
}
