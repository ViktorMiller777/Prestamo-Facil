<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cambios de Distribuidora - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --accent: #3b82f6;
            --border: #e2e8f0;
            --warning: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body { background: var(--bg); padding: 20px; padding-top: 8rem !important; display: flex; justify-content: center; }

        .container { width: 100%; max-width: 1100px; }

        /* ── Card de acción principal ── */
        .action-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .action-header {
            padding: 24px 30px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .action-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-body { padding: 30px; }

        /* ── Input token ── */
        .token-input-wrap {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            margin-bottom: 16px;
        }

        .field-group { flex: 1; }
        .field-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            display: block;
        }

        .field-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1.1rem;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--text-main);
            outline: none;
            transition: border-color 0.2s;
        }
        .field-input:focus { border-color: var(--accent); }
        .field-input::placeholder { font-size: 0.9rem; letter-spacing: 0.02em; font-weight: 400; color: #cbd5e1; }

        .btn-validar {
            padding: 12px 24px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.2s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-validar:hover { background: #2563eb; }
        .btn-validar:disabled { background: #94a3b8; cursor: not-allowed; }

        .btn-rechazar {
            padding: 12px 20px;
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-rechazar:hover { background: #fecaca; }

        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.85rem;
            color: #1d4ed8;
            line-height: 1.6;
        }

        /* ── Tabla de historial ── */
        .custom-table { width: 100%; border-collapse: collapse; }
        .custom-table th {
            background: #f1f5f9;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 14px 24px;
            text-align: left;
        }
        .custom-table td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .estado-badge {
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.72rem;
            font-weight: 800;
            display: inline-block;
        }
        .estado-pendiente          { background: #fef3c7; color: #92400e; }
        .estado-coordinador_validado { background: #dbeafe; color: #1e40af; }
        .estado-completado         { background: #dcfce7; color: #166534; }
        .estado-cancelado          { background: #fee2e2; color: #991b1b; }

        /* ══════════════════════════════════════
           MODALES
        ══════════════════════════════════════ */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(11,31,58,0.5);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }
        .modal-backdrop.open { display: flex; }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            border: 1px solid var(--border);
            padding: 30px;
            width: 480px;
            max-width: 95vw;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-title { font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 6px; }
        .modal-sub   { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 22px; line-height: 1.5; }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row span:first-child { color: var(--text-muted); font-weight: 600; }
        .info-row span:last-child  { color: var(--text-main); font-weight: 700; }

        .info-card {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        /* Token display */
        .token-display {
            background: #0f172a;
            border-radius: 14px;
            padding: 22px;
            text-align: center;
            margin-bottom: 16px;
        }
        .token-label { font-size: 0.72rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
        .token-value { font-family: 'Courier New', monospace; font-size: 2.2rem; font-weight: 800; color: #60a5fa; letter-spacing: 0.2em; }
        .token-expira { font-size: 0.75rem; color: #64748b; margin-top: 8px; }

        .token-copy-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            padding: 11px;
            border-radius: 10px;
            background: #f1f5f9;
            border: 1px solid var(--border);
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            cursor: pointer;
            margin-bottom: 18px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background 0.2s;
        }
        .token-copy-btn:hover { background: #e2e8f0; }

        .alert-yellow {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.83rem;
            color: #92400e;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.83rem;
            color: #166534;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .field-textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            outline: none;
            resize: vertical;
            min-height: 80px;
            margin-bottom: 18px;
        }
        .field-textarea:focus { border-color: var(--danger); }

        .modal-footer { display: flex; gap: 10px; justify-content: flex-end; }
        .btn-modal-cancel  { padding: 10px 22px; border-radius: 10px; background: #fff; color: var(--text-muted); border: 1.5px solid var(--border); font-size: 0.85rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-modal-primary { padding: 10px 22px; border-radius: 10px; background: var(--accent); color: #fff; border: none; font-size: 0.85rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-modal-primary:hover { background: #2563eb; }
        .btn-modal-danger  { padding: 10px 22px; border-radius: 10px; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; font-size: 0.85rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-modal-danger:hover { background: #fecaca; }

        .empty-state { padding: 50px; text-align: center; color: var(--text-muted); }
    </style>
</head>
<body>
    <x-header-bar />

    <div class="container">

        {{-- ══════════════════════════════════════════════
             PANEL PRINCIPAL: Ingresar Token A
        ══════════════════════════════════════════════ --}}
        <div class="action-card">
            <div class="action-header">
                <h2>
                    <i data-lucide="arrow-right-left"></i>
                    Validar solicitud de cambio
                </h2>
            </div>
            <div class="action-body">
                <div class="token-input-wrap">
                    <div class="field-group">
                        <label class="field-label">Token A (recibido de la distribuidora origen)</label>
                        <input type="text"
                               id="input-token-origen"
                               class="field-input"
                               placeholder="Ingresa el código de 8 caracteres"
                               maxlength="8"
                               autocomplete="off">
                    </div>
                    <button class="btn-validar" onclick="validarTokenOrigen()">
                        <i data-lucide="check-circle" style="width:16px;"></i>
                        Validar
                    </button>
                    <button class="btn-rechazar" onclick="abrirModalRechazar()">
                        <i data-lucide="x-circle" style="width:16px;"></i>
                        Rechazar
                    </button>
                </div>

                <div class="alert-info">
                    <strong>¿Cómo funciona?</strong> La distribuidora origen generó un <strong>Token A</strong> al solicitar el cambio y te lo entregó manualmente.
                    Ingrésalo aquí para validar el proceso. Si es correcto, el sistema generará un <strong>Token B</strong> que deberás pasar a la distribuidora destino.
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════
             HISTORIAL DE CAMBIOS
        ══════════════════════════════════════════════ --}}
        <div class="action-card">
            <div class="action-header">
                <h2><i data-lucide="history"></i> Historial de cambios</h2>
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Estado</th>
                        <th>Fecha solicitud</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cambios as $cambio)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-main);">
                            {{ $cambio->cliente->persona->nombre }}
                            {{ $cambio->cliente->persona->apellido }}
                        </td>
                        <td style="font-size: 0.88rem; color: var(--text-muted);">
                            {{ $cambio->distribuidoraOrigen->usuario->persona->nombre }}
                            {{ $cambio->distribuidoraOrigen->usuario->persona->apellido }}
                        </td>
                        <td style="font-size: 0.88rem; color: var(--text-muted);">
                            {{ $cambio->distribuidoraDestino->usuario->persona->nombre }}
                            {{ $cambio->distribuidoraDestino->usuario->persona->apellido }}
                        </td>
                        <td>
                            <span class="estado-badge estado-{{ $cambio->estado }}">
                                {{ ucfirst(str_replace('_', ' ', $cambio->estado)) }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">
                            {{ $cambio->fecha_solicitud->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            @if($cambio->estado === 'coordinador_validado' && $cambio->token_destino)
                                {{-- Mostrar token B si ya fue validado pero no completado --}}
    @php
    $expiraAt = optional($cambio->token_destino_expira_at)->format('d/m/Y H:i') ?? '';
@endphp

<button class="btn-validar" style="font-size:0.78rem; padding: 6px 14px;"
    onclick="mostrarTokenB(
        '{{ $cambio->token_destino }}',
        '{{ $cambio->cliente->persona->nombre }} {{ $cambio->cliente->persona->apellido }}',
        '{{ $cambio->distribuidoraDestino->usuario->persona->nombre }} {{ $cambio->distribuidoraDestino->usuario->persona->apellido }}',
        '{{ $expiraAt }}'
    )">
    <i data-lucide="key" style="width:13px;"></i>
    Ver Token B
</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i data-lucide="inbox" style="width:40px; height:40px; margin-bottom:10px;"></i>
                                <p>No hay solicitudes de cambio registradas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MODAL: Token B generado (después de validar Token A)
    ══════════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="modal-token-b">
        <div class="modal-box">
            <div class="modal-title">✅ Token validado — Token B generado</div>
            <div class="modal-sub">Comparte este nuevo código con la distribuidora destino. Tiene vigencia de 24 horas.</div>

            <div class="info-card">
                <div class="info-row">
                    <span>Cliente</span>
                    <span id="tb-cliente">—</span>
                </div>
                <div class="info-row">
                    <span>Distribuidora destino</span>
                    <span id="tb-destino">—</span>
                </div>
            </div>

            <div class="token-display">
                <div class="token-label">Token B — para distribuidora destino</div>
                <div class="token-value" id="tb-valor">--------</div>
                <div class="token-expira" id="tb-expira">Válido por 24 horas</div>
            </div>

            <button class="token-copy-btn" onclick="copiarTokenB()">
                <i data-lucide="copy" style="width:15px;"></i>
                <span id="tb-copy-label">Copiar Token B</span>
            </button>

            <div class="alert-success">
                Entrega este código manualmente a la distribuidora destino. Cuando lo ingresen en su panel, el cliente quedará transferido automáticamente.
            </div>

            <div class="modal-footer">
                <button class="btn-modal-primary" onclick="cerrarTodo()" style="width:100%;">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MODAL: Rechazar cambio
    ══════════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="modal-rechazar">
        <div class="modal-box">
            <div class="modal-title">Rechazar solicitud</div>
            <div class="modal-sub">Ingresa el Token A de la solicitud a rechazar y, opcionalmente, el motivo.</div>

            <label class="field-label">Token A a rechazar</label>
            <input type="text"
                   id="input-token-rechazar"
                   class="field-input"
                   placeholder="Código de 8 caracteres"
                   maxlength="8"
                   style="margin-bottom: 16px;">

            <label class="field-label">Motivo (opcional)</label>
            <textarea class="field-textarea"
                      id="motivo-rechazo"
                      placeholder="Explica brevemente el motivo del rechazo..."></textarea>

            <div class="modal-footer">
                <button class="btn-modal-cancel" onclick="cerrarTodo()">Cancelar</button>
                <button class="btn-modal-danger" onclick="rechazarCambio()">
                    Confirmar rechazo
                </button>
            </div>
        </div>
    </div>

    <script>
        var csrfToken   = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var tokenBActual = '';

        /* ── Validar Token A ── */
        function validarTokenOrigen() {
            var token = document.getElementById('input-token-origen').value.trim().toUpperCase();
            if (token.length < 4) {
                alert('Ingresa el token completo.');
                return;
            }

            fetch('/coordinador/cambios/validar-token-origen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ token_origen: token }),
            })
            .then(function(res) {
                if (!res.ok) return res.json().then(function(d) { throw d; });
                return res.json();
            })
            .then(function(data) {
                tokenBActual = data.token_destino;

                document.getElementById('tb-cliente').textContent = data.cliente;
                document.getElementById('tb-destino').textContent = data.destino;
                document.getElementById('tb-valor').textContent   = data.token_destino;
                document.getElementById('tb-expira').textContent  = 'Expira en 24 horas';
                document.getElementById('tb-copy-label').textContent = 'Copiar Token B';
                document.getElementById('input-token-origen').value = '';

                document.getElementById('modal-token-b').classList.add('open');
                lucide.createIcons();
            })
            .catch(function(err) {
                alert(err.mensaje ?? 'Token inválido o ya utilizado. Intenta de nuevo.');
            });
        }

        /* ── Mostrar Token B desde historial ── */
        function mostrarTokenB(token, cliente, destino, expira) {
            tokenBActual = token;
            document.getElementById('tb-cliente').textContent = cliente;
            document.getElementById('tb-destino').textContent = destino;
            document.getElementById('tb-valor').textContent   = token;
            document.getElementById('tb-expira').textContent  = 'Expira: ' + expira;
            document.getElementById('modal-token-b').classList.add('open');
            lucide.createIcons();
        }

        /* ── Copiar Token B ── */
        function copiarTokenB() {
            navigator.clipboard.writeText(tokenBActual).then(function() {
                var label = document.getElementById('tb-copy-label');
                label.textContent = '¡Copiado!';
                setTimeout(function() { label.textContent = 'Copiar Token B'; }, 2000);
            });
        }

        /* ── Abrir modal rechazar ── */
        function abrirModalRechazar() {
            var token = document.getElementById('input-token-origen').value.trim();
            document.getElementById('input-token-rechazar').value = token;
            document.getElementById('modal-rechazar').classList.add('open');
            lucide.createIcons();
        }

        /* ── Rechazar cambio ── */
        function rechazarCambio() {
            var token  = document.getElementById('input-token-rechazar').value.trim().toUpperCase();
            var motivo = document.getElementById('motivo-rechazo').value.trim();

            if (token.length < 4) {
                alert('Ingresa el token de la solicitud a rechazar.');
                return;
            }

            fetch('/coordinador/cambios/rechazar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ token_origen: token, motivo: motivo }),
            })
            .then(function(res) {
                if (!res.ok) return res.json().then(function(d) { throw d; });
                return res.json();
            })
            .then(function(data) {
                cerrarTodo();
                alert('Solicitud rechazada correctamente.');
                location.reload();
            })
            .catch(function(err) {
                alert(err.mensaje ?? 'No se pudo rechazar. Verifica el token.');
            });
        }

        /* ── Cerrar todos los modales ── */
        function cerrarTodo() {
            document.getElementById('modal-token-b').classList.remove('open');
            document.getElementById('modal-rechazar').classList.remove('open');
        }

        /* Cerrar al click en backdrop */
        ['modal-token-b', 'modal-rechazar'].forEach(function(id) {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) cerrarTodo();
            });
        });

        lucide.createIcons();
    </script>
</body>
</html>