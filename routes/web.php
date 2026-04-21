<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\DistribuidorasController;
use App\Http\Controllers\ValesController;
use App\Http\Controllers\RelacionesController;
use App\Http\Controllers\DetallesValesController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ConfiguracionesController;
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

    Route::get('/gerente/productos', [ProductosController::class, 'listaProductos'])->name('gerente.productos');

    Route::get('/gerente/distribuidora', [DistribuidorasController::class, 'listaDistribuidoras'])->name('gerente.distribuidoras');

    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])->name('distribuidoras.store');
    
    Route::post('/gerente/distribuidora/{id}/estado', [DistribuidorasController::class, 'actualizarEstado'])->name('gerente.distribuidoras.estado');

    Route::get('/gerente/presolicitudes',[DistribuidorasController::class,'distribuidorasInactivas'])->name('gerente.presolicitud');

    Route::get('/gerente/configuracion', [ConfiguracionesController::class, 'index'])->name('gerente.configuracion');
    Route::post('/gerente/configuracion', [ConfiguracionesController::class, 'update'])->name('gerente.configuracion.update');
});

// ================================
// COORDINADOR - role_id 2
// ================================

Route::middleware(['auth', 'coordinador'])->group(function () {
    Route::get('/coordinador/dashboard', function () {
        return view('coordinador.dashboard');
    })->name('coordinador.dashboard');

    Route::get('/coordinador/notificaciones', function () {
        return view('coordinador.notificaciones');
    })->name('coordinador.notificaciones');

    Route::get('/nueva-distribuidora', function () {
        return view('auth.register');
    })->name('distribuidoras.create');

    Route::get('/coordinador/distribuidora', [DistribuidorasController::class, 'listaDistribuidorasCoordinador'])->name('coordinador.distribuidoras');
    Route::get('/coordinador/vales', [ValesController::class, 'listaValesCoordinador'])->name('coordinador.vales');
    Route::get('/coordinador/clientes', [ClientesController::class, 'listaClientesCoordinador'])->name('coordinador.clientes');
    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])->name('distribuidoras.store');
});

// ================================
// VERIFICADOR - role_id 3
// ================================

Route::middleware(['auth', 'verificador'])->group(function () {
    Route::get('/verificador/dashboard', function () {return view('verificador.dashboard');})->name('verificador.dashboard');

    Route::get('/verificador/presolicitudes', [DistribuidorasController::class, 'distribuidorasPresolicitud'])->name('verificador.presolicitud');

    Route::get('/verificador/distribuidora/{id}', [DistribuidorasController::class, 'detalle'])->name('verificador.detalle');
});

// ================================
// DISTRIBUIDOR - role_id 4
// ================================

Route::middleware(['auth', 'distribuidor'])->group(function () {
    Route::get('/distribuidora/dashboard', function () {
        return view('distribuidora.dashboard');
    })->name('distribuidora.dashboard');

    Route::get('/distribuidora/clientes', [ClientesController::class,'clientesDistribuidora'])->name('distribuidora.clientes');

    Route::get('/distribuidora/vales', [ValesController::class, 'valesPorDistribuidora'])->name('distribuidora.vale');

    Route::get('/distribuidora/productos', [ProductosController::class, 'listaProductos'])->name('productos');
    // Asegúrate de que apunte a listaRelacionesAuth y no a otra función
    Route::get('/distribuidora/relaciones', [RelacionesController::class, 'listaRelacionesAuth'])->name('relaciones');

    Route::get('/distribudora/detalle_vale/{id}',[DetallesValesController::class, 'verDetalleRelacion'])->name('detalle_vale');

});


// ================================
// CAJERA - role_id 5
// ================================

Route::middleware(['auth', 'cajera'])->group(function () {
    Route::get('/cajera/dashboard', function () {
        return view('cajera.dashboard');
    })->name('cajera.dashboard');

    Route::get('/cajera/prevale', [ValesController::class, 'listaVales'])->name('cajera.prevale');
    Route::get('/cajera/prevale/buscar/{folio}', [ValesController::class, 'buscarPorFolio']);
    Route::post('/cajera/prevale/confirmar/{id}', [ValesController::class, 'confirmarPrevale']);

    Route::get('/cajera/conciliacion', [ValesController::class, 'vistaConciliacion'])->name('cajera.conciliacion');
    Route::get('/cajera/conciliacion/buscar/{folio}', [ValesController::class, 'buscarValeActivo']);
});

// ================================
// RUTAS COMPARTIDAS AUTH
// ================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return match(auth()->user()->role_id) {
        1 => redirect()->route('gerente.dashboard'),
        2 => redirect()->route('coordinador.notificaciones'),
        3 => redirect()->route('verificador.dashboard'),
        4 => redirect()->route('distribuidora.dashboard'),
        5 => redirect()->route('cajera.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';