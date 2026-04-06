<?php

namespace App\Http\Controllers\Cajera;

use App\Http\Controllers\Controller;

class PrevalesController extends Controller
{
        public function index()
    {
    // TODO: traer distribuidoras y productos de la BD
        return view('cajera.prevales');
    }
}



