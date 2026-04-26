<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SetDatabaseConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $host = gethostname();

        $isVpn = str_starts_with($ip, '10.200.0.');
        $isFromLB = ($ip === '10.200.0.3');
        $isApp3 = ($host === 'app3');

        Log::info('MIDDLEWARE DB ejecutado', [
            'ip' => $ip,
            'host' => $host,
            'isVpn' => $isVpn,
            'isFromLB' => $isFromLB,
            'isApp3' => $isApp3
        ]);

        $current = Config::get('database.default');

        // Si viene de VPN (10.200.0.x) Y NO es del balanceador (10.200.0.3) Y es app3
        if ($isVpn && !$isFromLB && $isApp3) {
            $new = 'mysql_vpnApp3';
        } else {
            $new = 'mysql_normal';
        }

        if ($current !== $new) {
            Config::set('database.default', $new);
            
            Log::info('DB SWITCH', [
                'from' => $current,
                'to' => $new,
                'ip' => $ip,
                'host' => $host
            ]);
        }

        return $next($request);
    }
}