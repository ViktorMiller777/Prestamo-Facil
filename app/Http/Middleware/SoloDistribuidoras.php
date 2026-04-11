<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloDistribuidoras
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role_id == 4 ) {
            return $next($request);
        }

        return redirect('/')->with('error', 'No tienes permisos para acceder a esta sección.');
        // return $next($request);
    }
}
