<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
	// Lógica de VPN para base de datos
        $remoteIp = Request::ip();

        // Obtener la configuración actual
        $config = Config::get('database.connections.mysql');

        if (strpos($remoteIp, '10.200.0.') === 0) {
            // VPN: Usar MASTER1 para todo
            $config['host'] = env('MASTER1_IP');
            $config['read'] = [];
            $config['write'] = [];
        } else {
            // No VPN: Usar read/write separados
            $config['host'] = '127.0.0.1';
            $config['read'] = ['host' => [env('DB_READ_HOST')]];
            $config['write'] = ['host' => [env('DB_WRITE_HOST')]];
        }
        // Aplicar la configuración
        Config::set('database.connections.mysql', $config);

        // Forzar reconexión
        DB::purge('mysql');
    }
}
