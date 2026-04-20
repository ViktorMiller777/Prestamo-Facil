<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        // En v2 el campo siempre es 'g-recaptcha-response'
        $token = $request->input('g-recaptcha-response');

        if (!$token) {
            return back()
                ->withErrors(['recaptcha' => 'Por favor, marca la casilla de "No soy un robot".'])
                ->withInput($request->except('password'));
        }

        // Verificar el token con la API de Google
        $respuesta = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => config('services.recaptcha.secret_key'),
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        $datos = $respuesta->json();

        // IMPORTANTE: En v2 solo validamos 'success'
        // Se eliminan las validaciones de 'score' y 'action' porque no existen en v2
        if (!($datos['success'] ?? false)) {
            return back()
                ->withErrors(['recaptcha' => 'Verificación de seguridad fallida. Intenta de nuevo.'])
                ->withInput($request->except('password'));
        }

        return $next($request);
    }
}