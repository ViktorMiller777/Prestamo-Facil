<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DistribuidorasController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
