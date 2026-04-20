<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        // Interceptamos si es Distribuidora (Rol 4) para validar su estado
        if ($user->role_id === 4) {
            $estado = $user->distribuidora->estado;

            if ($estado !== 'activo' && $estado !== 'moroso') {
                // Si no está activa o morosa, cerramos la sesión que Laravel acaba de abrir
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $mensaje = ($estado === 'presolicitud') 
                    ? 'Tu cuenta está en proceso de validación (Presolicitud).' 
                    : 'Tu cuenta de distribuidora se encuentra inactiva.';

                return redirect()->route('login')->withErrors([
                    'email' => $mensaje
                ]);
            }
        }

        $request->session()->regenerate();

        return match($user->role_id) {
            1 => redirect()->route('gerente.productos'),
            2 => redirect()->route('coordinador.notificaciones'),
            3 => redirect()->route('verificador.dashboard'),
            4 => redirect()->route('distribuidora.dashboard'),
            5 => redirect()->route('cajera.prevale'),
            default => redirect()->route('login'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
