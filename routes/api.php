<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//PARA CREAR UNA PERSONA
Route::post('/crearPersona',[PersonasController::Class,'crearPersona']);
