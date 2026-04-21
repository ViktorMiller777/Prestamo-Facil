<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clientes - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg-color: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --accent: #3b82f6;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body { background-color: var(--bg-color); padding: 20px; padding-top: 8rem !important; display: flex; justify-content: center; }

        .container { width: 100%; max-width: 1200px; }

        .box-2 { background: var(--white); border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid var(--border); overflow: hidden; }

        .header-section { padding: 25px 30px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }

        .header-section h2 { font-size: 1.5rem; font-weight: 800; color: var(--text-main); display: flex; align-items: center; gap: 12px; }

        .custom-table { width: 100%; border-collapse: collapse; }

        .custom-table th { background-color: #f1f5f9; color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; font-weight: 700; padding: 15px 30px; text-align: left; }

        .custom-table td { padding: 20px 30px; border-bottom: 1px solid var(--border); vertical-align: middle; }

        .client-name { font-weight: 700; font-size: 1rem; color: var(--text-main); display: block; }

        .badge { padding: 4px 10px; border-radius: 8px; font-size: 0.7rem; font-weight: 800; display: inline-block; margin-top: 4px; }
        .badge-m { background: #dcfce7; color: #15803d; }
        .badge-f { background: #fce7f3; color: #9d174d; }

        .id-text { font-family: 'Courier New', monospace; font-size: 0.85rem; color: var(--text-muted); display: block; }

        .btn-doc { display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 10px; background: #f1f5f9; color: var(--text-muted); text-decoration: none; border: 1px solid var(--border); transition: all 0.2s; }
        .btn-doc:hover { background: var(--accent); color: white; transform: translateY(-2px); }

        /* ── Botón cambio distribuidora ── */
        .btn-cambio { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 10px; background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
        .btn-cambio:hover { background: #dbeafe; transform: translateY(-1px); }
        .btn-cambio-pendiente { background: #fef3c7; color: #b45309; border-color: #fde68a; cursor: default; }

        .empty-state { padding: 60px; text-align: center; color: var(--text-muted); }

        /* ══════════════════════════════════════
           MODAL BASE
        ══════════════════════════════════════ */
        .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(11,31,58,0.5); z-index: 999; align-items: center; justify-content: center; }
        .modal-backdrop.open { display: flex; }
        .modal-box { background: #fff; border-radius: 20px; border: 1px solid var(--border); padding: 28px; width: 460px; max-width: 95vw; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }

        .modal-title { font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 6px; }
        .modal-sub   { font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5; }

        .modal-cliente-box { background: #f8fafc; border: 1px solid var(--border); border-radius: 12px; padding: 14px 16px; margin-bottom: 18px; }
        .modal-cliente-nombre { font-size: 1rem; font-weight: 700; color: var(--text-main); }
        .modal-cliente-sub    { font-size: 0.82rem; color: var(--text-muted); margin-top: 3px; }

        .field-label { font-size: 0.82rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; display: block; }
        .field-select { width: 100%; padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 0.9rem; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-main); outline: none; margin-bottom: 16px; }
        .field-select:focus { border-color: var(--accent); }

        .modal-alert-blue   { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 11px 14px; font-size: 0.82rem; color: #1d4ed8; line-height: 1.5; margin-bottom: 18px; }
        .modal-alert-yellow { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 11px 14px; font-size: 0.82rem; color: #92400e; line-height: 1.5; margin-bottom: 18px; }

        /* Token generado */
        .token-display { background: #0f172a; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 18px; }
        .token-label   { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .token-value   { font-family: 'Courier New', monospace; font-size: 2rem; font-weight: 800; color: #60a5fa; letter-spacing: 0.15em; }
        .token-expira  { font-size: 0.75rem; color: #64748b; margin-top: 6px; }

        .token-copy-btn { display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 10px; border-radius: 10px; background: #f1f5f9; border: 1px solid var(--border); font-size: 0.85rem; font-weight: 600; color: var(--text-main); cursor: pointer; transition: background 0.2s; margin-bottom: 16px; }
        .token-copy-btn:hover { background: #e2e8f0; }

        /* Footer botones */
        .modal-footer { display: flex; gap: 10px; justify-content: flex-end; }
        .btn-modal-cancel  { padding: 9px 20px; border-radius: 10px; background: #fff; color: var(--text-muted); border: 1.5px solid var(--border); font-size: 0.85rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
        .btn-modal-primary { padding: 9px 20px; border-radius: 10px; background: var(--accent); color: #fff; border: none; font-size: 0.85rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; transition: background 0.2s; }
        .btn-modal-primary:hover { background: #2563eb; }
        .btn-modal-danger  { padding: 9px 20px; border-radius: 10px; background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; font-size: 0.85rem; font-weight: 700; cursor: pointer; font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body>
    <x-header-bar />

    <div class="container">
        <div class="box-2">
            <div class="header-section">
                <h2><i data-lucide="users"></i> Cartera de Clientes</h2>
                <span class="badge" style="background: #f1f5f9; color: var(--text-muted);">
                    {{ count($clientes) }} Registrados
                </span>
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Identificación</th>
                        <th>Contacto</th>
                        <th>Documentos</th>
                        <th>Cambio distrib.</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    @php
                        // Verificar si tiene un cambio activo
                        $cambioActivo = $cliente->cambioActivo ?? null;
                        // TODO: cargar esta relación desde el controlador con:
                        // ->with(['cambioActivo' => fn($q) => $q->whereIn('estado',['pendiente','coordinador_validado'])])
                    @endphp
                    <tr>
                        <td>
                            <span class="client-name">{{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}</span>
                            <span class="badge {{ $cliente->persona->sexo == 'M' ? 'badge-m' : 'badge-f' }}">
                                {{ $cliente->persona->sexo == 'M' ? 'MASCULINO' : 'FEMENINO' }}
                            </span>
                        </td>
                        <td>
                            <span class="id-text"><strong>CURP:</strong> {{ $cliente->persona->CURP }}</span>
                            <span class="id-text"><strong>RFC:</strong> {{ $cliente->persona->RFC }}</span>
                        </td>
                        <td>
                            <div style="font-size: 0.9rem; color: var(--text-main); font-weight: 500; display: flex; align-items: center; gap: 8px;">
                                <i data-lucide="phone" style="width: 14px; color: var(--accent);"></i>
                                {{ $cliente->persona->telefono_personal }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                @php
                                    $ine = $cliente->documentos->where('tipo', 'INE')->first();
                                    $comprobante = $cliente->documentos->where('tipo', 'Comprobante Domicilio')->first();
                                @endphp

                                @if($ine)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('spaces')->temporaryUrl($ine->archivo_path, now()->addMinutes(10)) }}"
                                       target="_blank" class="btn-doc" title="Ver INE">
                                        <i data-lucide="contact-2"></i>
                                    </a>
                                @else
                                    <div class="btn-doc" title="INE No disponible" style="opacity: 0.4; cursor: not-allowed;">
                                        <i data-lucide="contact-2"></i>
                                    </div>
                                @endif

                                @if($comprobante)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('spaces')->temporaryUrl($comprobante->archivo_path, now()->addMinutes(10)) }}"
                                       target="_blank" class="btn-doc" title="Ver Comprobante">
                                        <i data-lucide="home"></i>
                                    </a>
                                @else
                                    <div class="btn-doc" title="Comprobante No disponible" style="opacity: 0.4; cursor: not-allowed;">
                                        <i data-lucide="home"></i>
                                    </div>
                                @endif
                            </div>
                        </td>

                        {{-- ── COLUMNA CAMBIO DE DISTRIBUIDORA ── --}}
                        <td>
                            @if($cambioActivo)
                                {{-- Ya tiene un proceso activo — solo mostrar estado --}}
                                <span class="btn-cambio btn-cambio-pendiente">
                                    <i data-lucide="clock" style="width:14px;"></i>
                                    Cambio pendiente
                                </span>
                            @else
                                <button class="btn-cambio"
                                    onclick="abrirModalConfirmar(
                                        {{ $cliente->id }},
                                        '{{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}'
                                    )">
                                    <i data-lucide="arrow-right-left" style="width:14px;"></i>
                                    Solicitar cambio
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($clientes) == 0)
                <div class="empty-state">
                    <i data-lucide="search-x" style="width: 48px; height: 48px; margin-bottom: 10px;"></i>
                    <p>No hay clientes registrados en esta distribuidora.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MODAL PASO 1 — Confirmar intención de cambio
    ══════════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="modal-confirmar">
        <div class="modal-box">
            <div class="modal-title">¿Solicitar cambio de distribuidora?</div>
            <div class="modal-sub">Esta acción iniciará un proceso que requiere la aprobación del coordinador. El cliente no se moverá hasta que se complete el flujo.</div>

            <div class="modal-cliente-box">
                <div class="modal-cliente-nombre" id="conf-nombre"></div>
                <div class="modal-cliente-sub">Cliente a transferir</div>
            </div>

            <label class="field-label">Distribuidora destino</label>
            <select class="field-select" id="sel-destino">
                <option value="">Selecciona la distribuidora destino...</option>
                @foreach($distribuidoras ?? [] as $d)
                    <option value="{{ $d->id }}">
                        {{ $d->usuario->persona->nombre }} {{ $d->usuario->persona->apellido }}
                    </option>
                @endforeach
            </select>
            {{-- TODO: $distribuidoras debe pasarse desde el controlador:
                 $distribuidoras = Distribuidora::where('estado','activo')
                     ->where('id', '!=', auth()->user()->distribuidora->id)
                     ->with('usuario.persona')->get();
            --}}

            <div class="modal-alert-yellow">
                Se generará un token que deberás compartir con tu coordinador para continuar el proceso.
            </div>

            <div class="modal-footer">
                <button class="btn-modal-cancel" onclick="cerrarTodo()">Cancelar</button>
                <button class="btn-modal-primary" onclick="generarToken()">
                    Sí, solicitar cambio
                </button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════
         MODAL PASO 2 — Token generado
    ══════════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="modal-token">
        <div class="modal-box">
            <div class="modal-title">Token generado</div>
            <div class="modal-sub">Comparte este código con tu coordinador. Tiene una vigencia de 24 horas.</div>

            <div class="token-display">
                <div class="token-label">Token de autorización</div>
                <div class="token-value" id="token-valor">--------</div>
                <div class="token-expira">Válido por 24 horas</div>
            </div>

            <button class="token-copy-btn" onclick="copiarToken()">
                <i data-lucide="copy" style="width:16px;"></i>
                <span id="copy-label">Copiar token</span>
            </button>

            <div class="modal-alert-blue">
                El coordinador ingresará este token en su panel. Si lo aprueba, recibirás confirmación y el proceso continuará.
            </div>

            <div class="modal-footer">
                <button class="btn-modal-primary" onclick="cerrarTodo()" style="width:100%;">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <script>
        var csrfToken    = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var clienteActual = null;
        var tokenGenerado = '';

        /* ── Abrir modal de confirmación ── */
        function abrirModalConfirmar(clienteId, nombre) {
            clienteActual = clienteId;
            document.getElementById('conf-nombre').textContent = nombre;
            document.getElementById('sel-destino').value = '';
            document.getElementById('modal-confirmar').classList.add('open');
        }

        /* ── Cerrar todos los modales ── */
        function cerrarTodo() {
            document.getElementById('modal-confirmar').classList.remove('open');
            document.getElementById('modal-token').classList.remove('open');
            clienteActual = null;
        }

        /* ── Paso 1: solicitar cambio y obtener token ── */
        function generarToken() {
            var destinoId = document.getElementById('sel-destino').value;

            if (!destinoId) {
                alert('Selecciona la distribuidora destino antes de continuar.');
                return;
            }

            fetch('/distribuidora/clientes/' + clienteActual + '/solicitar-cambio', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ distribuidora_destino_id: destinoId })
            })
            .then(function(res) {
                if (!res.ok) return res.json().then(function(d) { throw d; });
                return res.json();
            })
            .then(function(data) {
                tokenGenerado = data.token_origen;
                document.getElementById('token-valor').textContent = tokenGenerado;
                document.getElementById('copy-label').textContent  = 'Copiar token';

                /* Pasar al modal del token */
                document.getElementById('modal-confirmar').classList.remove('open');
                document.getElementById('modal-token').classList.add('open');
            })
            .catch(function(err) {
                alert(err.mensaje ?? 'Error al solicitar el cambio. Intenta de nuevo.');
            });
        }

        /* ── Copiar token al portapapeles ── */
        function copiarToken() {
            navigator.clipboard.writeText(tokenGenerado).then(function() {
                var label = document.getElementById('copy-label');
                label.textContent = '¡Copiado!';
                setTimeout(function() { label.textContent = 'Copiar token'; }, 2000);
            });
        }

        /* Cerrar modales al click en backdrop */
        ['modal-confirmar', 'modal-token'].forEach(function(id) {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) cerrarTodo();
            });
        });

        lucide.createIcons();
    </script>
</body>
</html>