<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistribuidorasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Gerente.configuracion');
});

Route::prefix('gerente')->name('gerente.')->group(function () {

    Route::get('/configuracion', function () {
        return view('Gerente.configuracion');
    })->name('configuracion');

    Route::get('/fechas-corte', function () {
        return view('Gerente.fechas_corte');
    })->name('fechas_corte');

    Route::get('/indicadores', function () {
        return view('Gerente.indicadores');
    })->name('indicadores');

});
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
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
