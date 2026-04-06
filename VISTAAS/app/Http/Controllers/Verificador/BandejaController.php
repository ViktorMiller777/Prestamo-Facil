<?php

namespace App\Http\Controllers\Verificador;

use App\Http\Controllers\Controller;

class BandejaController extends Controller
{
    public function index()
    {
        // TODO: reemplazar con datos reales cuando haya BD
        // $presolicitudes = Presolicitud::all();
        // $totales = [...];

        $presolicitudes = [];
        $totales = [
            'pendientes' => 0,
            'revision'   => 0,
            'aprobadas'  => 0,
            'rechazadas' => 0,
        ];

        return view('verificador.bandeja', compact('presolicitudes', 'totales'));
    }
}