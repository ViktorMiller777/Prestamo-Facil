<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\SetDatabaseConnection::class);
        $middleware->trustProxies(
                at: '*',
                headers: Request::HEADER_X_FORWARDED_FOR |
                        Request::HEADER_X_FORWARDED_HOST |
                        Request::HEADER_X_FORWARDED_PORT |
                        Request::HEADER_X_FORWARDED_PROTO
        );
        $middleware->alias([
            'gerente'      => \App\Http\Middleware\SoloGerente::class,
            'coordinador'  => \App\Http\Middleware\SoloCoordinador::class,
            'verificador'  => \App\Http\Middleware\SoloVerificador::class,
            'distribuidor' => \App\Http\Middleware\SoloDistribuidor::class,
            'cajera'       => \App\Http\Middleware\SoloCajera::class,
            'recaptcha' => \App\Http\Middleware\VerifyRecaptcha::class,

        ]);
    })
    // ->withExceptions(function (Exceptions $exceptions) {

    //     $exceptions->render(function (\Throwable $e) {

    //         Log::error("EXCEPCION: ".$e::class." | ".$e->getMessage());

    //         if (
    //             $e instanceof \PDOException ||
    //             $e instanceof \Illuminate\Database\QueryException ||
    //             $e instanceof \Illuminate\Http\Client\ConnectionException ||
    //             str_contains($e->getMessage(), 'SQLSTATE') ||
    //             str_contains($e->getMessage(), 'Connection') ||
    //             str_contains($e->getMessage(), 'refused') ||
    //             str_contains($e->getMessage(), 'gone away') ||
    //             str_contains($e->getMessage(), 'SSL')
    //         ) {
    //             return response()->view('errores.db-fail', [], 503);
    //         }
    //     });
    // }) -> create();

    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (\Throwable $e) {
            
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return null; // Laravel manejará esto normalmente
            }
            
            // NO interceptar errores de autenticación
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return null;
            }
            
            // NO interceptar errores HTTP como 401, 403
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException && 
                in_array($e->getStatusCode(), [401, 403, 404])) {
                return null;
            }
            
            Log::error("EXCEPCION: " . $e::class . " | " . $e->getMessage() . " | " . $e->getFile() . ":" . $e->getLine());
            
            // Verificar si es error de BD
            $isDatabaseError = (
                $e instanceof \PDOException ||
                $e instanceof \Illuminate\Database\QueryException ||
                $e instanceof \Illuminate\Http\Client\ConnectionException ||
                str_contains($e->getMessage(), 'SQLSTATE') ||
                str_contains($e->getMessage(), 'Connection') ||
                str_contains($e->getMessage(), 'refused') ||
                str_contains($e->getMessage(), 'gone away') ||
                str_contains($e->getMessage(), 'SSL') ||
                str_contains($e->getMessage(), 'MySQL') ||
                str_contains($e->getMessage(), 'server has gone away') ||
                str_contains($e->getMessage(), 'Lost connection') ||
                str_contains($e->getMessage(), 'try reconnecting')
            );
            
            // Solo manejar errores de BD
            if ($isDatabaseError) {
                if (!str_contains($e->getMessage(), 'sessions') && request()->isMethod('get')) {
                    return response()->view('errores.db-fail', [
                        'error_type' => 'database_connection',
                        'message' => 'Estamos teniendo problemas técnicos. Por favor, recarga la página.',
                        'auto_reload' => true
                    ], 503);
                }
                
                return response()->view('errores.db-fail', [
                    'error_type' => 'database_critical',
                    'message' => 'No pudimos procesar tu solicitud. Por favor, intenta más tarde.',
                    'suggestion' => 'Si el problema persiste, contacta a soporte.'
                ], 503);
            }
            
            // Solo errores no manejados y NO de validación/autenticación
            if (!config('app.debug') && !$e instanceof \Illuminate\Validation\ValidationException) {
                return response()->view('errores.generic', [
                    'message' => 'Ha ocurrido un error inesperado.'
                ], 500);
            }
            
            return null;
        });
    })->create();