<?php
use App\Jobs\ProcesarCorteQuincenal;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::call(function () {
    // 1. Obtenemos los días de corte de tu tabla de configuraciones
    $diaCorte1 = (int) Configuracion::obtener('dia_corte_1'); // Ej: 15
    $diaCorte2 = (int) Configuracion::obtener('dia_corte_2'); // Ej: 30 o 31
    
    $hoy = Carbon::now()->day;

    // 2. Verificamos si hoy es uno de los días de corte
    // También validamos si es el último día del mes en caso de que dia_corte_2 sea 31
    if ($hoy == $diaCorte1 || $hoy == $diaCorte2 || (Carbon::now()->isLastOfMonth() && $diaCorte2 >= 28)) {
        
        // Ejecutamos el Job que acabamos de crear
        ProcesarCorteQuincenal::dispatch();
    }
})->dailyAt('00:00'); // Se revisa una vez al día a medianoche

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
