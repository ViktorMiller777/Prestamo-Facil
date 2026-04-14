@extends('layouts.coordinador')

@section('title', 'Gestión de cambios de cliente')
@section('page-title', 'Gestión de cambios de cliente')
@section('page-sub', 'Transferencia de clientes entre distribuidoras · Coordinador')

@push('styles')
<style>
    /* ── Stepper ── */
    .stepper        { display: flex; align-items: center; gap: 0; margin-bottom: 20px; }
    .step           { display: flex; align-items: center; gap: 10px; flex: 1; }
    .step-circle    { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; transition: all .2s; }
    .step-circle.done   { background: #16A34A; color: #fff; }
    .step-circle.active { background: #2563EB;  color: #fff; }
    .step-circle.idle   { background: #E2E8F0;  color: #94A3B8; }
    .step-label         { font-size: 11px; font-weight: 500; }
    .step-label.done    { color: #16A34A; }
    .step-label.active  { color: #2563EB; }
    .step-label.idle    { color: #94A3B8; }
    .step-line          { flex: 1; height: 2px; margin: 0 8px; }
    .step-line.done     { background: #16A34A; }
    .step-line.idle     { background: #E2E8F0; }

    /* ── Layout ── */
    .layout-cambio { display: grid; grid-template-columns: 1fr 300px; gap: 20px; }
    .main-col      { display: flex; flex-direction: column; gap: 16px; }
    .side-col      { display: flex; flex-direction: column; gap: 16px; }

    /* ── Búsqueda distribuidora ── */
    .search-section { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .dist-search    { display: flex; flex-direction: column; gap: 8px; }
    .dist-label     { font-size: 10px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #94A3B8; margin-bottom: 2px; }
    .inp-wrap       { position: relative; display: flex; align-items: center; gap: 8px; }
    .inp            { width: 100%; padding: 8px 12px; border: 1px solid #E2E8F0; border-radius: 7px; font-size: 12px; font-family: 'DM Mono', monospace; color: #0B1F3A; outline: none; transition: border-color .15s; }
    .inp:focus      { border-color: #2563EB; }
    .inp.valid      { border-color: #16A34A; background: #F0FDF4; }
    .inp.error      { border-color: #DC2626; background: #FFF1F2; }
    .btn-buscar     { padding: 8px 14px; border-radius: 7px; background: #0B1F3A; color: #fff; font-size: 11px; font-weight: 700; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; white-space: nowrap; transition: background .12s; flex-shrink: 0; }
    .btn-buscar:hover { background: #1D4ED8; }
    .dist-card      { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 12px 14px; display: flex; align-items: center; gap: 12px; margin-top: 4px; }
    .dist-card.found     { border-color: #16A34A; background: #F0FDF4; }
    .dist-card.not-found { border-color: #DC2626; background: #FFF1F2; }
    .dist-avatar    { width: 36px; height: 36px; border-radius: 50%; background: #1D4ED8; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0; }
    .dist-avatar.green { background: #16A34A; }
    .dist-avatar.purple { background: #7C3AED; }
    .dist-info-name { font-size: 12px; font-weight: 700; color: #0B1F3A; }
    .dist-info-sub  { font-size: 10px; color: #64748B; margin-top: 2px; }
    .dist-info-meta { font-size: 10px; color: #94A3B8; margin-top: 1px; }
    .arrow-icon     { width: 32px; height: 32px; background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #2563EB; font-size: 14px; font-weight: 700; }

    /* ── Tabla clientes ── */
    .radio-sel      { width: 15px; height: 15px; accent-color: #2563EB; cursor: pointer; }
    .row-selected td { background: #EFF6FF; }
    .cliente-name   { font-size: 12px; font-weight: 700; color: #0B1F3A; }
    .cliente-sub    { font-size: 10px; color: #94A3B8; margin-top: 2px; }

    /* ── Panel lateral ── */
    .side-section   { padding: 14px 16px; border-bottom: 1px solid #F1F5F9; }
    .side-section:last-child { border-bottom: none; }
    .side-label     { font-size: 9px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #94A3B8; margin-bottom: 10px; }
    .info-row       { display: flex; justify-content: space-between; align-items: center; padding: 5px 0; border-bottom: 1px solid #F8FAFC; font-size: 11px; }
    .info-row:last-child { border-bottom: none; }
    .info-key       { color: #64748B; }
    .info-val       { font-family: 'DM Mono', monospace; font-weight: 500; color: #0B1F3A; }
    .info-val.red   { color: #DC2626; }
    .info-val.blue  { color: #2563EB; }
    .info-val.green { color: #16A34A; }

    /* ── Llave generada ── */
    .llave-box      { background: #0B1F3A; border-radius: 8px; padding: 14px 16px; margin: 12px 0; }
    .llave-label    { font-size: 9px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-bottom: 6px; }
    .llave-val      { font-family: 'DM Mono', monospace; font-size: 15px; font-weight: 500; color: #60A5FA; letter-spacing: .06em; }
    .llave-sub      { font-size: 10px; color: rgba(255,255,255,0.35); margin-top: 4px; }
    .llave-copy     { display: inline-flex; align-items: center; gap: 4px; margin-top: 8px; padding: 4px 10px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 6px; font-size: 10px; color: rgba(255,255,255,0.6); cursor: pointer; transition: background .12s; }
    .llave-copy:hover { background: rgba(255,255,255,0.14); color: #fff; }

    /* ── Alerts ── */
    .alert-info    { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 7px; padding: 9px 12px; font-size: 11px; color: #1D4ED8; line-height: 1.5; }
    .alert-warn    { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 7px; padding: 9px 12px; font-size: 11px; color: #92400E; line-height: 1.5; }
    .alert-success { background: #F0FDF4; border: 1px solid #86EFAC; border-radius: 7px; padding: 9px 12px; font-size: 11px; color: #15803D; line-height: 1.5; }
    .alert-error   { background: #FFF1F2; border: 1px solid #FECACA; border-radius: 7px; padding: 9px 12px; font-size: 11px; color: #B91C1C; line-height: 1.5; }

    /* ── Botones ── */
    .btn           { padding: 8px 18px; border-radius: 7px; font-size: 12px; font-weight: 700; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .12s; }
    .btn-primary   { background: #2563EB; color: #fff; } .btn-primary:hover { background: #1D4ED8; }
    .btn-success   { background: #16A34A; color: #fff; }
    .btn-ghost     { background: #fff; color: #64748B; border: 1px solid #E2E8F0; } .btn-ghost:hover { background: #F8FAFC; }
    .btn:disabled  { opacity: .45; cursor: default; pointer-events: none; }
    .footer-actions { display: flex; gap: 10px; justify-content: flex-end; padding: 13px 18px; background: #F8FAFC; border-top: 1px solid #F1F5F9; }

    /* ── Card states ── */
    .card-inactive { opacity: .5; pointer-events: none; }
    .divider       { height: 1px; background: #F1F5F9; margin: 12px 0; }
</style>
@endpush

@section('content')

{{-- ── KPIs ── --}}
<div class="pf-metrics">
    <div class="pf-metric pf-metric-accent">
        <div class="pf-metric-label">Cambios este corte</div>
        {{-- TODO: $cambiosCorte --}}
        <div class="pf-metric-value">{{ $cambiosCorte ?? 3 }}</div>
        <div class="pf-metric-delta blue">En este período</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Pendientes</div>
        <div class="pf-metric-value" id="kpi-pend">{{ $cambiosPendientes ?? 1 }}</div>
        {{-- TODO: $cambiosPendientes --}}
        <div class="pf-metric-delta amber">Por completar</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Completados</div>
        <div class="pf-metric-value" id="kpi-comp">{{ $cambiosCompletados ?? 2 }}</div>
        {{-- TODO: $cambiosCompletados --}}
        <div class="pf-metric-delta">Llaves generadas</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Próximo corte</div>
        {{-- TODO: $proximoCorte->fecha_limite_pago --}}
        <div class="pf-metric-value" style="font-size:1rem;">{{ isset($proximoCorte) ? \Carbon\Carbon::parse($proximoCorte->fecha_limite_pago)->format('d M') : '15 may' }}</div>
        <div class="pf-metric-delta">Fecha límite</div>
    </div>
</div>

{{-- ── STEPPER ── --}}
<div class="stepper">
    <div class="step">
        <div class="step-circle active" id="sc1">1</div>
        <div class="step-label active" id="sl1">Buscar distribuidoras</div>
    </div>
    <div class="step-line idle" id="line1"></div>
    <div class="step">
        <div class="step-circle idle" id="sc2">2</div>
        <div class="step-label idle" id="sl2">Seleccionar cliente</div>
    </div>
    <div class="step-line idle" id="line2"></div>
    <div class="step">
        <div class="step-circle idle" id="sc3">3</div>
        <div class="step-label idle" id="sl3">Validar transferencia</div>
    </div>
    <div class="step-line idle" id="line3"></div>
    <div class="step">
        <div class="step-circle idle" id="sc4">4</div>
        <div class="step-label idle" id="sl4">Generar llave</div>
    </div>
</div>

{{-- ── LAYOUT PRINCIPAL ── --}}
<div class="layout-cambio">
<div class="main-col">

    {{-- ══ PASO 1: Buscar distribuidoras ══ --}}
    <div class="pf-card" id="paso1">
        <div class="pf-card-header">
            <span class="pf-card-title">Paso 1 — Identificar distribuidoras</span>
            <span class="pf-card-badge" style="background:#DBEAFE;color:#1D4ED8;">Origen y destino</span>
        </div>
        <div style="padding:16px 18px;">
            <div class="search-section">

                {{-- Origen --}}
                <div class="dist-search">
                    <div class="dist-label">Distribuidora origen (quien cede el cliente)</div>
                    <div class="inp-wrap">
                        {{-- TODO: buscar en tabla Distribuidor por nombre o ID --}}
                        <input type="text" class="inp" id="inp-origen" placeholder="Nombre o código..." autocomplete="off">
                        <button type="button" class="btn-buscar" onclick="buscarDist('origen')">Buscar</button>
                    </div>
                    <div class="dist-card" id="card-origen" style="opacity:.4;">
                        <div class="dist-avatar" style="background:#94A3B8;">—</div>
                        <div>
                            <div class="dist-info-name" style="color:#94A3B8;">Sin seleccionar</div>
                            <div class="dist-info-sub" style="color:#CBD5E1;">Ingresa nombre o código</div>
                        </div>
                    </div>
                </div>

                {{-- Destino --}}
                <div class="dist-search">
                    <div class="dist-label">Distribuidora destino (quien recibe el cliente)</div>
                    <div class="inp-wrap">
                        {{-- TODO: buscar en tabla Distribuidor por nombre o ID --}}
                        <input type="text" class="inp" id="inp-destino" placeholder="Nombre o código..." autocomplete="off">
                        <button type="button" class="btn-buscar" onclick="buscarDist('destino')">Buscar</button>
                    </div>
                    <div class="dist-card" id="card-destino" style="opacity:.4;">
                        <div class="dist-avatar" style="background:#94A3B8;">—</div>
                        <div>
                            <div class="dist-info-name" style="color:#94A3B8;">Sin seleccionar</div>
                            <div class="dist-info-sub" style="color:#CBD5E1;">Ingresa nombre o código</div>
                        </div>
                    </div>
                </div>

            </div>
            <div style="margin-top:14px;">
                <div class="alert-warn" id="alerta-paso1">
                    Asegúrate de que la distribuidora destino tenga línea de crédito suficiente para absorber al cliente.
                </div>
            </div>
        </div>
        <div class="footer-actions">
            <button type="button" class="btn btn-primary" onclick="irPaso2()">Continuar</button>
        </div>
    </div>

    {{-- ══ PASO 2: Seleccionar cliente ══ --}}
    <div class="pf-card card-inactive" id="paso2">
        <div class="pf-card-header">
            <span class="pf-card-title">Paso 2 — Seleccionar cliente a transferir</span>
            <span class="pf-card-badge" id="badge-origen-nombre" style="background:#FEF3C7;color:#B45309;">Clientes de la distribuidora</span>
        </div>
        <table class="pf-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Cliente</th>
                    <th>Vale / Producto</th>
                    <th>Pagos realizados</th>
                    <th>Saldo pendiente</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="tabla-clientes">
                <tr>
                    <td colspan="6" style="text-align:center;color:#94A3B8;padding:28px;font-size:12px;">
                        Completa el paso anterior para ver los clientes.
                    </td>
                </tr>
            </tbody>
        </table>
        {{--
        TODO: los clientes se cargarán vía fetch al controller:
        fetch('{{ route("coordinador.cambios.clientes") }}?distribuidor_id=' + distribuidorOrigenId)
        El controller filtra Distribuidor → Relacion → Vale → cliente_id de la tabla Relacion
        --}}
        <div class="footer-actions">
            <button type="button" class="btn btn-ghost" onclick="irPaso1()">Atrás</button>
            <button type="button" class="btn btn-primary" id="btn-paso2" onclick="irPaso3()" disabled>Continuar</button>
        </div>
    </div>

    {{-- ══ PASO 3: Validar transferencia ══ --}}
    <div class="pf-card card-inactive" id="paso3">
        <div class="pf-card-header">
            <span class="pf-card-title">Paso 3 — Validar transferencia</span>
            <span class="pf-card-badge" style="background:#F1F5F9;color:#475569;">Resumen del traspaso</span>
        </div>
        <div style="padding:16px 18px;">
            <div style="display:grid;grid-template-columns:1fr 48px 1fr;gap:12px;align-items:start;">
                <div>
                    <div class="side-label">Distribuidora origen</div>
                    <div class="dist-card found" id="resumen-origen-box">
                        <div class="dist-avatar" id="resumen-orig-avatar">—</div>
                        <div>
                            <div class="dist-info-name" id="resumen-orig-nombre">—</div>
                            <div class="dist-info-sub" id="resumen-orig-cat">—</div>
                            <div class="dist-info-meta">Cede al cliente</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;justify-content:center;padding-top:28px;">
                    <div class="arrow-icon">&#8594;</div>
                </div>
                <div>
                    <div class="side-label">Distribuidora destino</div>
                    <div class="dist-card found" id="resumen-destino-box">
                        <div class="dist-avatar green" id="resumen-dest-avatar">—</div>
                        <div>
                            <div class="dist-info-name" id="resumen-dest-nombre">—</div>
                            <div class="dist-info-sub" id="resumen-dest-cat">—</div>
                            <div class="dist-info-meta">Recibe al cliente</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <div class="side-label">Cliente que se transfiere</div>
            <div class="dist-card found" id="resumen-cliente-box">
                <div class="dist-avatar purple" id="resumen-cli-avatar">—</div>
                <div style="flex:1;">
                    <div class="dist-info-name" id="resumen-cli-nombre">—</div>
                    <div class="dist-info-sub" id="resumen-cli-sub">—</div>
                </div>
                <div id="resumen-cli-badge"></div>
            </div>
            <div class="divider"></div>
            <div class="alert-info">
                El saldo pendiente continuará siendo cobrado por la distribuidora destino a partir del siguiente corte.
                La comisión se recalculará según la categoría de la distribuidora destino.
            </div>
        </div>
        <div class="footer-actions">
            <button type="button" class="btn btn-ghost" onclick="irPaso2()">Atrás</button>
            {{--
            TODO: este botón hace POST a coordinador.cambios.store con:
            { distribuidor_origen_id, distribuidor_destino_id, cliente_id (persona_id), vale_id }
            El controller crea el registro en Cambio_cliente y genera la nueva folio_referencia
            --}}
            <button type="button" class="btn btn-primary" onclick="irPaso4()">Confirmar y generar llave</button>
        </div>
    </div>

    {{-- ══ PASO 4: Llave generada ══ --}}
    <div class="pf-card" id="paso4" style="display:none;">
        <div class="pf-card-header">
            <span class="pf-card-title">Paso 4 — Llave de referencia generada</span>
            <span class="pf-card-badge" style="background:#DCFCE7;color:#15803D;">Transferencia completada</span>
        </div>
        <div style="padding:16px 18px;">
            <div class="alert-success" style="margin-bottom:14px;">
                La transferencia fue registrada exitosamente. Se generó la nueva línea de referencia de pago para la distribuidora destino.
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div>
                    <div class="side-label">Llave de origen (histórico)</div>
                    <div class="llave-box" style="background:#475569;">
                        <div class="llave-label">Referencia original</div>
                        <div class="llave-val" id="llave-origen-val" style="color:#CBD5E1;">—</div>
                        <div class="llave-sub">Queda inactiva para este cliente</div>
                    </div>
                </div>
                <div>
                    <div class="side-label">Nueva llave — distribuidora destino</div>
                    <div class="llave-box">
                        <div class="llave-label">Nueva referencia de pago</div>
                        {{-- TODO: este valor viene del response del POST --}}
                        <div class="llave-val" id="llave-nueva-val">—</div>
                        <div class="llave-sub" id="llave-sub">Válida a partir del siguiente corte</div>
                        <div class="llave-copy" onclick="copiarLlave()">
                            <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                                <rect x="4" y="4" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.2"/>
                                <path d="M2 8V2h6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                            </svg>
                            Copiar referencia
                        </div>
                    </div>
                </div>
            </div>
            <div class="divider"></div>
            <div class="side-label">Detalle del traspaso</div>
            <div style="background:#F8FAFC;border:1px solid #F1F5F9;border-radius:8px;overflow:hidden;">
                <table class="pf-table">
                    <tbody id="tabla-resumen-final"></tbody>
                </table>
            </div>
        </div>
        <div class="footer-actions">
            <button type="button" class="btn btn-ghost" onclick="nuevoTraspaso()">Nuevo traspaso</button>
            {{-- TODO: route('coordinador.cambios.exportar', $cambio->id) --}}
            <button type="button" class="btn btn-primary">Imprimir / Exportar</button>
        </div>
    </div>

</div>

{{-- ── PANEL LATERAL ── --}}
<div class="side-col">

    <div class="pf-card">
        <div class="pf-card-header">
            <span class="pf-card-title">Reglas del traspaso</span>
        </div>
        <div class="side-section">
            <div class="side-label">Condiciones requeridas</div>
            <div class="info-row"><span class="info-key">Periodicidad</span><span class="info-val">Cada quincena</span></div>
            <div class="info-row"><span class="info-key">Cliente sin adeudo vencido</span><span class="info-val green">Requerido</span></div>
            <div class="info-row"><span class="info-key">Destino con crédito disponible</span><span class="info-val green">Requerido</span></div>
            <div class="info-row"><span class="info-key">Aprobación coordinador</span><span class="info-val blue">Esta vista</span></div>
        </div>
        <div class="side-section">
            <div class="side-label">Efecto en comisiones</div>
            <div class="info-row"><span class="info-key">Comisión origen</span><span class="info-val red">Se cancela</span></div>
            <div class="info-row"><span class="info-key">Comisión destino</span><span class="info-val green">Se recalcula</span></div>
            <div class="info-row"><span class="info-key">Aplica desde</span><span class="info-val">Siguiente corte</span></div>
        </div>
        <div class="side-section">
            <div class="side-label">Referencia de pago</div>
            <div class="alert-info" style="font-size:10px;line-height:1.5;">
                Se genera una nueva línea de referencia única para la distribuidora destino.
                La referencia anterior queda inactiva para este cliente a partir de este traspaso.
            </div>
        </div>
    </div>

    <div class="pf-card">
        <div class="pf-card-header">
            <span class="pf-card-title">Traspasos recientes</span>
        </div>
        {{--
        TODO: @forelse($traspasos as $t)
        <div style="padding:10px 16px;border-bottom:1px solid #F8FAFC;">
            <div style="font-size:11px;font-weight:700;color:#0B1F3A;">
                {{ $t->cliente->nombre }} → {{ $t->distribuidoraDestino->nombre }}
            </div>
            <div style="font-size:10px;color:#94A3B8;margin-top:2px;">
                {{ $t->created_at->diffForHumans() }} · Ref: {{ $t->folio_referencia }}
            </div>
        </div>
        @empty
        <div style="text-align:center;color:#94A3B8;font-size:11px;padding:20px;">
            Sin traspasos registrados.
        </div>
        @endforelse
        --}}
        <div style="padding:10px 16px;border-bottom:1px solid #F8FAFC;">
            <div style="font-size:11px;font-weight:700;color:#0B1F3A;">Luis Ramírez → Allison Torres</div>
            <div style="font-size:10px;color:#94A3B8;margin-top:2px;">hace 1 quincena · Ref: 44F22819031</div>
        </div>
        <div style="padding:10px 16px;border-bottom:1px solid #F8FAFC;">
            <div style="font-size:11px;font-weight:700;color:#0B1F3A;">Ana Robledo → Rosa Hernández</div>
            <div style="font-size:10px;color:#94A3B8;margin-top:2px;">hace 2 quincenas · Ref: 09C44827301</div>
        </div>
        <div style="padding:10px 16px;">
            <div style="font-size:11px;font-weight:700;color:#94A3B8;">Sin más registros</div>
        </div>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
    var paso = 1;
    var origenEncontrado = false, destinoEncontrado = false;
    var distribuidorOrigenId = null, distribuidorDestinoId = null;
    var clienteSeleccionado = null;

    {{--
    TODO: reemplazar clientesOrigen por fetch real:
    function cargarClientesOrigen(distribuidorId) {
        fetch('{{ route("coordinador.cambios.clientes") }}?distribuidor_id=' + distribuidorId, {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(r => r.json()).then(clientes => { renderClientes(clientes); });
    }
    --}}
    var clientesEjemplo = [
        { id: 1, nombre: 'María Eugenia Martínez', curp: 'MAME8204023H1', folio: 'VL-1987', producto: '8/10 Plus', pagos: '4/8', pagosNum: 4, total: 8, saldo: '$12,600', adeudoNum: 12600 },
        { id: 2, nombre: 'Rocío Álvarez Salas',    curp: 'AASA9107154B2', folio: 'VL-1854', producto: '5/10 Normal', pagos: '3/10', pagosNum: 3, total: 10, saldo: '$6,440', adeudoNum: 6440 },
        { id: 3, nombre: 'Carmen Delgado Ruiz',    curp: 'DERC7803019F3', folio: 'VL-2210', producto: '6/12 Plus',  pagos: '6/12', pagosNum: 6, total: 12, saldo: '$9,800', adeudoNum: 9800 },
    ];

    function setStep(n) {
        for (var i = 1; i <= 4; i++) {
            var sc = document.getElementById('sc' + i), sl = document.getElementById('sl' + i);
            if (!sc) continue;
            if (i < n)      { sc.className = 'step-circle done';   sl.className = 'step-label done';   sc.textContent = '✓'; }
            else if (i === n){ sc.className = 'step-circle active'; sl.className = 'step-label active'; sc.textContent = i; }
            else             { sc.className = 'step-circle idle';   sl.className = 'step-label idle';   sc.textContent = i; }
            if (i < 4) { var line = document.getElementById('line' + i); if (line) line.className = 'step-line ' + (i < n ? 'done' : 'idle'); }
        }
        paso = n;
    }

    function setCardActive(id, active) {
        var c = document.getElementById(id);
        if (!c) return;
        if (active) c.classList.remove('card-inactive');
        else c.classList.add('card-inactive');
    }

    function buscarDist(tipo) {
        var val = document.getElementById('inp-' + tipo).value.trim();
        if (!val) return;
        {{--
        TODO: reemplazar por fetch real:
        fetch('{{ route("coordinador.cambios.buscar-distribuidor") }}?q=' + encodeURIComponent(val), {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(r => r.json()).then(data => { if (data) montarCardDist(tipo, data); });
        --}}
        var initials = val.split(' ').map(function(w){ return w[0] || ''; }).slice(0, 2).join('').toUpperCase();
        var catFake  = tipo === 'origen' ? 'Oro 10%' : 'Plata 6%';
        montarCardDist(tipo, { id: tipo === 'origen' ? 1 : 2, nombre: val, iniciales: initials, categoria: catFake, clientes: tipo === 'origen' ? 14 : 8, credito: tipo === 'origen' ? '$80,000' : '$50,000', disponible: tipo === 'origen' ? '$22,400' : '$31,200' });
    }

    function montarCardDist(tipo, data) {
        var card = document.getElementById('card-' + tipo);
        card.className = 'dist-card found';
        card.style.opacity = '1';
        var colorAvatar = tipo === 'destino' ? 'green' : '';
        card.innerHTML = '<div class="dist-avatar ' + colorAvatar + '">' + data.iniciales + '</div>'
            + '<div>'
            + '<div class="dist-info-name">' + data.nombre + '</div>'
            + '<div class="dist-info-sub">' + data.categoria + ' · ' + data.clientes + ' clientes activos</div>'
            + '<div class="dist-info-meta">Línea de crédito: ' + data.credito + ' · Disponible: ' + data.disponible + '</div>'
            + '</div>';
        if (tipo === 'origen') { origenEncontrado = true; distribuidorOrigenId = data.id; }
        else                   { destinoEncontrado = true; distribuidorDestinoId = data.id; }
        if (origenEncontrado && destinoEncontrado) {
            document.getElementById('alerta-paso1').className = 'alert-success';
            document.getElementById('alerta-paso1').textContent = 'Ambas distribuidoras encontradas. La destino tiene crédito suficiente para el traspaso.';
        }
    }

    function irPaso2() {
        if (!origenEncontrado || !destinoEncontrado) {
            document.getElementById('alerta-paso1').className = 'alert-error';
            document.getElementById('alerta-paso1').textContent = 'Debes buscar y confirmar ambas distribuidoras antes de continuar.';
            return;
        }
        setStep(2);
        setCardActive('paso2', true);
        setCardActive('paso1', false);
        var nombreOrigen = document.getElementById('inp-origen').value.trim();
        document.getElementById('badge-origen-nombre').textContent = 'Clientes de ' + nombreOrigen.split(' ')[0];
        renderClientes(clientesEjemplo);
    }

    function irPaso1() {
        setStep(1); setCardActive('paso1', true); setCardActive('paso2', false);
        clienteSeleccionado = null;
    }

    function renderClientes(lista) {
        var body = document.getElementById('tabla-clientes');
        body.innerHTML = '';
        lista.forEach(function(c) {
            var tr = document.createElement('tr');
            if (clienteSeleccionado && clienteSeleccionado.id === c.id) tr.className = 'row-selected';
            var pagosColor = c.pagosNum === 0 ? '#DC2626' : (c.pagosNum / c.total < 0.5 ? '#D97706' : '#16A34A');
            tr.innerHTML = '<td><input type="radio" class="radio-sel" name="cli-sel" ' + (clienteSeleccionado && clienteSeleccionado.id === c.id ? 'checked' : '') + ' onchange="selCliente(' + c.id + ')"></td>'
                + '<td><div class="cliente-name">' + c.nombre + '</div><div class="cliente-sub">CURP: ' + c.curp + '</div></td>'
                + '<td><span class="pf-badge pf-badge-blue">' + c.folio + '</span><div style="font-size:10px;color:#94A3B8;margin-top:3px;">' + c.producto + '</div></td>'
                + '<td class="mono" style="color:' + pagosColor + ';font-weight:700;">' + c.pagos + '</td>'
                + '<td class="mono" style="font-weight:700;">' + c.saldo + '</td>'
                + '<td><span class="pf-badge pf-badge-green">Al corriente</span></td>';
            tr.style.cursor = 'pointer';
            (function(cc){ tr.addEventListener('click', function(){ selCliente(cc.id); }); })(c);
            body.appendChild(tr);
        });
    }

    function selCliente(id) {
        clienteSeleccionado = clientesEjemplo.find(function(c){ return c.id === id; });
        renderClientes(clientesEjemplo);
        document.getElementById('btn-paso2').disabled = false;
    }

    function irPaso3() {
        if (!clienteSeleccionado) return;
        setStep(3); setCardActive('paso3', true); setCardActive('paso2', false);
        var origNombre = document.getElementById('inp-origen').value.trim();
        var destNombre = document.getElementById('inp-destino').value.trim();
        var origInit   = origNombre.split(' ').map(function(w){ return w[0] || ''; }).slice(0, 2).join('').toUpperCase();
        var destInit   = destNombre.split(' ').map(function(w){ return w[0] || ''; }).slice(0, 2).join('').toUpperCase();
        var cliInit    = clienteSeleccionado.nombre.split(' ').map(function(w){ return w[0] || ''; }).slice(0, 2).join('').toUpperCase();
        document.getElementById('resumen-orig-avatar').textContent  = origInit;
        document.getElementById('resumen-orig-nombre').textContent  = origNombre;
        document.getElementById('resumen-orig-cat').textContent     = 'Oro 10%';
        document.getElementById('resumen-dest-avatar').textContent  = destInit;
        document.getElementById('resumen-dest-nombre').textContent  = destNombre;
        document.getElementById('resumen-dest-cat').textContent     = 'Plata 6%';
        document.getElementById('resumen-cli-avatar').textContent   = cliInit;
        document.getElementById('resumen-cli-nombre').textContent   = clienteSeleccionado.nombre;
        document.getElementById('resumen-cli-sub').textContent      = clienteSeleccionado.folio + ' · Saldo: ' + clienteSeleccionado.saldo;
        document.getElementById('resumen-cli-badge').innerHTML      = '<span class="pf-badge pf-badge-blue">' + clienteSeleccionado.producto + '</span>';
    }

    function irPaso4() {
        {{--
        TODO: POST al controller:
        fetch('{{ route("coordinador.cambios.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                distribuidor_origen_id:  distribuidorOrigenId,
                distribuidor_destino_id: distribuidorDestinoId,
                persona_id:              clienteSeleccionado.id,
            })
        }).then(r => r.json()).then(res => {
            document.getElementById('llave-nueva-val').textContent  = res.folio_referencia_nueva;
            document.getElementById('llave-origen-val').textContent = res.folio_referencia_original;
            document.getElementById('llave-sub').textContent        = 'Asignada a ' + destNombre + ' · Válida desde próximo corte';
            montarResumenFinal(res);
        });
        --}}
        setStep(4);
        setCardActive('paso3', false);
        document.getElementById('paso4').style.display = 'block';
        var nuevaLlave = generarLlave();
        var destNombre = document.getElementById('inp-destino').value.trim();
        document.getElementById('llave-nueva-val').textContent  = nuevaLlave;
        document.getElementById('llave-origen-val').textContent = '22B91034118';
        document.getElementById('llave-sub').textContent        = 'Asignada a ' + destNombre + ' · Válida desde próximo corte';
        var filas = [
            ['Cliente transferido',   clienteSeleccionado.nombre],
            ['Distribuidora origen',  document.getElementById('inp-origen').value.trim()],
            ['Distribuidora destino', destNombre],
            ['Folio del vale',        clienteSeleccionado.folio],
            ['Saldo transferido',     clienteSeleccionado.saldo],
            ['Pagos realizados',      clienteSeleccionado.pagos],
            ['Nueva referencia',      nuevaLlave],
            ['Fecha del traspaso',    new Date().toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' })],
        ];
        var body = document.getElementById('tabla-resumen-final'); body.innerHTML = '';
        filas.forEach(function(f){
            var tr = document.createElement('tr');
            tr.innerHTML = '<td style="color:#64748B;font-size:11px;width:160px;">' + f[0] + '</td>'
                + '<td style="font-family:DM Mono,monospace;font-size:11px;font-weight:500;color:#0B1F3A;">' + f[1] + '</td>';
            body.appendChild(tr);
        });
        document.getElementById('kpi-pend').textContent = Math.max(0, parseInt(document.getElementById('kpi-pend').textContent) - 1);
        document.getElementById('kpi-comp').textContent = parseInt(document.getElementById('kpi-comp').textContent) + 1;
    }

    function generarLlave() {
        var p = ['33D','44F','55G','66H','77J'][Math.floor(Math.random() * 5)];
        return p + Math.floor(10000000 + Math.random() * 90000000);
    }

    function copiarLlave() {
        var v = document.getElementById('llave-nueva-val').textContent;
        navigator.clipboard && navigator.clipboard.writeText(v);
        var btn = document.querySelector('.llave-copy');
        btn.textContent = '✓ Copiado';
        setTimeout(function(){
            btn.innerHTML = '<svg width="11" height="11" viewBox="0 0 12 12" fill="none"><rect x="4" y="4" width="7" height="7" rx="1" stroke="currentColor" stroke-width="1.2"/><path d="M2 8V2h6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg> Copiar referencia';
        }, 2000);
    }

    function nuevoTraspaso() {
        origenEncontrado = false; destinoEncontrado = false;
        distribuidorOrigenId = null; distribuidorDestinoId = null;
        clienteSeleccionado = null;
        ['inp-origen','inp-destino'].forEach(function(id){ document.getElementById(id).value = ''; document.getElementById(id).className = 'inp'; });
        ['origen','destino'].forEach(function(tipo){
            var c = document.getElementById('card-' + tipo);
            c.className = 'dist-card'; c.style.opacity = '.4';
            c.innerHTML = '<div class="dist-avatar" style="background:#94A3B8;">—</div><div><div class="dist-info-name" style="color:#94A3B8;">Sin seleccionar</div><div class="dist-info-sub" style="color:#CBD5E1;">Ingresa nombre o código</div></div>';
        });
        document.getElementById('alerta-paso1').className = 'alert-warn';
        document.getElementById('alerta-paso1').textContent = 'Asegúrate de que la distribuidora destino tenga línea de crédito suficiente para absorber al cliente.';
        document.getElementById('btn-paso2').disabled = true;
        document.getElementById('paso4').style.display = 'none';
        setStep(1);
        setCardActive('paso1', true);
        setCardActive('paso2', false);
        setCardActive('paso3', false);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
@endpush