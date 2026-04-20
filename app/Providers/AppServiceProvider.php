<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use PDO;

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
    /*public function boot()
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
    }*/

    public function boot(): void
    {
        $remoteIp = Request::ip();
        $isVpn = strpos($remoteIp, '10.200.0.') === 0;
        $appHostname = gethostname();
        
        $baseConfig = Config::get('database.connections.mysql');
        
        $sslOptions = extension_loaded('pdo_mysql') ? [
            PDO::MYSQL_ATTR_SSL_CA => env('DB_SSL_CA'),
            PDO::MYSQL_ATTR_SSL_CERT => env('DB_SSL_CERT'),
            PDO::MYSQL_ATTR_SSL_KEY => env('DB_SSL_KEY'),
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => env('DB_SSL_VERIFY_SERVER_CERT', true),
        ] : [];
        
        // CONEXIONES DESDE VPN
        if ($isVpn) {
            // APP1/APP2/APP3 desde LB: Master2 (escritura) y Slave (lectura)
            if ($remoteIp === '10.200.0.3') {
                $baseConfig['write'] = ['host' => [env('MASTER2_HOST', '10.200.0.5')]];
                $baseConfig['read'] = ['host' => [env('SLAVE_HOST', '10.200.0.6')]];
                $baseConfig['sticky'] = true;
                $baseConfig['options'] = $sslOptions;
                
                Config::set('database.connections.mysql', $baseConfig);
                
                \Log::info('APP1/APP2 VPN Mode', [
                    'write_host' => env('MASTER2_HOST'),
                    'read_host' => env('SLAVE_HOST'),
                    'ip' => $remoteIp
                ]);
            }
            // cualquier otra IP VPN y está en APP3: Todo a Master1
            else if (str_contains($appHostname, 'app3')) {
                $baseConfig['host'] = env('MASTER1_HOST', '10.200.0.4');
                $baseConfig['options'] = $sslOptions;
                
                Config::set('database.connections.mysql', $baseConfig);
                
                \Log::info('APP3 VPN Mode', [
                    'host' => env('MASTER1_HOST'),
                    'ip' => $remoteIp
                ]);
            }
            else{
                 \Log::warning('VPN IP no reconocida', [
                    'ip' => $remoteIp,
                    'app' => $appHostname
                ]);
                
                // Configuración por defecto o error
                throw new \RuntimeException('Configuración no definida para esta combinación IP/App');
            }
        } else {
            // Rechazar conexión explícitamente
            \Log::error('Intento de conexión desde IP no-VPN bloqueado', [
                'ip' => $remoteIp,
                'app' => $appHostname
            ]);
            
            throw new \RuntimeException('Acceso no permitido desde IP no-VPN');
        }
        
        // Forzar reconexión con nueva configuración
        DB::purge('mysql');
    }

}
