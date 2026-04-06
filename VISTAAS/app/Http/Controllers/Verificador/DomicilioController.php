<?php

namespace App\Http\Controllers\Verificador;

use App\Http\Controllers\Controller;

class DomicilioController extends Controller
{
    public function index($folio = null)
    {
        // TODO: reemplazar con datos reales cuando haya BD
        // $presolicitud = Presolicitud::where('folio', $folio)->firstOrFail();

        $datos = [
            'folio'        => '',
            'nombre'       => '',
            'calle'        => '',
            'ciudad'       => '',
            'ciudad_corta' => '',
            'cp'           => '',
            'latitud'      => '',
            'longitud'     => '',
        ];

        return view('verificador.domicilio', $datos);
    }
}