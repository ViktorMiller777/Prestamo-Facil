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
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\RelacionesController;
use App\Http\Controllers\ConfiguracionesController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//PARA CREAR UNA PERSONA
Route::post('/crear/persona',[PersonasController::Class,'crearPersona']);
//RUTA PARA PRIMERO CREAR UNA PERSONA Y LUEGO AL USUARIO
Route::post('/crear/usuario',[UsersController::class,'crearUsuario']);
//RUTA PARA PRIMERO CREAR UNA PERSONA LUEGO USUARIO Y AL FINAL DISTRIBUIDORA
Route::post('/crear/distribuidora',[DistribuidorasController::class,'crearDistribuidora']);
//RUTA PRAA INICIAR SESION
Route::post('/login', [AuthController::class,'iniciarSesion']);
//RUTA PARA CREAR UN VALE
Route::post('/crear/vale',[ValesController::class,'crearPrevale']);
//RUTA PARA CREAR UN CLIENTE
Route::post('/crear/cliente',[ClientesController::class,'crearCliente']);
//RUTA PARA QUE EL COORDINADOR CREE UN PRODUCTO
Route::post('/crear/producto',[ProductosController::class,'crearProducto']);
//RUTA PARA CREAR A LOS FAMILIAR DE UNA DISTRIBUIDORA
Route::post('/crear/datoFamilia',[DatosFamiliaresController::class,'crearDatoFamiliar']);
//Ruta para crear un vehiculo a una distribuidora
Route::post('/crear/vehiculo',[DatosVehiculosController::class,'crearVehiculo']);
//Ruta para crear un afiliar a una distribuidora
Route::post('/crear/afilial',[AfilialesController::class,'crearAfilial']);
//Ruta para mostrar la lista de lo productos
Route::get('/lista/productos',[ProductosController::class,'listaProductos']);
//Ruta para editar un producto por su ID utilizando patch para campos parciales
Route::patch('/editar/producto/{id}',[ProductosController::class,'editarProducto']);
//Ruta de lista de las distribuidoras
Route::get('/lista/distribuidoras',[DistribuidorasController::class,'listaDistribuidoras']);
//Ruta para subir de categoria una distribuidora
Route::patch('/subir/categoria/{id}',[CategoriasController::class,'subirCategoria']);
//Ruta para bajar de categoria un distribuidora
Route::patch('/bajar/categoria/{id}',[CategoriasController::class,'bajarCategoria']);
//Ruta para ver todas las relaciones con el nombre de la distribuidora
Route::get('lista/relaciones',[RelacionesController::class,'listaRelaciones']);
//Ruta de todos los vales creados 
Route::get('lista/vales',[ValesController::class,'listaVales']);
//Ruta para lista de todos los clientes 
Route::get('lista/clientes',[ClientesController::class,'listaClientes']);
//Ruta de todos los clientes por id de distribuidora
Route::get('clientes/distribuidora/{id}',[ClientesController::class,'clientesDistribuidora']);
//Ruta de todas las distribuidoras inactivas para le verificador
Route::get('/lista/distribuidoras-inactivas',[DistribuidorasController::class,'distribuidorasInactivas']);
//Ruta para activar una distribuidora
Route::put('/activar/distribuidora/{id}', [DistribuidorasController::class, 'activarDistribuidora']);
//Ruta para elimiar un producto alv
Route::delete('/eliminar/producto/{id}', [ProductosController::class, 'eliminarProducto']);
//Ruta de relaciones de la distribuidora logueada
Route::get('/distribuidora/relaciones',[RelacionesController::class,'listaRelacionesAuth']);
//Ruta para activar a una distribuidora
Route::patch('/activar/distribuidora/{id}', [DistribuidorasController::class, 'activarDistribuidora']);

Route::patch('/inactivar/distribuidora/{id}', [DistribuidorasController::class, 'inactivarDistribuidora']);

// Rutas de configuración de Bonos
Route::post('/configuracion/store', [ConfiguracionesController::class, 'store']);
