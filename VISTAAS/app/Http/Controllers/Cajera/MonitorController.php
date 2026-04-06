<?php

namespace App\Http\Controllers\Cajera;

use App\Http\Controllers\Controller;


class MonitorController extends Controller
{
    public function index()
{
    // TODO: traer distribuidoras morosas de la BD
    return view('cajera.monitor');
}
}
