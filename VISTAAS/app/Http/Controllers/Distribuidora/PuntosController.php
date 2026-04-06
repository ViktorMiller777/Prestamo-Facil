<?php

namespace App\Http\Controllers\Distribuidora;

use App\Http\Controllers\Controller;


class PuntosController extends Controller
{
    public function index()
{
    // TODO: traer puntos e historial de la BD
    return view('distribuidora.puntos');
}
}
