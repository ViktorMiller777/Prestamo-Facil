<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SetDatabaseConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('MIDDLEWARE DB ejecutado', [
            'ip' => $request->ip(),
            'host' => gethostname()
        ]);

        $ip = $request->ip();
        $host = gethostname();

        $isVpn = str_starts_with($ip, '10.200.0.');
        $isLB = $ip !== '10.200.0.3';
        $isApp3 = str_contains($host, 'app3');

        $current = Config::get('database.default');

        if ($isVpn && $isApp3 && $isLB) {
            $new = 'mysql_vpnApp3';
        } else {
            $new = 'mysql_normal';
        }

        if ($current !== $new) {
            Config::set('database.default', $new);
            //DB::purge($new);

            Log::info('DB SWITCH', [
                'from' => $current,
                'to' => $new
            ]);
        }

        return $next($request);
    }
}