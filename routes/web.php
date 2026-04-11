<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\DistribuidorasController;
use App\Http\Middleware\SoloDistribuidoras;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Dashboard para gerente, coordinado y cajera 
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', SoloDistribuidoras::class])->group(function () {
    
    Route::get('/distribuidora/dashboard', function (){
        return view('distribuidora.distribuidora');
    })->name('distribuidora.dashboard');

    Route::get('/distribuidora/clientes', function () {
        return view('distribuidora.clientes');
    })->name('clientes.index');

    Route::get('/distribuidora/productos', [ProductosController::class, 'listaProductos'])->name('productos');

});

Route::middleware('auth')->group(function () {

    Route::get('/productos', function () {
        return view('productos');
    })->name('productos.index');
    
    //Esta ruta manda a la vista de register 
    Route::get('/nueva-distribuidora', function () {
        return view('auth.register'); // Aquí le dices que use la vista de register auth
    })->name('distribuidoras.create');

    // 2. Esta ruta Recibe los datos y dispara tu controlador
    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])
        ->name('distribuidoras.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
