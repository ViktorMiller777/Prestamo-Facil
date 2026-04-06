<?php

namespace App\Http\Controllers\Distribuidora;

use App\Http\Controllers\Controller;


class CuentaController extends Controller
{
    public function index()
{
    // TODO: traer corte activo y vales de la BD
    return view('distribuidora.cuenta');
}
}
