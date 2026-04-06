<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DistribuidorasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ValesController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\DatosFamiliaresController;
use App\Http\Controllers\DatosVehiculosController;
use App\Http\Controllers\AfilialesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//PARA CREAR UNA PERSONA
Route::post('/crearPersona',[PersonasController::Class,'crearPersona']);
//RUTA PARA PRIMERO CREAR UNA PERSONA Y LUEGO AL USUARIO
Route::post('/crearUsuario',[UsersController::class,'crearUsuario']);
//RUTA PARA PRIMERO CREAR UNA PERSONA LUEGO USUARIO Y AL FINAL DISTRIBUIDORA
Route::post('/crearDistribuidora',[DistribuidorasController::class,'crearDistribuidora']);
//RUTA PRAA INICIAR SESION
Route::post('/login', [AuthController::class,'iniciarSesion']);
//RUTA PARA CREAR UN VALE
Route::post('/crearVale',[ValesController::class,'crearPrevale']);
//RUTA PARA CREAR UN CLIENTE
Route::post('/crearCliente',[ClientesController::class,'crearCliente']);
//RUTA PARA QUE EL COORDINADOR CREE UN PRODUCTO
Route::post('/crear/producto',[ProductosController::class,'crearProducto']);
//RUTA PARA CREAR A LOS FAMILIAR DE UNA DISTRIBUIDORA
Route::post('/crearDatoFamilia',[DatosFamiliaresController::class,'crearDatoFamiliar']);

Route::post('/crearVehiculo',[DatosVehiculosController::class,'crearVehiculo']);

Route::post('/crearAfilial',[AfilialesController::class,'crearAfilial']);

Route::get('/lista/productos',[ProductosController::class,'listaProductos']);






Route::get('/distribuidoras-detalle', [DistribuidorasController::class, 'obtenerDetalleDistribuidoras']);

// Route::middleware('auth:sanctum')->group(function () {
    
//     // Ahora esta ruta está protegida
//     Route::post('/crearDistribuidora', [DistribuidorasController::class, 'store']);
    
//     // Puedes crear una ruta rápida para probar tu identidad
//     Route::get('/quien-soy', function (Request $request) {
//         return $request->user()->load('persona', 'role');
//     });
// });