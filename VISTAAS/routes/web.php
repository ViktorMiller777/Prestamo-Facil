<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Verificador\BandejaController;
use App\Http\Controllers\Verificador\ExpedienteController;
use App\Http\Controllers\Verificador\DomicilioController;
use App\Http\Controllers\Cajera\ConciliacionController;
use App\Http\Controllers\Cajera\PrevalesController;    
use App\Http\Controllers\Cajera\MonitorController;
use App\Http\Controllers\Distribuidora\CuentaController;      
use App\Http\Controllers\Distribuidora\PuntosController; 
use App\Http\Controllers\Distribuidora\ClientesController;  
use App\Http\Controllers\Distribuidora\TokenController;  


// ── ruta raizzzz
Route::get('/', function () {
    return view('welcome');
});

// ── dashb
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ── perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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
    Route::get('/notificaciones', [NotificacionesController::class, 'index'])->name('notificaciones');
    Route::get('/perfil', [PerfilController::class, 'index'])->name('perfil');

});

// ── auth (siempre va al final :DD)
require __DIR__.'/auth.php';