<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withMiddleware(function (Middleware $middleware): void {
//        $middleware->append(\App\Http\Middleware\SetDatabaseConnection::class);
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
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (QueryException  $e){
            if($e->getCode() === 2002 || str_contains($e->getMessage(), 'Connection refused ')) {
                Log::emergency("La base de datos esta caida.");
                return response()->view('errores.db-fail',[],503);
            }
        });
    })->create();
