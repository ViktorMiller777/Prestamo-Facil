<?php

namespace App\Http\Controllers\Distribuidora;

use App\Http\Controllers\Controller;

class ClientesController extends Controller
{
    public function index()
{
    // TODO: traer clientes de la distribuidora autenticada desde BD
    return view('distribuidora.clientes');
}
}
