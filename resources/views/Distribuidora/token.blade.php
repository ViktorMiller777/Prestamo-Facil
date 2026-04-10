@extends('layouts.app')

@section('title', 'Generador de tokens')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    .topbar { padding: 24px 32px 16px 32px; }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-sub   { font-size: .82rem; color: #64748B; margin-top: 3px; }

    .info-banner {
        margin: 0 32px 16px 32px;
        background: #EFF6FF;
        border: 1.5px solid #BFDBFE;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: .88rem;
        color: #1D4ED8;
        line-height: 1.6;
    }

    .main-card {
        margin: 0 32px 16px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }
    .main-card-header { padding: 18px 22px; border-bottom: 1px solid #F1F5F9; }
    .main-card-title  { font-size: .95rem; font-weight: 700; color: #0B1F3A; margin-bottom: 3px; }
    .main-card-sub    { font-size: .82rem; color: #94A3B8; }

    .card-body { padding: 22px; display: flex; flex-direction: column; gap: 16px; }

    .field-label { font-size: .78rem; color: #64748B; margin-bottom: 5px; display: block; font-weight: 500; }
    .field-select {
        width: 100%;
        padding: 12px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .92rem;
        font-family: 'DM Sans', sans-serif;
        color: #fff;
        background: #0B1F3A;
        outline: none;
        cursor: pointer;
    }

    .cliente-preview {
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 9px;
        padding: 12px 16px;
        display: none;
        align-items: center;
        justify-content: space-between;
    }
    .cliente-preview.show { display: flex; }
    .cliente-av {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: #DBEAFE;
        display: flex; align-items: center; justify-content: center;
        font-size: .88rem; font-weight: 700; color: #1D4ED8;
        flex-shrink: 0;
    }
    .cliente-name { font-size: .92rem; font-weight: 700; color: #0B1F3A; }
    .cliente-sub  { font-size: .78rem; color: #94A3B8; margin-top: 2px; }

    .docs-row { display: flex; gap: 10px; }
    .doc-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: .75rem; font-weight: 700;
    }
    .doc-ok      { background: #DCFCE7; color: #15803D; }
    .doc-missing { background: #FEE2E2; color: #B91C1C; }

    .badge-elegible { background: #DCFCE7; color: #15803D; padding: 3px 10px; border-radius: 99px; font-size: .75rem; font-weight: 700; }

    .btn-generar {
        width: 100%;
        padding: 12px;
        background: #2563EB;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: .92rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-generar:hover { background: #1D4ED8; }

    .token-box {
        background: #0B1F3A;
        border-radius: 10px;
        padding: 24px;
        text-align: center;
        display: none;
    }
    .token-box.show { display: block; }
    .token-label { font-size: .70rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: .08em; margin-bottom: 10px; }
    .token-val   { font-family: 'DM Mono', monospace; font-size: 1.8rem; font-weight: 700; color: #60A5FA; letter-spacing: .15em; }
    .token-exp   { font-size: .78rem; color: rgba(255,255,255,0.3); margin-top: 8px; }
    .btn-copiar  {
        margin-top: 14px;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 7px;
        padding: 7px 18px;
        font-size: .82rem;
        color: rgba(255,255,255,0.7);
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-copiar:hover { background: rgba(255,255,255,0.14); }

    .alert-warn {
        background: #FEF3C7;
        border: 1.5px solid #FDE68A;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: .82rem;
        color: #92400E;
        line-height: 1.5;
        display: none;
    }
    .alert-warn.show { display: block; }

    .historial-card {
        margin: 0 32px 32px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }
    .historial-header { padding: 16px 22px; border-bottom: 1px solid #F1F5F9; }
    .historial-title  { font-size: .95rem; font-weight: 700; color: #0B1F3A; }
    .hist-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 22px;
        border-bottom: 1px solid #F8FAFC;
    }
    .hist-item:last-child { border-bottom: none; }
    .hist-nombre { font-size: .88rem; font-weight: 700; color: #0B1F3A; }
    .hist-token  { font-family: 'DM Mono', monospace; font-size: .78rem; color: #94A3B8; margin-top: 2px; }
    .hist-right  { text-align: right; }
    .hist-fecha  { font-size: .78rem; color: #94A3B8; margin-bottom: 4px; }
    .badge { display: inline-flex; padding: 3px 10px; border-radius: 99px; font-size: .75rem; font-weight: 700; }
    .badge-green { background: #DCFCE7; color: #15803D; }
    .badge-amber { background: #FEF3C7; color: #B45309; }

    @media (max-width: 1024px) {

    .topbar { padding: 20px; }
    .topbar-title { font-size: 1.3rem; }

    .info-banner { margin: 0 16px 16px 16px; }

    .main-card { margin: 0 16px 16px 16px; }

    .historial-card { margin: 0 16px 32px 16px; }

    .token-val { font-size: 1.3rem; letter-spacing: .08em; }

    .cliente-preview { flex-direction: column; align-items: flex-start; gap: 10px; }

    .docs-row { flex-wrap: wrap; }
}
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <h1 class="topbar-title">Generador de tokens</h1>
        <div class="topbar-sub">Autoriza el cambio de un cliente a otra distribuidora</div>
    </div>

    {{-- ── INFO BANNER ── --}}
    <div class="info-banner">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" style="flex-shrink:0;margin-top:2px;">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        Cuando uno de tus clientes quiere cambiarse a otra distribuidora, tú debes generar un token de autorización. El cliente se lo entrega a la nueva distribuidora, y el coordinador valida el cambio. El token expira en 24 horas.
    </div>

    {{-- ── GENERAR TOKEN ── --}}
    <div class="main-card">
        <div class="main-card-header">
            <div class="main-card-title">Generar token de autorización</div>
            <div class="main-card-sub">Selecciona el cliente que desea cambiarse de distribuidora</div>
        </div>
        <div class="card-body">

            {{--
                Campos ocultos para Cambio_cliente:
                - IDpersona_id    → se llena al seleccionar cliente
                - distribuidor_id → distribuidora actual (de la sesión)
            --}}
            <input type="hidden" id="input-persona-id"     name="persona_id" />
            <input type="hidden" id="input-distribuidor-id" name="distribuidor_id" value="{{ auth()->user()->distribuidor_id ?? '' }}" />

            <div>
                <label class="field-label">Cliente a transferir</label>
                {{--
                    SELECT desde Clientes JOIN Personas
                    Solo clientes con estado = 'corriente' de esta distribuidora
                    data-persona-id  → Cambio_cliente.IDpersona_id
                    data-ine         → Clientes.INE
                    data-domicilio   → Clientes.comprobante_domicilio
                --}}
                <select class="field-select" id="cliente-select" onchange="onClienteSelect(this)">
                    <option value="">Seleccionar cliente...</option>
                    @foreach($clientes ?? [] as $cliente)
                        <option value="{{ $cliente->cliente_id }}"
                                data-persona-id="{{ $cliente->IDpersona_id }}"
                                data-nombre="{{ $cliente->nombre }} {{ $cliente->apellido }}"
                                data-ine="{{ $cliente->INE }}"
                                data-domicilio="{{ $cliente->comprobante_domicilio }}">
                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Preview cliente + validación de documentos (Clientes.INE + Clientes.comprobante_domicilio) --}}
            <div class="cliente-preview" id="cliente-preview">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div class="cliente-av" id="cliente-av"></div>
                    <div>
                        <div class="cliente-name" id="cliente-nombre"></div>
                        <div class="docs-row" style="margin-top:6px;">
                            {{-- Se llena en JS según data-ine y data-domicilio --}}
                            <span class="doc-badge" id="doc-ine"></span>
                            <span class="doc-badge" id="doc-domicilio"></span>
                        </div>
                    </div>
                </div>
                <span class="badge-elegible">Elegible</span>
            </div>

            <button class="btn-generar" onclick="generarToken()">Generar token →</button>

            {{-- Token generado — en producción vendrá del backend --}}
            <div class="token-box" id="token-box">
                <div class="token-label">Código de autorización</div>
                <div class="token-val" id="token-val"></div>
                <div class="token-exp">Válido 24 horas desde su generación</div>
                <button class="btn-copiar" onclick="copiar(this)">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2"/>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                    Copiar código
                </button>
            </div>

            <div class="alert-warn" id="alert-warn">
                Entrégale este código a tu cliente para que se lo dé a la nueva distribuidora. Es de un solo uso y expira en 24 horas.
            </div>

        </div>
    </div>

    {{-- ── HISTORIAL ── --}}
    {{--
        Viene de Cambio_cliente JOIN Personas
        Campos: IDpersona_id, distribuidor_id, token (cuando el backend lo genere)
    --}}
    <div class="historial-card">
        <div class="historial-header">
            <div class="historial-title">Tokens generados</div>
        </div>
        @forelse($tokens ?? [] as $token)
        <div class="hist-item">
            <div>
                {{-- Personas.nombre via Cambio_cliente.IDpersona_id --}}
                <div class="hist-nombre">{{ $token->nombre }} {{ $token->apellido }}</div>
                <div class="hist-token">{{ $token->codigo ?? '—' }}</div>
            </div>
            <div class="hist-right">
                <div class="hist-fecha">{{ $token->fecha ?? '—' }}</div>
                <span class="badge {{ $token->usado ? 'badge-green' : 'badge-amber' }}">
                    {{ $token->usado ? 'Usado' : 'Pendiente' }}
                </span>
            </div>
        </div>
        @empty
        <div style="padding:32px;text-align:center;color:#94A3B8;font-size:.85rem;">
            No hay tokens generados aún.
        </div>
        @endforelse
    </div>

</div>

<script>
    function onClienteSelect(sel) {
        const preview = document.getElementById('cliente-preview');
        document.getElementById('token-box').classList.remove('show');
        document.getElementById('alert-warn').classList.remove('show');

        if (!sel.value) {
            preview.classList.remove('show');
            document.getElementById('input-persona-id').value = '';
            return;
        }

        const opt = sel.options[sel.selectedIndex];

        {{-- Cambio_cliente.IDpersona_id --}}
        document.getElementById('input-persona-id').value = opt.dataset.personaId ?? '';

        const nombre   = opt.dataset.nombre;
        const initials = nombre.split(' ').map(w => w[0]).slice(0,2).join('');

        document.getElementById('cliente-av').textContent    = initials;
        document.getElementById('cliente-nombre').textContent = nombre;

        {{-- Clientes.INE --}}
        const ine = opt.dataset.ine && opt.dataset.ine !== 'null';
        document.getElementById('doc-ine').textContent  = ine ? '✓ INE' : '✗ INE';
        document.getElementById('doc-ine').className    = 'doc-badge ' + (ine ? 'doc-ok' : 'doc-missing');

        {{-- Clientes.comprobante_domicilio --}}
        const dom = opt.dataset.domicilio && opt.dataset.domicilio !== 'null';
        document.getElementById('doc-domicilio').textContent = dom ? '✓ Domicilio' : '✗ Domicilio';
        document.getElementById('doc-domicilio').className   = 'doc-badge ' + (dom ? 'doc-ok' : 'doc-missing');

        preview.classList.add('show');
    }

    function generarToken() {
        if (!document.getElementById('cliente-select').value) return;
        {{-- En producción este token vendrá del backend vía POST --}}
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        const seg   = n => Array.from({length:n}, () => chars[Math.floor(Math.random()*chars.length)]).join('');
        document.getElementById('token-val').textContent = 'TK-' + seg(4) + '-' + seg(4);
        document.getElementById('token-box').classList.add('show');
        document.getElementById('alert-warn').classList.add('show');
    }

    function copiar(btn) {
        const codigo = document.getElementById('token-val').textContent;
        navigator.clipboard.writeText(codigo).catch(() => {});
        btn.textContent = '✓ Copiado';
    }
</script>

@endsection