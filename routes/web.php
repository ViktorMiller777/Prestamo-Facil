<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PersonasController;

Route::get('/', function () {
    return view('welcome');
});
//LISTA DE TODOS LOS ROLES
Route::get('/roles',[RolesController::class,'listaDeRoles']);

