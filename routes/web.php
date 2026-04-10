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

    // ── verificador
    Route::prefix('verificador')->name('verificador.')->group(function () {
    Route::get('/bandeja', [BandejaController::class, 'index'])->name('bandeja');
    Route::get('/expediente/{folio?}', [ExpedienteController::class, 'index'])->name('expediente');
    Route::get('/domicilio/{folio?}', [DomicilioController::class, 'index'])->name('domicilio');
});

// ── cajera
    Route::prefix('cajera')->name('cajera.')->group(function () {
    Route::get('/conciliacion', [ConciliacionController::class, 'index'])->name('conciliacion');
    Route::get('/prevales', [PrevalesController::class, 'index'])->name('prevales');
    Route::get('/monitor', [MonitorController::class, 'index'])->name('monitor');
});

// ── distribuidora
    Route::prefix('distribuidora')->name('distribuidora.')->group(function () {
    Route::get('/cuenta', [CuentaController::class, 'index'])->name('cuenta');
    Route::get('/puntos', [PuntosController::class, 'index'])->name('puntos');
    Route::get('/clientes', [ClientesController::class, 'index'])->name('clientes');
    Route::get('/token', [TokenController::class, 'index'])->name('token');
});

// ── Transversales (sin auth por ahora para desarrollo)
    Route::get('/notificaciones', [NotificacionesController::class, 'index'])->name('notificaciones');
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');
});

require __DIR__.'/auth.php';
