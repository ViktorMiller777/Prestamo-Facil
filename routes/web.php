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

Route::get('/distribuidora', function (){
    return view ('distribuidora.distribuidora');
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


// ── Verificador
Route::prefix('verificador')->name('verificador.')->group(function () {
    Route::get('/bandeja', function () {
        return view('Verificador.bandeja');
    })->name('bandeja');
    Route::get('/expediente/{folio?}', function () {
        return view('Verificador.expediente');
    })->name('expediente');
    Route::get('/domicilio/{folio?}', function () {
        return view('Verificador.domicilio');
    })->name('domicilio');
});

// ── Cajera
Route::prefix('cajera')->name('cajera.')->group(function () {
    Route::get('/conciliacion', function () {
        return view('Cajera.conciliacion');
    })->name('conciliacion');
    Route::get('/prevales', function () {
        return view('Cajera.prevales');
    })->name('prevales');
    Route::get('/monitor', function () {
        return view('Cajera.monitor');
    })->name('monitor');
});

/// ── Distribuidora
Route::prefix('distribuidora')->name('distribuidora.')->group(function () {
    Route::get('/cuenta', function () {
        return view('Distribuidora.cuenta');
    })->name('cuenta');
    Route::get('/puntos', function () {
        return view('Distribuidora.puntos');
    })->name('puntos');
    Route::get('/clientes', function () {
        return view('Distribuidora.clientes');
    })->name('clientes');
    Route::get('/token', function () {
        return view('Distribuidora.token');
    })->name('token');
});
// ── Transversales
Route::get('/notificaciones', function () {
    return view('general.notificaciones');
})->name('notificaciones');
Route::get('/perfil', function () {
    return view('general.perfil');
})->name('perfil');


require __DIR__.'/auth.php';
