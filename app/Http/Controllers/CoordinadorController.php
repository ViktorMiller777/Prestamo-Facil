<?php

namespace App\Http\Controllers;

use App\Models\CambioDistribuidora;

class CoordinadorController extends Controller
{
   public function vistaCambios()
{
    $cambios = CambioDistribuidora::with([
            'cliente.persona',
            'distribuidoraOrigen.usuario.persona',
            'distribuidoraDestino.usuario.persona',
        ])
        ->orderByDesc('created_at')
        ->paginate(15);

    // Cambia 'cambios' por 'vales'
    return view('coordinador.cambio_cliente', compact('cambios')); 
}
}