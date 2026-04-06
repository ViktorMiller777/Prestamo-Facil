<?php

namespace App\Http\Controllers\Cajera;

use App\Http\Controllers\Controller;

class ConciliacionController extends Controller
{
    public function index()
    {
        // TODO: traer historial de conciliaciones cuando haya BD
        // $historial = Conciliacion::latest()->get();

        return view('cajera.conciliacion');
    }
}