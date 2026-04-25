<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\DistribuidorasController;
use App\Http\Controllers\ValesController;
use App\Http\Controllers\RelacionesController;
use App\Http\Controllers\DetallesValesController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ConfiguracionesController;
use App\Http\Controllers\CambioDistribuidoraController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

// ================================
// RUTAS PÚBLICAS
// ================================
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/test-error', function () {
    throw new \Exception('Error de prueba');
});

Route::get('/test-db', function () {
    try {
        $connection = Config::get('database.default');
        $config = Config::get("database.connections.$connection");

        $hostname = DB::select('SELECT @@hostname as host, @@server_id as id, @@port as port');
        $ssl = DB::select("SHOW STATUS LIKE 'Ssl_cipher'");

        return [
            'success' => true,
            'app_hostname' => gethostname(),
            'client_ip' => request()->ip(),
            'is_vpn' => str_starts_with(request()->ip(), '10.200.0.'),
            'connection_used' => $connection,

            'database_host' => $hostname[0] ?? null,
            'ssl_cipher' => $ssl[0]->Value ?? 'No SSL',

            'config' => [
                'host' => $config['host'] ?? null,
                'read_host' => $config['read']['host'][0] ?? null,
                'write_host' => $config['write']['host'][0] ?? null,
            ]
        ];

    } catch (\Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage(),
        ];
    }
});

// ================================
// GERENTE - role_id 1
// ================================
Route::middleware(['auth', 'gerente'])->group(function () {

    Route::get('/gerente/productos', [ProductosController::class, 'listaProductos'])->name('gerente.productos');
    Route::get('/gerente/distribuidora', [DistribuidorasController::class, 'listaDistribuidoras'])->name('gerente.distribuidoras');
    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])->name('distribuidoras.store');
    Route::post('/gerente/distribuidora/{id}/estado', [DistribuidorasController::class, 'actualizarEstado'])->name('gerente.distribuidoras.estado');
    Route::get('/gerente/presolicitudes', [DistribuidorasController::class, 'distribuidorasInactivas'])->name('gerente.presolicitud');
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

    Route::get('/nueva-distribuidora', function () {
        return view('auth.register');
    })->name('distribuidoras.create');

    Route::get('/coordinador/distribuidora', [DistribuidorasController::class, 'listaDistribuidorasCoordinador'])->name('coordinador.distribuidoras');
    Route::get('/coordinador/vales', [ValesController::class, 'listaValesCoordinador'])->name('coordinador.vales');
    Route::get('/coordinador/clientes', [ClientesController::class, 'listaClientesCoordinador'])->name('coordinador.clientes');
    Route::post('/distribuidoras/store', [DistribuidorasController::class, 'crearDistribuidora'])->name('distribuidoras.store');

    // ── Cambios de distribuidora ──
    Route::get('/coordinador/cambio_cliente', [CambioDistribuidoraController::class, 'vistaCoordinador'])->name('coordinador.cambio_cliente');
    Route::post('/coordinador/cambios/validar-token-origen', [CambioDistribuidoraController::class, 'validarTokenOrigen'])->name('coordinador.cambio.validar');
    Route::post('/coordinador/cambios/rechazar', [CambioDistribuidoraController::class, 'rechazarCambio'])->name('coordinador.cambio.rechazar');
});

// ================================
// VERIFICADOR - role_id 3
// ================================
Route::middleware(['auth', 'verificador'])->group(function () {
    Route::get('/verificador/dashboard', function () {
        return view('verificador.dashboard');
    })->name('verificador.dashboard');

    Route::get('/verificador/presolicitudes', [DistribuidorasController::class, 'distribuidorasPresolicitud'])->name('verificador.presolicitud');
    Route::get('/verificador/distribuidoras', [DistribuidorasController::class, 'listaDistribuidorasVerificador'])->name('verificador.distribuidoras');
    Route::get('/verificador/distribuidora/{id}', [DistribuidorasController::class, 'detalle'])->name('verificador.detalle');
});

// ================================
// DISTRIBUIDOR - role_id 4
// ================================
Route::middleware(['auth', 'distribuidor'])->group(function () {
    Route::get('/distribuidora/dashboard', function () {
        return view('distribuidora.dashboard');
    })->name('distribuidora.dashboard');

    Route::get('/distribuidora/clientes', [ClientesController::class, 'clientesDistribuidora'])->name('distribuidora.clientes');
    Route::get('/distribuidora/vales', [ValesController::class, 'valesPorDistribuidora'])->name('distribuidora.vale');
    Route::get('/distribuidora/productos', [ProductosController::class, 'listaProductos'])->name('productos');
    Route::get('/distribuidora/relaciones', [RelacionesController::class, 'listaRelacionesAuth'])->name('relaciones');
    Route::get('/distribudora/detalle_vale/{id}', [DetallesValesController::class, 'verDetalleRelacion'])->name('detalle_vale');

    // ── Cambios de distribuidora ──
    Route::post('/distribuidora/clientes/{cliente}/solicitar-cambio', [CambioDistribuidoraController::class, 'solicitarCambio'])->name('distribuidora.cambio.solicitar');
Route::get('/distribuidora/aceptar_cliente', [CambioDistribuidoraController::class, 'vistaCompletar'])->name('distribuidora.aceptar_cliente');
    Route::post('/distribuidora/cambios/completar', [CambioDistribuidoraController::class, 'completarCambio'])->name('distribuidora.cambio.completar');
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
        1 => redirect()->route('gerente.productos'),
        2 => redirect()->route('coordinador.distribuidoras'),
        3 => redirect()->route('verificador.dashboard'),
        4 => redirect()->route('distribuidora.dashboard'),
        5 => redirect()->route('cajera.prevale'),
        default => redirect()->route('login'),
    };
})->middleware('auth')->name('dashboard');

require __DIR__.'/auth.php';
