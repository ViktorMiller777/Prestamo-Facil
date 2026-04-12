<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\DistribuidorasController;
use Illuminate\Support\Facades\Route;

// ================================
// RUTAS PÚBLICAS
// ================================
Route::get('/', function () {
    return redirect()->route('login');
});

// ================================
// GERENTE - role_id 1
// ================================
Route::middleware(['auth', 'gerente'])->group(function () {
    Route::get('/gerente/dashboard', function () {
        return view('gerente.dashboard');
    })->name('gerente.dashboard');

    Route::get('/gerente/productos', [ProductosController::class, 'listaProductos'])
        ->name('gerente.productos');

    Route::get('/gerente/distribuidora', [DistribuidorasController::class, 'listaDistribuidoras'])
        ->name('gerente.distribuidoras');

    Route::get('/nueva-distribuidora', function () {
        return view('auth.register');
    })->name('distribuidoras.create');

    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])
        ->name('distribuidoras.store');
});


Route::middleware(['auth', 'coordinador'])->group(function () {
    Route::get('/coordinador/dashboard', function () {
        return view('coordinador.dashboard');
    })->name('coordinador.dashboard');
});

// ================================
// VERIFICADOR - role_id 3
// ================================

Route::middleware(['auth', 'verificador'])->group(function () {
    Route::get('/verificador/dashboard', function () {
        return view('verificador.dashboard');
    })->name('verificador.dashboard');

    Route::get('/verificador/notificaciones', [DistribuidorasController::class, 'distribuidorasInactivas'])
        ->name('verificador.notificaciones');

    Route::get('/verificador/distribuidora/{id}', [DistribuidorasController::class, 'detalle'])
        ->name('verificador.detalle');
});

// ================================
// DISTRIBUIDOR - role_id 4
// ================================
Route::middleware(['auth', 'distribuidor'])->group(function () {
    Route::get('/distribuidora/dashboard', function () {
        return view('distribuidora.dashboard');
    })->name('distribuidora.dashboard');

    Route::get('/distribuidora/clientes', function () {
        return view('distribuidora.clientes');
    })->name('clientes.index');

    Route::get('/distribuidora/vales', function () {
        return view('distribuidora.vales');
    })->name('distribuidora.vale');

    Route::get('/distribuidora/productos', [ProductosController::class, 'listaProductos'])
        ->name('productos');
});

Route::middleware(['auth', 'cajera'])->group(function () {
    Route::get('/cajera/dashboard', function () {
        return view('cajera.dashboard');
    })->name('cajera.dashboard');
});

// ================================
// RUTAS COMPARTIDAS AUTH
// ================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';