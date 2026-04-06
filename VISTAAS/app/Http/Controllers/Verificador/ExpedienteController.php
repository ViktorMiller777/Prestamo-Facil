<?php

namespace App\Http\Controllers\Verificador;

use App\Http\Controllers\Controller;

class ExpedienteController extends Controller
{
    public function index($folio = null)
    {
        // TODO: reemplazar con datos reales cuando haya BD
        // $presolicitud = Presolicitud::where('folio', $folio)->firstOrFail();

        $datos = [
            'folio'           => '',
            'nombre'          => '',
            'iniciales'       => '',
            'domicilio'       => '',
            'fecha_solicitud' => '',
            'docs_cargados'   => 0,
            'docs_total'      => 4,
            'curp'            => '',
            'telefono'        => '',
            'calle'           => '',
            'ciudad'          => '',
            'vehiculo'        => '',
        ];

        return view('verificador.expediente', $datos);
    }
}