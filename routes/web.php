<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\DistribuidorasController;
use App\Http\Controllers\ValesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ================================
// RUTAS PÚBLICAS
// ================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/db-status', function () {
    try {
        $result = DB::select('SELECT @@server_id as server_id, @@hostname as hostname');
        
        return [
            'client_ip' => request()->ip(),
            'is_vpn' => strpos(request()->ip(), '10.200.0.') === 0,
            'current_mysql_host' => config('database.connections.mysql.host'),
            'mysql_server_id' => $result[0]->server_id ?? 'unknown',
            'mysql_hostname' => $result[0]->hostname ?? 'unknown',
            'app_server_hostname' => gethostname(),
        ];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
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

    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])->name('distribuidoras.store');
});

// ================================
// COORDINADOR - role_id 2
// ================================

Route::middleware(['auth', 'coordinador'])->group(function () {
    Route::get('/coordinador/dashboard', function () {
        return view('coordinador.dashboard');
    })->name('coordinador.dashboard');

    Route::get('/nueva-distribuidora', function () {
        return view('auth.register');
    })->name('distribuidoras.create');

    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])
        ->name('distribuidoras.store');
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

    Route::get('/distribuidora/vales', [ValesController::class, 'valesPorDistribuidora'])->name('distribuidora.vale');

    Route::get('/distribuidora/productos', [ProductosController::class, 'listaProductos'])->name('productos');
});


// ================================
// CAJERA - role_id 5
// ================================

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

Route::get('/dashboard', function () {
    return match(auth()->user()->role_id) {
        1 => redirect()->route('gerente.dashboard'),
        2 => redirect()->route('coordinador.dashboard'),
        3 => redirect()->route('verificador.dashboard'),
        4 => redirect()->route('distribuidora.dashboard'),
        5 => redirect()->route('cajera.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';