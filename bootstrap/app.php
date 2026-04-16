<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'gerente'      => \App\Http\Middleware\SoloGerente::class,
            'coordinador'  => \App\Http\Middleware\SoloCoordinador::class,
            'verificador'  => \App\Http\Middleware\SoloVerificador::class,
            'distribuidor' => \App\Http\Middleware\SoloDistribuidor::class,
            'cajera'       => \App\Http\Middleware\SoloCajera::class,
            'recaptcha' => \App\Http\Middleware\VerifyRecaptcha::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();