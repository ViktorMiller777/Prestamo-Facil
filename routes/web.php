<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistribuidorasController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Coordinador.cliente_moroso');
});

/* ══════════════════════════════════════
   GERENTE
══════════════════════════════════════ */
Route::prefix('gerente')->name('gerente.')->group(function () {

    Route::get('/configuracion', function () {
        return view('Gerente.configuracion');
    })->name('configuracion');

    // TODO: conectar al controlador cuando esté listo
    Route::post('/configuracion/update', function () {
        return response()->json(['ok' => true]);
    })->name('configuracion.update');

    Route::get('/fechas-corte', function () {
        return view('Gerente.fechas_corte');
    })->name('fechas_corte');

    Route::get('/indicadores', function () {
        return view('Gerente.indicadores');
    })->name('indicadores');

});

/* ══════════════════════════════════════
   COORDINADOR
══════════════════════════════════════ */
Route::prefix('coordinador')->name('coordinador.')->group(function () {

    // TODO: conectar al controlador cuando esté listo
    Route::get('/dashboard', function () {
        return view('Coordinador.dashboard');
    })->name('dashboard');

    Route::get('/cambio_cliente', function () {
        return view('Coordinador.cambio_cliente');
    })->name('cambio_cliente');

    Route::get('/clientes-morosos', function () {
        return view('Coordinador.cliente_moroso');
    })->name('clientes-morosos');

    Route::get('/presolicitudes', function () {
        return view('Coordinador.presolicitudes');
    })->name('presolicitudes');

    // TODO: conectar al controlador cuando esté listo
    Route::post('/clientes-morosos/resolver', function () {
        return response()->json(['ok' => true]);
    })->name('clientes-morosos.resolver');

});

/* ══════════════════════════════════════
   DASHBOARD / AUTH / PROFILE
══════════════════════════════════════ */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/nueva-distribuidora', function () {
        return view('auth.register');
    })->name('distribuidoras.create');

    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])
        ->name('distribuidoras.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';