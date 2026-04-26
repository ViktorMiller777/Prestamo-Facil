<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Completar Cambio - Préstamo Fácil</title>
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
            --success: #10b981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body { background: var(--bg); padding: 20px; padding-top: 8rem !important; display: flex; justify-content: center; }

        .container { width: 100%; max-width: 900px; }

        .page-card {
            background: var(--white);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .page-header {
            padding: 28px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .page-header-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: #dbeafe;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            flex-shrink: 0;
        }

        .page-header h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-main);
        }
        .page-header p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        .page-body { padding: 32px; }

        /* ── Step indicator ── */
        .steps {
            display: flex;
            align-items: center;
            margin-bottom: 32px;
            gap: 0;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-muted);
        }
        .step-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f1f5f9;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            font-weight: 800;
        }
        .step.done .step-num { background: #dcfce7; border-color: #86efac; color: #166534; }
        .step.active .step-num { background: #dbeafe; border-color: #93c5fd; color: #1d4ed8; }
        .step.active { color: var(--text-main); }
        .step-line { flex: 1; height: 2px; background: var(--border); margin: 0 12px; }
        .step-line.done { background: #86efac; }

        /* ── Input area ── */
        .field-label {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
            display: block;
        }

        .token-input-row { display: flex; gap: 12px; align-items: flex-end; }

        .token-input {
            flex: 1;
            padding: 16px 20px;
            border: 2px solid var(--border);
            border-radius: 14px;
            font-size: 1.4rem;
            font-family: 'Courier New', monospace;
            font-weight: 800;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--text-main);
            outline: none;
            text-align: center;
            transition: border-color 0.2s;
        }
        .token-input:focus { border-color: var(--success); }
        .token-input::placeholder { font-size: 0.95rem; letter-spacing: 0.05em; font-weight: 400; color: #cbd5e1; }

        .btn-completar {
            padding: 16px 28px;
            background: var(--success);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 800;
            cursor: pointer;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-completar:hover { background: #059669; }

        .alert-blue {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.85rem;
            color: #1d4ed8;
            line-height: 1.6;
            margin-top: 20px;
        }

        /* ── Resultado ── */
        .resultado-card {
            display: none;
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            margin-top: 24px;
        }
        .resultado-card.visible { display: block; }

        .resultado-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #dcfce7;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #16a34a;
        }

        .resultado-title { font-size: 1.25rem; font-weight: 800; color: #14532d; margin-bottom: 8px; }
        .resultado-sub   { font-size: 0.9rem; color: #166534; }

        /* ── Historial de transferencias recibidas ── */
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

        .empty-state { padding: 50px; text-align: center; color: var(--text-muted); }

        /* ── Toast ── */
        #toast {
            position: fixed;
            top: 30px;
            right: 30px;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            color: white;
            z-index: 99999;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 380px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        #toast.toast-success { background: #16a34a; }
        #toast.toast-error   { background: #dc2626; }
        #toast.toast-warning { background: #d97706; }
    </style>
</head>
<body>
    <x-header-bar />

    <div class="container">

        <div class="page-card">
            <div class="page-header">
                <div class="page-header-icon">
                    <i data-lucide="user-check" style="width:22px; height:22px;"></i>
                </div>
                <div>
                    <h2>Recibir cliente por cambio de distribuidora</h2>
                    <p>Ingresa el Token B que te proporcionó el coordinador para completar la transferencia.</p>
                </div>
            </div>

            <div class="page-body">

                {{-- Pasos del proceso (referencia visual) --}}
                <div class="steps">
                    <div class="step done">
                        <div class="step-num"><i data-lucide="check" style="width:13px;"></i></div>
                        <span>Origen solicitó</span>
                    </div>
                    <div class="step-line done"></div>
                    <div class="step done">
                        <div class="step-num"><i data-lucide="check" style="width:13px;"></i></div>
                        <span>Coordinador validó</span>
                    </div>
                    <div class="step-line done"></div>
                    <div class="step active">
                        <div class="step-num">3</div>
                        <span>Tú completas</span>
                    </div>
                </div>

                <label class="field-label">Token B recibido del coordinador</label>
                <div class="token-input-row">
                    <input type="text"
                           id="input-token-destino"
                           class="token-input"
                           placeholder="Ej: AB3XY9PQ"
                           maxlength="8"
                           autocomplete="off">
                    <button class="btn-completar" onclick="completarCambio()">
                        <i data-lucide="check-circle-2" style="width:18px;"></i>
                        Completar transferencia
                    </button>
                </div>

                <div class="alert-blue">
                    Al ingresar el Token B válido, el cliente quedará asignado automáticamente a tu distribuidora.
                </div>

                {{-- Resultado de éxito --}}
                <div class="resultado-card" id="resultado-exito">
                    <div class="resultado-icon">
                        <i data-lucide="check-circle-2" style="width:30px; height:30px;"></i>
                    </div>
                    <div class="resultado-title" id="resultado-titulo">¡Cliente transferido exitosamente!</div>
                    <div class="resultado-sub" id="resultado-sub">El cliente ya forma parte de tu cartera.</div>
                </div>
            </div>
        </div>

        <div class="page-card">
            <div class="page-header">
                <div class="page-header-icon" style="background: #f3f4f6; color: #64748b;">
                    <i data-lucide="history" style="width:20px; height:20px;"></i>
                </div>
                <div>
                    <h2>Transferencias recibidas</h2>
                    <p>Clientes que han llegado a tu distribuidora por proceso de cambio.</p>
                </div>
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Distribuidora origen</th>
                        <th>Fecha completado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transferenciasRecibidas as $cambio)
                    <tr>
                        <td style="font-weight: 700; color: var(--text-main);">
                            {{ $cambio->cliente->persona->nombre }}
                            {{ $cambio->cliente->persona->apellido }}
                        </td>
                        <td style="font-size: 0.88rem; color: var(--text-muted);">
                            {{ $cambio->distribuidoraOrigen->usuario->persona->nombre }}
                            {{ $cambio->distribuidoraOrigen->usuario->persona->apellido }}
                        </td>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">
                            {{ optional($cambio->fecha_completado)->format('d/m/Y H:i') ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <i data-lucide="inbox" style="width:36px; height:36px; margin-bottom:10px;"></i>
                                <p>Aún no has recibido clientes por cambio de distribuidora.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Toast de notificación --}}
    <div id="toast"></div>

    <script>
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function completarCambio() {
            var token = document.getElementById('input-token-destino').value.trim().toUpperCase();

            if (token.length < 4) {
                mostrarToast('⚠️ Ingresa el Token B completo (mínimo 4 caracteres).', 'warning');
                return;
            }

            // Bloquear botón mientras procesa
            const btn = document.querySelector('.btn-completar');
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader-2" style="width:18px;"></i> Procesando...';
            lucide.createIcons();

            fetch('/distribuidora/cambios/completar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ token_destino: token }),
            })
            .then(function(res) {
                if (!res.ok) return res.json().then(function(d) { throw d; });
                return res.json();
            })
            .then(function(data) {
                document.getElementById('input-token-destino').value = '';
                document.getElementById('resultado-titulo').textContent =
                    '¡' + data.cliente + ' transferido exitosamente!';
                document.getElementById('resultado-sub').textContent =
                    'El cliente ya forma parte de tu cartera de clientes.';
                document.getElementById('resultado-exito').classList.add('visible');
                lucide.createIcons();

                mostrarToast('✅ ¡Cliente transferido exitosamente!', 'success');

                // Recargar historial tras 3 segundos
                setTimeout(function() { location.reload(); }, 3000);
            })
            .catch(function(err) {
                const mensaje = err.mensaje ?? 'Token inválido, expirado o incorrecto para esta distribuidora.';
                mostrarToast('❌ ' + mensaje, 'error');

                // Restaurar botón
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check-circle-2" style="width:18px;"></i> Completar transferencia';
                lucide.createIcons();
            });
        }

        function mostrarToast(mensaje, tipo = 'success') {
            const toast = document.getElementById('toast');

            // Limpiar clases previas
            toast.className = '';
            toast.textContent = mensaje;

            if (tipo === 'success') toast.classList.add('toast-success');
            else if (tipo === 'error') toast.classList.add('toast-error');
            else if (tipo === 'warning') toast.classList.add('toast-warning');

            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';

            clearTimeout(toast._timeout);
            toast._timeout = setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
            }, 4000);
        }

        lucide.createIcons();
    </script>
</body>
</html>