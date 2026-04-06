<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistribuidorasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



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
