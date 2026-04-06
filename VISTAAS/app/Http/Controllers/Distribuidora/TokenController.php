<?php

namespace App\Http\Controllers\Distribuidora;

use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function index()
{
    // TODO: traer clientes elegibles y historial de tokens desde BD
    return view('distribuidora.token');
}
}
