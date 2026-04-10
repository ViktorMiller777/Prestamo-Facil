@extends('layouts.app')

@section('title', 'Configuración')
@section('page-title', 'Panel de configuración')
@section('page-sub', 'Parámetros globales del sistema · Solo acceso Gerente')

@push('styles')
<style>
    .cfg-section { padding: 18px; border-bottom: 1px solid #F1F5F9; }
    .cfg-section:last-of-type { border-bottom: none; }
    .cfg-title { font-size: 9px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #94A3B8; margin-bottom: 12px; }
    .cfg-row   { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; align-items: start; }
    .f-label { font-size: 11px; font-weight: 500; color: #64748B; margin-bottom: 4px; display: block; }
    .f-desc  { font-size: 10px; color: #94A3B8; line-height: 1.4; margin-top: 4px; }
    .f-wrap  { position: relative; display: flex; align-items: center; }
    .f-prefix { position: absolute; left: 10px; font-size: 12px; font-weight: 700; color: #64748B; font-family: 'DM Mono', monospace; pointer-events: none; }
    .f-input { width: 100%; padding: 8px 10px 8px 24px; border: 1px solid #E2E8F0; border-radius: 7px; font-size: 12px; font-family: 'DM Mono', monospace; color: #0B1F3A; outline: none; transition: border-color .12s; }
    .f-input:focus { border-color: #2563EB; }
    .prev-pill { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: 10px; font-weight: 700; font-family: 'DM Mono', monospace; }
    .prev-pill.blue  { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }
    .prev-pill.green { background: #F0FDF4; color: #15803D; border: 1px solid #BBF7D0; }
    .prev-pill.amber { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
    .mult-grid  { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; margin-top: 4px; }
    .mult-item  { display: flex; flex-direction: column; gap: 3px; }
    .mult-badge { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 7px; padding: 8px 4px; text-align: center; font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 500; color: #0B1F3A; }
    .mult-badge.active { background: #EFF6FF; border-color: #93C5FD; color: #1D4ED8; }
    .mult-del { font-size: 9px; color: #CBD5E1; text-align: center; cursor: pointer; transition: color .12s; text-transform: uppercase; letter-spacing: .04em; }
    .mult-del:hover { color: #DC2626; }
    .alert-warn { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 7px; padding: 9px 12px; font-size: 11px; color: #92400E; line-height: 1.5; }
    .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #F8FAFC; }
    .toggle-row:last-child { border-bottom: none; }
    .toggle-name { font-size: 12px; font-weight: 500; color: #0B1F3A; }
    .toggle-desc { font-size: 10px; color: #94A3B8; margin-top: 2px; }
    .ts { position: relative; width: 36px; height: 20px; flex-shrink: 0; }
    .ts input { opacity: 0; width: 0; height: 0; }
    .tsl { position: absolute; inset: 0; background: #E2E8F0; border-radius: 99px; cursor: pointer; transition: .2s; }
    .tsl:before { content: ''; position: absolute; width: 14px; height: 14px; left: 3px; top: 3px; background: #fff; border-radius: 50%; transition: .2s; }
    .ts input:checked + .tsl { background: #2563EB; }
    .ts input:checked + .tsl:before { transform: translateX(16px); }
    .hist-sep { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .hist-line { flex: 1; height: 1px; background: #F1F5F9; }
    .hist-row { display: flex; align-items: center; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #F8FAFC; font-size: 12px; }
    .hist-row:last-child { border-bottom: none; }
    .hist-campo  { color: #64748B; font-weight: 500; }
    .hist-cambio { font-family: 'DM Mono', monospace; color: #0B1F3A; font-size: 11px; }
    .hist-meta   { font-size: 10px; color: #94A3B8; text-align: right; }
    .cfg-footer { display: flex; align-items: center; justify-content: space-between; padding: 12px 18px; background: #F8FAFC; border-top: 1px solid #F1F5F9; }
    .cfg-footer-info { font-size: 10px; color: #94A3B8; }
    .btn { padding: 7px 16px; border-radius: 7px; font-size: 12px; font-weight: 500; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .12s; }
    .btn-primary { background: #2563EB; color: #fff; } .btn-primary:hover { background: #1D4ED8; }
    .btn-success { background: #16A34A; color: #fff; }
    .btn-error   { background: #DC2626; color: #fff; }
    .btn-ghost   { background: #fff; color: #64748B; border: 1px solid #E2E8F0; } .btn-ghost:hover { background: #F8FAFC; }

    /* ── Barra flotante de guardado ── */
    .save-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: #0B1F3A;
        border-top: 2px solid rgba(255,255,255,0.08);
        padding: 14px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 999;
        transform: translateY(100%);
        transition: transform .22s cubic-bezier(.4,0,.2,1);
    }
    .save-bar.visible { transform: translateY(0); }
    .save-bar-msg { font-size: 13px; color: rgba(255,255,255,0.5); }
    .save-bar-msg strong { color: #fff; font-weight: 600; }
    .save-bar-actions { display: flex; gap: 8px; align-items: center; }
    .btn-bar-ghost {
        padding: 8px 16px; border-radius: 7px; font-size: 12px; font-weight: 500;
        background: transparent; color: rgba(255,255,255,0.5);
        border: 1px solid rgba(255,255,255,0.15); cursor: pointer;
        font-family: 'DM Sans', sans-serif; transition: all .12s;
    }
    .btn-bar-ghost:hover { background: rgba(255,255,255,0.07); color: #fff; }
    .btn-bar-save {
        padding: 9px 22px; border-radius: 7px; font-size: 13px; font-weight: 600;
        background: #2563EB; color: #fff; border: none; cursor: pointer;
        font-family: 'DM Sans', sans-serif; transition: background .12s;
        display: flex; align-items: center; gap: 6px;
    }
    .btn-bar-save:hover          { background: #1D4ED8; }
    .btn-bar-save:disabled       { opacity: .6; cursor: default; }
    .btn-bar-save.btn-success    { background: #16A34A; }
    .btn-bar-save.btn-error-save { background: #DC2626; }

    /* Padding inferior para que el footer de la card no quede tapado por la barra */
    body { padding-bottom: 72px; }
</style>
@endpush

@section('content')

{{-- ── KPIs ── --}}
<div class="pf-metrics">
    <div class="pf-metric pf-metric-accent">
        <div class="pf-metric-label">Valor del punto</div>
        <div class="pf-metric-value">${{ number_format($config->valor_punto ?? 2, 2) }}</div>
        <div class="pf-metric-delta blue">Por punto canjeado</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Comisión por no pago</div>
        <div class="pf-metric-value">${{ number_format($config->comision_no_pago ?? 300, 0) }}</div>
        <div class="pf-metric-delta amber">Cargo al distribuidor</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Múltiplos activos</div>
        <div class="pf-metric-value">{{ count($config->multiplos ?? [100,250,500,750,1000]) }}</div>
        <div class="pf-metric-delta">Opciones de retiro</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Último cambio</div>
        <div class="pf-metric-value" style="font-size:15px;">
            {{ isset($config->updated_at) ? $config->updated_at->diffForHumans() : 'Sin cambios' }}
        </div>
        <div class="pf-metric-delta">Por Gerente Admin</div>
    </div>
</div>

{{-- ── CARD PRINCIPAL ── --}}
<div class="pf-card">
    <div class="pf-card-header">
        <span class="pf-card-title">Parámetros financieros</span>
        <span class="pf-card-badge">Configuración global</span>
    </div>

    {{-- Valor del punto --}}
    <div class="cfg-section">
        <div class="cfg-title">Valor del punto</div>
        <div class="cfg-row">
            <div>
                <label class="f-label">Valor monetario por punto</label>
                <div class="f-wrap">
                    <span class="f-prefix">$</span>
                    <input type="number" class="f-input" name="valor_punto" id="inp-punto"
                           value="{{ $config->valor_punto ?? 2.00 }}" step="0.50" min="0.50"
                           oninput="actualizarPunto(this.value)">
                </div>
                <span class="f-desc">Monto en pesos equivalente a cada punto al momento del canje.</span>
            </div>
            <div>
                <label class="f-label">Vista previa del canje</label>
                <div style="display:flex;flex-direction:column;gap:5px;margin-top:6px;">
                    <span class="prev-pill blue"  id="prev-100">100 pts = $200.00</span>
                    <span class="prev-pill green"  id="prev-500">500 pts = $1,000.00</span>
                    <span class="prev-pill amber" id="prev-1000">1,000 pts = $2,000.00</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Comisión por no pago --}}
    <div class="cfg-section">
        <div class="cfg-title">Comisión por no pago</div>
        <div class="cfg-row">
            <div>
                <label class="f-label">Monto fijo de comisión</label>
                <div class="f-wrap">
                    <span class="f-prefix">$</span>
                    <input type="number" class="f-input" name="comision_no_pago" id="inp-comision"
                           value="{{ $config->comision_no_pago ?? 300 }}" step="50" min="0">
                </div>
                <span class="f-desc">Cargo que se descuenta del distribuidor cuando un cliente no paga su quincena.</span>
            </div>
            <div>
                <div class="alert-warn">
                    Se descuenta de la comisión del distribuidor en la siguiente conciliación quincenal.
                    Cambios aplican a partir del próximo corte.
                </div>
            </div>
        </div>
    </div>

    {{-- Múltiplos --}}
    <div class="cfg-section">
        <div class="cfg-title">Múltiplos de retiro de puntos</div>
        <label class="f-label">Montos disponibles para retiro (en puntos)</label>
        <div class="mult-grid" id="mult-grid"></div>
        <div style="display:flex;gap:8px;align-items:center;margin-top:10px;">
            <div class="f-wrap" style="width:130px;">
                <span class="f-prefix">#</span>
                <input type="number" class="f-input" id="inp-mult" placeholder="ej. 750" step="50" min="50">
            </div>
            <button type="button" class="btn btn-ghost" onclick="agregarMult()">+ Agregar</button>
            <span style="font-size:10px;color:#94A3B8;">Se ordenan automáticamente</span>
        </div>
        <input type="hidden" name="multiplos" id="inp-multiplos-hidden">
    </div>

    {{-- Opciones del sistema --}}
    <div class="cfg-section">
        <div class="cfg-title">Opciones del sistema</div>
        <div class="toggle-row">
            <div>
                <div class="toggle-name">Notificar al coordinador por cambios de configuración</div>
                <div class="toggle-desc">Envía un resumen al coordinador al guardar cambios.</div>
            </div>
            <label class="ts">
                <input type="checkbox" id="tog-notificar"
                       {{ ($config->notificar_coordinador ?? true) ? 'checked' : '' }}>
                <span class="tsl"></span>
            </label>
        </div>
        <div class="toggle-row">
            <div>
                <div class="toggle-name">Aplicar comisión por no pago automáticamente</div>
                <div class="toggle-desc">Si está desactivado, el gerente aprueba cada cargo manualmente.</div>
            </div>
            <label class="ts">
                <input type="checkbox" id="tog-comision"
                       {{ ($config->comision_automatica ?? true) ? 'checked' : '' }}>
                <span class="tsl"></span>
            </label>
        </div>
        <div class="toggle-row">
            <div>
                <div class="toggle-name">Permitir retiro parcial de puntos</div>
                <div class="toggle-desc">Habilita retiros fuera de los múltiplos definidos.</div>
            </div>
            <label class="ts">
                <input type="checkbox" id="tog-retiro"
                       {{ ($config->retiro_parcial ?? false) ? 'checked' : '' }}>
                <span class="tsl"></span>
            </label>
        </div>
    </div>

    {{-- Historial --}}
    <div class="cfg-section">
        <div class="hist-sep">
            <span style="font-size:9px;font-weight:700;letter-spacing:.07em;color:#94A3B8;text-transform:uppercase;">Historial de cambios</span>
            <div class="hist-line"></div>
        </div>
        @forelse($historial ?? [] as $log)
        <div class="hist-row">
            <div class="hist-campo">{{ $log->campo }}</div>
            <div class="hist-cambio">{{ $log->valor_anterior }} → {{ $log->valor_nuevo }}</div>
            <div class="hist-meta">{{ $log->usuario }}<br>{{ $log->fecha->diffForHumans() }}</div>
        </div>
        @empty
        {{-- Datos de ejemplo mientras no hay historial real --}}
        <div class="hist-row">
            <div class="hist-campo">Valor del punto</div>
            <div class="hist-cambio">$1.50 → $2.00</div>
            <div class="hist-meta">Gerente Admin<br>hace 3 días</div>
        </div>
        <div class="hist-row">
            <div class="hist-campo">Comisión por no pago</div>
            <div class="hist-cambio">$200 → $300</div>
            <div class="hist-meta">Gerente Admin<br>hace 2 semanas</div>
        </div>
        <div class="hist-row">
            <div class="hist-campo">Múltiplos de retiro</div>
            <div class="hist-cambio">Se agregó 1,000 pts</div>
            <div class="hist-meta">Gerente Admin<br>hace 1 mes</div>
        </div>
        @endforelse
    </div>

    <div class="cfg-footer">
        <span class="cfg-footer-info">Los cambios se aplican globalmente a todos los distribuidores.</span>
    </div>
</div>

{{-- ── BARRA FLOTANTE DE GUARDADO ── --}}
<div class="save-bar" id="save-bar">
    <span class="save-bar-msg">
        Tienes <strong id="cambios-count">0</strong> cambio(s) sin guardar
    </span>
    <div class="save-bar-actions">
        <button type="button" class="btn-bar-ghost" onclick="descartarCambios()">
            Descartar
        </button>
        <button type="button" class="btn-bar-save" id="btn-guardar" onclick="guardarCfg()">
            Guardar configuración
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var multiplos  = {!! json_encode($config->multiplos ?? [100, 250, 500, 750, 1000]) !!};
    var csrfToken  = '{{ csrf_token() }}';
    var urlGuardar = '/gerente/configuracion/update'; {{-- TODO: cambiar por route('gerente.configuracion.update') cuando la ruta esté definida --}}

    /* ── render de múltiplos ── */
    function renderMult() {
        var g = document.getElementById('mult-grid');
        g.innerHTML = '';
        multiplos.slice().sort(function(a, b) { return a - b; }).forEach(function(m, i) {
            var div = document.createElement('div');
            div.className = 'mult-item';
            div.innerHTML =
                '<div class="mult-badge' + (i < 3 ? ' active' : '') + '">'
                + m.toLocaleString('es-MX')
                + '<br><span style="font-size:9px;color:#94A3B8;">pts</span></div>'
                + '<div class="mult-del" onclick="eliminarMult(' + m + ')">✕ quitar</div>';
            g.appendChild(div);
        });
        document.getElementById('inp-multiplos-hidden').value =
            JSON.stringify(multiplos.slice().sort(function(a, b) { return a - b; }));
    }

    function eliminarMult(m) {
        if (multiplos.length <= 1) return;
        multiplos = multiplos.filter(function(x) { return x !== m; });
        renderMult();
    }

    function agregarMult() {
        var inp = document.getElementById('inp-mult');
        var v   = parseInt(inp.value);
        if (!v || v < 50) return;
        if (multiplos.indexOf(v) === -1) multiplos.push(v);
        inp.value = '';
        renderMult();
    }

    /* ── preview valor punto ── */
    function actualizarPunto(v) {
        var val = parseFloat(v) || 0;
        document.getElementById('prev-100').textContent =
            '100 pts = $' + (100 * val).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('prev-500').textContent =
            '500 pts = $' + (500 * val).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('prev-1000').textContent =
            '1,000 pts = $' + (1000 * val).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    /* ── detección de cambios ── */
    var valoresIniciales = {};

    function capturarIniciales() {
        valoresIniciales = {
            punto:    document.getElementById('inp-punto').value,
            comision: document.getElementById('inp-comision').value,
            multiplos: JSON.stringify(multiplos.slice().sort(function(a,b){return a-b;})),
            notificar: document.getElementById('tog-notificar').checked,
            comAuto:   document.getElementById('tog-comision').checked,
            retiro:    document.getElementById('tog-retiro').checked
        };
    }

    function contarCambios() {
        var actual = {
            punto:    document.getElementById('inp-punto').value,
            comision: document.getElementById('inp-comision').value,
            multiplos: document.getElementById('inp-multiplos-hidden').value,
            notificar: document.getElementById('tog-notificar').checked,
            comAuto:   document.getElementById('tog-comision').checked,
            retiro:    document.getElementById('tog-retiro').checked
        };
        var count = 0;
        Object.keys(valoresIniciales).forEach(function(k) {
            if (String(valoresIniciales[k]) !== String(actual[k])) count++;
        });
        return count;
    }

    function actualizarBarra() {
        var n   = contarCambios();
        var bar = document.getElementById('save-bar');
        document.getElementById('cambios-count').textContent = n;
        if (n > 0) bar.classList.add('visible');
        else        bar.classList.remove('visible');
    }

    function descartarCambios() {
        document.getElementById('inp-punto').value    = valoresIniciales.punto;
        document.getElementById('inp-comision').value = valoresIniciales.comision;
        document.getElementById('tog-notificar').checked = valoresIniciales.notificar;
        document.getElementById('tog-comision').checked  = valoresIniciales.comAuto;
        document.getElementById('tog-retiro').checked    = valoresIniciales.retiro;
        multiplos = JSON.parse(valoresIniciales.multiplos);
        renderMult();
        actualizarPunto(document.getElementById('inp-punto').value);
        actualizarBarra();
    }

    /* ── guardar via fetch ── */
    function guardarCfg() {
        var btn = document.getElementById('btn-guardar');
        btn.disabled    = true;
        btn.textContent = 'Guardando...';

        var payload = {
            valor_punto:           document.getElementById('inp-punto').value,
            comision_no_pago:      document.getElementById('inp-comision').value,
            multiplos:             JSON.parse(document.getElementById('inp-multiplos-hidden').value),
            notificar_coordinador: document.getElementById('tog-notificar').checked,
            comision_automatica:   document.getElementById('tog-comision').checked,
            retiro_parcial:        document.getElementById('tog-retiro').checked
        };

        fetch(urlGuardar, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(function(res) {
            if (!res.ok) return res.json().then(function(data) { throw data; });
            return res.json();
        })
        .then(function() {
            btn.textContent = '✓ Guardado';
            btn.className   = 'btn-bar-save btn-success';
            btn.disabled    = false;
            capturarIniciales();
            actualizarBarra();
            setTimeout(function() {
                btn.textContent = 'Guardar configuración';
                btn.className   = 'btn-bar-save';
            }, 2500);
        })
        .catch(function(err) {
            console.error('Error al guardar:', err);
            btn.textContent = 'Error al guardar';
            btn.className   = 'btn-bar-save btn-error-save';
            btn.disabled    = false;
            setTimeout(function() {
                btn.textContent = 'Guardar configuración';
                btn.className   = 'btn-bar-save';
            }, 3000);
        });
    }

    /* ── escuchar cambios en inputs ── */
    function escucharCambios() {
        ['inp-punto', 'inp-comision'].forEach(function(id) {
            document.getElementById(id).addEventListener('input', actualizarBarra);
        });
        ['tog-notificar', 'tog-comision', 'tog-retiro'].forEach(function(id) {
            document.getElementById(id).addEventListener('change', actualizarBarra);
        });
    }

    /* ── init ── */
    renderMult();
    actualizarPunto(document.getElementById('inp-punto').value);
    capturarIniciales();
    escucharCambios();

    /* Detectar cambios en múltiplos desde renderMult */
    var _renderMult = renderMult;
    renderMult = function() { _renderMult(); actualizarBarra(); };
</script>
@endpush