<?php

namespace App\Http\Controllers;

use App\Models\CambioDistribuidora;
use App\Models\Cliente;
use App\Models\Distribuidora;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CambioDistribuidoraController extends Controller
{
    /* ══════════════════════════════════════════════════════════════
       VISTAS
    ══════════════════════════════════════════════════════════════ */

    /**
     * Vista del coordinador — historial + panel para ingresar Token A.
     * GET /coordinador/cambios
     */
    public function vistaCoordinador()
    {
        $cambios = CambioDistribuidora::with([
                'cliente.persona',
                'distribuidoraOrigen.usuario.persona',
                'distribuidoraDestino.usuario.persona',
            ])
            ->orderByDesc('created_at')
            ->paginate(15);

return view('coordinador.cambio_cliente', compact('cambios'));
    }

    /**
     * Vista distribuidora destino — panel para ingresar Token B.
     * GET /distribuidora/cambios/completar
     */
    public function vistaCompletar()
    {
        $distribuidoraId = auth()->user()->distribuidora->id;

        // Historial de transferencias recibidas por esta distribuidora
        $transferenciasRecibidas = CambioDistribuidora::with([
                'cliente.persona',
                'distribuidoraOrigen.usuario.persona',
            ])
            ->where('distribuidora_destino_id', $distribuidoraId)
            ->where('estado', 'completado')
            ->orderByDesc('fecha_completado')
            ->get();

return view('distribuidora.aceptar_cliente', compact('transferenciasRecibidas'));
    }

    /* ══════════════════════════════════════════════════════════════
       PASO 1 — Distribuidora origen solicita el cambio
       POST /distribuidora/clientes/{cliente}/solicitar-cambio
    ══════════════════════════════════════════════════════════════ */
    public function solicitarCambio(Request $request, $clienteId)
    {
        $request->validate([
            'distribuidora_destino_id' => 'required|exists:distribuidoras,id',
        ]);

        $distribuidoraOrigen = auth()->user()->distribuidora;

        // El cliente debe pertenecer a esta distribuidora
        $cliente = Cliente::where('id', $clienteId)
            ->where('distribuidor_id', $distribuidoraOrigen->id)
            ->firstOrFail();

        // Verificar que no tenga ya un cambio activo
        $cambioActivo = CambioDistribuidora::where('cliente_id', $clienteId)
            ->whereIn('estado', ['pendiente', 'coordinador_validado'])
            ->first();

        if ($cambioActivo) {
            return response()->json([
                'res'     => false,
                'mensaje' => 'Este cliente ya tiene una solicitud de cambio activa.',
            ], 422);
        }

        // No puede cambiarse a la misma distribuidora
        if ($request->distribuidora_destino_id == $distribuidoraOrigen->id) {
            return response()->json([
                'res'     => false,
                'mensaje' => 'La distribuidora destino no puede ser la misma que la actual.',
            ], 422);
        }

        // Generar Token A — 8 caracteres alfanuméricos en mayúsculas
        $tokenOrigen = strtoupper(Str::random(8));

        $cambio = CambioDistribuidora::create([
            'cliente_id'               => $clienteId,
            'distribuidora_origen_id'  => $distribuidoraOrigen->id,
            'distribuidora_destino_id' => $request->distribuidora_destino_id,
            'token_origen'             => $tokenOrigen,
            'token_origen_usado'       => false,
            'token_origen_expira_at'   => now()->addHours(24),
            'estado'                   => 'pendiente',
        ]);

        return response()->json([
            'res'          => true,
            'mensaje'      => 'Solicitud creada. Comparte el Token A con tu coordinador.',
            'token_origen' => $tokenOrigen,
            'expira_en'    => '24 horas',
            'cambio_id'    => $cambio->id,
        ]);
    }

    /* ══════════════════════════════════════════════════════════════
       PASO 2 — Coordinador valida Token A y genera Token B
       POST /coordinador/cambios/validar-token-origen
    ══════════════════════════════════════════════════════════════ */
    public function validarTokenOrigen(Request $request)
    {
        $request->validate([
            'token_origen' => 'required|string',
        ]);

        $cambio = CambioDistribuidora::where('token_origen', strtoupper($request->token_origen))
            ->where('estado', 'pendiente')
            ->where('token_origen_usado', false)
            ->first();

        if (! $cambio) {
            return response()->json([
                'res'     => false,
                'mensaje' => 'Token inválido o ya utilizado.',
            ], 422);
        }

        if ($cambio->tokenOrigenExpirado()) {
            $cambio->update([
                'estado'              => 'cancelado',
                'motivo_cancelacion'  => 'Token origen expirado.',
            ]);
            return response()->json([
                'res'     => false,
                'mensaje' => 'El token ha expirado. La solicitud fue cancelada.',
            ], 422);
        }

        // Generar Token B
        $tokenDestino = strtoupper(Str::random(8));

        $cambio->update([
            'coordinador_id'          => auth()->id(),
            'token_origen_usado'      => true,
            'token_destino'           => $tokenDestino,
            'token_destino_usado'     => false,
            'token_destino_expira_at' => now()->addHours(24),
            'estado'                  => 'coordinador_validado',
            'fecha_validacion_coord'  => now(),
        ]);

        return response()->json([
            'res'           => true,
            'mensaje'       => 'Token A validado. Comparte el Token B con la distribuidora destino.',
            'token_destino' => $tokenDestino,
            'cliente'       => $cambio->cliente->persona->nombre . ' ' . $cambio->cliente->persona->apellido,
            'origen'        => $cambio->distribuidoraOrigen->usuario->persona->nombre . ' ' . $cambio->distribuidoraOrigen->usuario->persona->apellido,
            'destino'       => $cambio->distribuidoraDestino->usuario->persona->nombre . ' ' . $cambio->distribuidoraDestino->usuario->persona->apellido,
        ]);
    }

    /* ══════════════════════════════════════════════════════════════
       PASO 3A — Coordinador rechaza el cambio
       POST /coordinador/cambios/rechazar
    ══════════════════════════════════════════════════════════════ */
    public function rechazarCambio(Request $request)
    {
        $request->validate([
            'token_origen' => 'required|string',
            'motivo'       => 'nullable|string|max:500',
        ]);

        $cambio = CambioDistribuidora::where('token_origen', strtoupper($request->token_origen))
            ->where('estado', 'pendiente')
            ->first();

        if (! $cambio) {
            return response()->json([
                'res'     => false,
                'mensaje' => 'Token inválido o solicitud no encontrada.',
            ], 422);
        }

        $cambio->update([
            'coordinador_id'         => auth()->id(),
            'estado'                 => 'cancelado',
            'motivo_cancelacion'     => $request->motivo ?? 'Rechazado por coordinador.',
            'fecha_validacion_coord' => now(),
        ]);

        return response()->json([
            'res'     => true,
            'mensaje' => 'Solicitud rechazada correctamente.',
        ]);
    }

    /* ══════════════════════════════════════════════════════════════
       PASO 3B — Distribuidora destino ingresa Token B → cambio ejecutado
       POST /distribuidora/cambios/completar
    ══════════════════════════════════════════════════════════════ */
    public function completarCambio(Request $request)
    {
        $request->validate([
            'token_destino' => 'required|string',
        ]);

        $cambio = CambioDistribuidora::where('token_destino', strtoupper($request->token_destino))
            ->where('estado', 'coordinador_validado')
            ->where('token_destino_usado', false)
            ->first();

        if (! $cambio) {
            return response()->json([
                'res'     => false,
                'mensaje' => 'Token inválido o ya utilizado.',
            ], 422);
        }

        if ($cambio->tokenDestinoExpirado()) {
            $cambio->update([
                'estado'             => 'cancelado',
                'motivo_cancelacion' => 'Token destino expirado.',
            ]);
            return response()->json([
                'res'     => false,
                'mensaje' => 'El token ha expirado. La solicitud fue cancelada.',
            ], 422);
        }

        // Verificar que quien ingresa el token sea la distribuidora destino
        $distribuidoraActual = auth()->user()->distribuidora;

        if ($distribuidoraActual->id !== $cambio->distribuidora_destino_id) {
            return response()->json([
                'res'     => false,
                'mensaje' => 'Este token no corresponde a tu distribuidora.',
            ], 403);
        }

        // Ejecutar el cambio real en la tabla clientes
        Cliente::where('id', $cambio->cliente_id)
            ->update(['distribuidor_id' => $cambio->distribuidora_destino_id]);

        // Marcar como completado
        $cambio->update([
            'token_destino_usado' => true,
            'estado'              => 'completado',
            'fecha_completado'    => now(),
        ]);

        return response()->json([
            'res'     => true,
            'mensaje' => 'El cliente fue transferido correctamente a tu distribuidora.',
            'cliente' => $cambio->cliente->persona->nombre . ' ' . $cambio->cliente->persona->apellido,
        ]);
    }

    /* ══════════════════════════════════════════════════════════════
       EXTRA — Historial de cambios de la distribuidora actual
       GET /distribuidora/cambios/historial
    ══════════════════════════════════════════════════════════════ */
    public function historial()
    {
        $distribuidoraId = auth()->user()->distribuidora->id;

        $cambios = CambioDistribuidora::with([
                'cliente.persona',
                'distribuidoraOrigen.usuario.persona',
                'distribuidoraDestino.usuario.persona',
            ])
            ->where(function ($q) use ($distribuidoraId) {
                $q->where('distribuidora_origen_id', $distribuidoraId)
                  ->orWhere('distribuidora_destino_id', $distribuidoraId);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->json($cambios);
    }
}