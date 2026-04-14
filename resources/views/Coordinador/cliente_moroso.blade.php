@extends('layouts.coordinador')

@section('title', 'Clientes morosos')
@section('page-title', 'Validación de clientes morosos')
@section('page-sub', 'Solicitudes de distribuidoras · Coordinador da la última palabra')

@push('styles')
<style>
    /* ── KPI row ── */
    .kpi-row { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 16px; }
    .kpi-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px; }
    .kpi-card.red-left  { border-left: 3px solid #DC2626; border-radius: 0 10px 10px 0; }
    .kpi-card.amber-left { border-left: 3px solid #D97706; border-radius: 0 10px 10px 0; }
    .kpi-label { font-size: 10px; color: #94A3B8; text-transform: uppercase; letter-spacing: .05em; font-weight: 500; margin-bottom: 6px; }
    .kpi-value { font-size: 22px; font-weight: 500; color: #0B1F3A; font-family: 'DM Mono', monospace; margin-bottom: 4px; }
    .kpi-sub        { font-size: 11px; }
    .kpi-sub.red    { color: #DC2626; }
    .kpi-sub.amber  { color: #D97706; }
    .kpi-sub.green  { color: #16A34A; }
    .kpi-sub.muted  { color: #94A3B8; }

    /* ── Tabs ── */
    .tabs-row { display: flex; padding: 0 18px; border-bottom: 1px solid #E2E8F0; }
    .tab-btn {
        padding: 10px 16px; font-size: 12px; font-weight: 500;
        color: #94A3B8; background: none; border: none;
        border-bottom: 2px solid transparent; margin-bottom: -1px;
        cursor: pointer; font-family: 'DM Sans', sans-serif; white-space: nowrap;
    }
    .tab-btn.active { color: #2563EB; border-bottom-color: #2563EB; font-weight: 600; }

    /* ── Solicitud card ── */
    .solicitud-card {
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        overflow: hidden;
        transition: box-shadow .15s;
    }
    .solicitud-card:hover { box-shadow: 0 2px 12px rgba(0,0,0,.06); }
    .solicitud-card.urgente { border-left: 3px solid #DC2626; border-radius: 0 10px 10px 0; }
    .solicitud-card.revisar { border-left: 3px solid #D97706; border-radius: 0 10px 10px 0; }

    .sol-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        padding: 14px 18px; border-bottom: 1px solid #F1F5F9; gap: 16px;
    }
    .sol-cliente-name { font-size: 14px; font-weight: 700; color: #0B1F3A; }
    .sol-cliente-sub  { font-size: 11px; color: #64748B; margin-top: 2px; }
    .sol-dist         { font-size: 11px; color: #94A3B8; margin-top: 4px; display: flex; align-items: center; gap: 5px; }
    .sol-dist-name    { color: #2563EB; font-weight: 500; }

    .sol-monto { text-align: right; flex-shrink: 0; }
    .sol-monto-val  { font-family: 'DM Mono', monospace; font-size: 18px; font-weight: 600; color: #DC2626; }
    .sol-monto-label { font-size: 10px; color: #94A3B8; margin-top: 2px; }

    /* ── Info grid ── */
    .sol-info {
        display: grid; grid-template-columns: repeat(4, 1fr);
        background: #F8FAFC; border-bottom: 1px solid #F1F5F9;
        gap: 0;
    }
    .sol-info-cell { padding: 10px 18px; border-right: 1px solid #F1F5F9; }
    .sol-info-cell:last-child { border-right: none; }
    .sol-info-label { font-size: 9px; color: #94A3B8; text-transform: uppercase; letter-spacing: .06em; font-weight: 500; margin-bottom: 3px; }
    .sol-info-val   { font-size: 13px; font-weight: 600; color: #0B1F3A; }
    .sol-info-val.red   { color: #DC2626; }
    .sol-info-val.amber { color: #D97706; }

    /* ── Motivo ── */
    .sol-motivo {
        padding: 12px 18px; border-bottom: 1px solid #F1F5F9;
        display: flex; align-items: flex-start; gap: 10px;
    }
    .sol-motivo-badge {
        background: #FEF3C7; color: #B45309;
        font-size: 10px; font-weight: 600;
        padding: 2px 8px; border-radius: 99px; flex-shrink: 0; margin-top: 1px;
    }
    .sol-motivo-text { font-size: 12px; color: #334155; line-height: 1.5; }
    .sol-motivo-time { font-size: 10px; color: #94A3B8; margin-top: 3px; }

    /* ── Historial de pagos mini ── */
    .sol-pagos { padding: 10px 18px; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .sol-pagos-label { font-size: 10px; color: #94A3B8; margin-right: 4px; white-space: nowrap; }
    .pago-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .pago-dot.ok      { background: #16A34A; }
    .pago-dot.tarde   { background: #D97706; }
    .pago-dot.falta   { background: #DC2626; }
    .pago-dot.futuro  { background: #E2E8F0; }
    .pagos-legend { margin-left: auto; display: flex; gap: 10px; flex-shrink: 0; }
    .leg-item { display: flex; align-items: center; gap: 4px; font-size: 10px; color: #64748B; }

    /* ── Acciones ── */
    .sol-actions { padding: 12px 18px; display: flex; align-items: center; gap: 10px; }
    .btn-confirmar {
        padding: 8px 18px; border-radius: 7px; font-size: 12px; font-weight: 600;
        background: #FEE2E2; color: #B91C1C; border: 1.5px solid #FECACA;
        cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .12s;
    }
    .btn-confirmar:hover { background: #FECACA; }
    .btn-rechazar {
        padding: 8px 18px; border-radius: 7px; font-size: 12px; font-weight: 600;
        background: #F8FAFC; color: #64748B; border: 1.5px solid #E2E8F0;
        cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .12s;
    }
    .btn-rechazar:hover { background: #F1F5F9; }
    .btn-historial {
        padding: 8px 14px; border-radius: 7px; font-size: 12px; font-weight: 500;
        background: transparent; color: #2563EB; border: none;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        text-decoration: none; margin-left: auto;
    }
    .btn-historial:hover { text-decoration: underline; }
    .sol-actions-info { font-size: 10px; color: #94A3B8; }

    /* ── Estado enviado/resuelto ── */
    .sol-card-resuelta { opacity: .6; pointer-events: none; }
    .sol-resolved-banner {
        padding: 10px 18px; display: flex; align-items: center; gap: 8px;
        font-size: 12px; font-weight: 500;
    }
    .sol-resolved-banner.confirmado { background: #FEF2F2; color: #B91C1C; }
    .sol-resolved-banner.rechazado  { background: #F8FAFC; color: #64748B; }

    /* ── Empty state ── */
    .empty-state {
        text-align: center; padding: 48px 24px; color: #94A3B8;
        display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .empty-icon { width: 40px; height: 40px; background: #F1F5F9; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

    /* ── Modal de confirmación ── */
    .modal-backdrop {
        display: none; position: fixed; inset: 0;
        background: rgba(11,31,58,.45); z-index: 998;
        align-items: center; justify-content: center;
    }
    .modal-backdrop.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 12px;
        border: 1px solid #E2E8F0; padding: 24px;
        width: 440px; max-width: 95vw;
    }
    .modal-title { font-size: 15px; font-weight: 700; color: #0B1F3A; margin-bottom: 4px; }
    .modal-sub   { font-size: 12px; color: #64748B; margin-bottom: 16px; line-height: 1.5; }
    .modal-cliente-box {
        background: #F8FAFC; border: 1px solid #E2E8F0;
        border-radius: 8px; padding: 12px 14px; margin-bottom: 16px;
    }
    .modal-cliente-name { font-size: 13px; font-weight: 700; color: #0B1F3A; }
    .modal-cliente-sub  { font-size: 11px; color: #94A3B8; margin-top: 2px; }
    .modal-alert {
        background: #FEF2F2; border: 1px solid #FECACA;
        border-radius: 8px; padding: 10px 14px;
        font-size: 11px; color: #7F1D1D; line-height: 1.5; margin-bottom: 16px;
    }
    .modal-footer { display: flex; gap: 8px; justify-content: flex-end; }
    .modal-btn { padding: 8px 18px; border-radius: 7px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .modal-btn-cancel  { background: #fff; color: #64748B; border: 1px solid #E2E8F0; }
    .modal-btn-confirm { background: #DC2626; color: #fff; }
    .modal-btn-confirm:hover { background: #B91C1C; }
    .modal-btn-reject  { background: #F1F5F9; color: #334155; border: 1px solid #E2E8F0; }
    .modal-btn-reject:hover  { background: #E2E8F0; }

    /* Textarea motivo rechazo */
    .modal-textarea {
        width: 100%; height: 72px; border: 1px solid #E2E8F0;
        border-radius: 7px; padding: 8px 12px; font-size: 12px;
        font-family: 'DM Sans', sans-serif; color: #334155;
        resize: none; outline: none; margin-bottom: 12px;
    }
    .modal-textarea:focus { border-color: #2563EB; }
    .modal-field-label { font-size: 11px; color: #64748B; font-weight: 500; margin-bottom: 5px; display: block; }
</style>
@endpush

@section('content')

{{-- ── KPI ROW ── --}}
{{--
    TODO: pasar $kpis con las claves:
      - pendientes   (int) → solicitudes con estado 'pendiente'
      - confirmados  (int) → resueltas hoy como moroso
      - rechazados   (int) → resueltas hoy como no moroso
      - clientes_morosos_total (int) → total clientes en estado moroso
--}}
<div class="kpi-row">
    <div class="kpi-card red-left">
        <div class="kpi-label">Solicitudes pendientes</div>
        <div class="kpi-value">{{ $kpis['pendientes'] ?? 0 }}</div>
        <div class="kpi-sub red">Requieren tu decisión</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Confirmados hoy</div>
        <div class="kpi-value">{{ $kpis['confirmados'] ?? 0 }}</div>
        <div class="kpi-sub muted">Pasaron a moroso</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Rechazados hoy</div>
        <div class="kpi-value">{{ $kpis['rechazados'] ?? 0 }}</div>
        <div class="kpi-sub green">No procedieron</div>
    </div>
    <div class="kpi-card amber-left">
        <div class="kpi-label">Total clientes morosos</div>
        <div class="kpi-value">{{ $kpis['clientes_morosos_total'] ?? 0 }}</div>
        <div class="kpi-sub amber">En toda la sucursal</div>
    </div>
</div>

{{-- ── CARD PRINCIPAL ── --}}
<div class="pf-card">
    <div class="pf-card-header">
        <span class="pf-card-title">Solicitudes de cambio a moroso</span>
        <span class="pf-card-badge">
            {{ $kpis['pendientes'] ?? 0 }} pendiente(s)
        </span>
    </div>

    {{-- Tabs --}}
    <div class="tabs-row">
        <button class="tab-btn active" data-tab="pendientes">
            Pendientes
            @if(($kpis['pendientes'] ?? 0) > 0)
                <span style="margin-left:5px;background:#DC2626;color:#fff;font-size:9px;padding:1px 5px;border-radius:99px;">
                    {{ $kpis['pendientes'] ?? 0 }}
                </span>
            @endif
        </button>
        <button class="tab-btn" data-tab="resueltos">Resueltos hoy</button>
        <button class="tab-btn" data-tab="historial">Historial completo</button>
    </div>

    {{-- Lista de solicitudes --}}
    <div style="padding:16px;display:flex;flex-direction:column;gap:12px;" id="lista-solicitudes">

        {{--
            TODO: iterar $solicitudes desde el controlador.
            Cada item necesita:
              $s->id
              $s->cliente->persona->nombre / apellido
              $s->cliente->persona->telefono
              $s->distribuidora->persona->nombre / apellido
              $s->vale->producto_label    (ej: '4/12 Normal')
              $s->vale->quincenas_pagadas / quincenas
              $s->deuda_actual            (decimal)
              $s->quincenas_vencidas      (int)
              $s->ultimo_pago             (Carbon o null)
              $s->motivo                  (string)
              $s->descripcion             (string, nullable)
              $s->created_at              (Carbon)
              $s->pagos_historial         (array de 'ok'|'tarde'|'falta'|'futuro')
              $s->urgente                 (bool — vence hoy o lleva más de 3 quincenas)
        --}}
        @forelse($solicitudes ?? [] as $s)
        <div class="solicitud-card {{ $s->urgente ? 'urgente' : 'revisar' }}" id="sol-{{ $s->id }}">

            {{-- Header --}}
            <div class="sol-header">
                <div>
                    <div class="sol-cliente-name">
                        {{ $s->cliente->persona->nombre }} {{ $s->cliente->persona->apellido }}
                    </div>
                    <div class="sol-cliente-sub">
                        Tel: {{ $s->cliente->persona->telefono ?? '—' }}
                        &nbsp;·&nbsp; Vale: {{ $s->vale->producto_label ?? '—' }}
                    </div>
                    <div class="sol-dist">
                        Solicitado por:
                        <span class="sol-dist-name">
                            {{ $s->distribuidora->persona->nombre }} {{ $s->distribuidora->persona->apellido }}
                        </span>
                        · hace {{ $s->created_at->diffForHumans(null, true) }}
                    </div>
                </div>
                <div class="sol-monto">
                    <div class="sol-monto-val">${{ number_format($s->deuda_actual, 0, '.', ',') }}</div>
                    <div class="sol-monto-label">Deuda actual</div>
                </div>
            </div>

            {{-- Info grid --}}
            <div class="sol-info">
                <div class="sol-info-cell">
                    <div class="sol-info-label">Quincenas vencidas</div>
                    <div class="sol-info-val {{ $s->quincenas_vencidas >= 3 ? 'red' : 'amber' }}">
                        {{ $s->quincenas_vencidas }}
                    </div>
                </div>
                <div class="sol-info-cell">
                    <div class="sol-info-label">Último pago</div>
                    <div class="sol-info-val">
                        {{ $s->ultimo_pago ? $s->ultimo_pago->format('d M Y') : 'Sin pagos' }}
                    </div>
                </div>
                <div class="sol-info-cell">
                    <div class="sol-info-label">Pagos realizados</div>
                    <div class="sol-info-val">
                        {{ $s->vale->quincenas_pagadas }}/{{ $s->vale->quincenas }}
                    </div>
                </div>
                <div class="sol-info-cell">
                    <div class="sol-info-label">Estado actual</div>
                    <div class="sol-info-val">
                        <span class="pf-badge pf-badge-amber">Al corriente</span>
                    </div>
                </div>
            </div>

            {{-- Historial de pagos visual --}}
            <div class="sol-pagos">
                <span class="sol-pagos-label">Historial:</span>
                @foreach($s->pagos_historial ?? [] as $pago)
                    <div class="pago-dot {{ $pago }}"
                         title="{{ $pago === 'ok' ? 'Pagado' : ($pago === 'tarde' ? 'Pago tardío' : ($pago === 'falta' ? 'Sin pago' : 'Pendiente')) }}">
                    </div>
                @endforeach
                <div class="pagos-legend">
                    <div class="leg-item"><div class="pago-dot ok"></div> A tiempo</div>
                    <div class="leg-item"><div class="pago-dot tarde"></div> Tarde</div>
                    <div class="leg-item"><div class="pago-dot falta"></div> Sin pago</div>
                </div>
            </div>

            {{-- Motivo de la distribuidora --}}
            <div class="sol-motivo">
                <span class="sol-motivo-badge">Motivo</span>
                <div>
                    <div class="sol-motivo-text">{{ $s->motivo }}</div>
                    @if($s->descripcion)
                    <div class="sol-motivo-text" style="color:#64748B;margin-top:3px;">
                        "{{ $s->descripcion }}"
                    </div>
                    @endif
                    <div class="sol-motivo-time">
                        Enviado {{ $s->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="sol-actions">
                <button class="btn-confirmar"
                    onclick="abrirModal('confirmar', {{ $s->id }}, '{{ $s->cliente->persona->nombre }} {{ $s->cliente->persona->apellido }}', '{{ $s->vale->producto_label ?? '' }}', '{{ number_format($s->deuda_actual, 0, ".", ",") }}')">
                    Confirmar moroso
                </button>
                <button class="btn-rechazar"
                    onclick="abrirModal('rechazar', {{ $s->id }}, '{{ $s->cliente->persona->nombre }} {{ $s->cliente->persona->apellido }}', '{{ $s->vale->producto_label ?? '' }}', '{{ number_format($s->deuda_actual, 0, ".", ",") }}')">
                    Rechazar solicitud
                </button>
                {{-- TODO: ruta de historial del cliente --}}
                <a href="#" class="btn-historial">Ver historial completo →</a>
                @if($s->urgente)
                <span style="margin-left:auto;font-size:10px;background:#FEE2E2;color:#B91C1C;padding:2px 8px;border-radius:99px;font-weight:600;">
                    Urgente
                </span>
                @endif
            </div>

        </div>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div style="font-size:13px;font-weight:500;color:#64748B;">Sin solicitudes pendientes</div>
            <div style="font-size:12px;">Cuando una distribuidora reporte un cliente moroso aparecerá aquí.</div>
        </div>
        @endforelse

    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL CONFIRMAR / RECHAZAR
══════════════════════════════════════ --}}
<div class="modal-backdrop" id="modal">
    <div class="modal-box">

        {{-- Confirmar moroso --}}
        <div id="modal-confirmar">
            <div class="modal-title">Confirmar cliente moroso</div>
            <div class="modal-sub">
                Esta acción es definitiva. El cliente pasará a estado moroso y
                la distribuidora será notificada automáticamente.
            </div>
            <div class="modal-cliente-box">
                <div class="modal-cliente-name" id="mc-nombre"></div>
                <div class="modal-cliente-sub" id="mc-sub"></div>
            </div>
            <div class="modal-alert">
                El cliente no podrá recibir nuevos vales mientras esté en estado moroso.
                Solo el coordinador puede revertir esta decisión.
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button class="modal-btn modal-btn-confirm" onclick="ejecutarAccion('confirmar')">
                    Sí, confirmar moroso
                </button>
            </div>
        </div>

        {{-- Rechazar solicitud --}}
        <div id="modal-rechazar" style="display:none;">
            <div class="modal-title">Rechazar solicitud</div>
            <div class="modal-sub">
                La distribuidora será notificada que la solicitud no procedió.
                El cliente continuará en estado normal.
            </div>
            <div class="modal-cliente-box">
                <div class="modal-cliente-name" id="mr-nombre"></div>
                <div class="modal-cliente-sub" id="mr-sub"></div>
            </div>
            <label class="modal-field-label">Motivo del rechazo (opcional)</label>
            <textarea class="modal-textarea" id="motivo-rechazo"
                placeholder="Explica por qué no procede la solicitud..."></textarea>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button class="modal-btn modal-btn-reject" onclick="ejecutarAccion('rechazar')">
                    Rechazar solicitud
                </button>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    var solicitudActual = null;
    var accionActual    = null;
    var csrfToken       = '{{ csrf_token() }}';

    /* ── Tabs ── */
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            /* TODO: filtrar la lista por tab cuando se conecte el controlador */
        });
    });

    /* ── Modal ── */
    function abrirModal(accion, id, nombre, producto, deuda) {
        solicitudActual = id;
        accionActual    = accion;

        document.getElementById('modal-confirmar').style.display = accion === 'confirmar' ? 'block' : 'none';
        document.getElementById('modal-rechazar').style.display  = accion === 'rechazar'  ? 'block' : 'none';

        if (accion === 'confirmar') {
            document.getElementById('mc-nombre').textContent = nombre;
            document.getElementById('mc-sub').textContent   = producto + ' · Adeudo: $' + deuda;
        } else {
            document.getElementById('mr-nombre').textContent = nombre;
            document.getElementById('mr-sub').textContent   = producto + ' · Adeudo: $' + deuda;
            document.getElementById('motivo-rechazo').value = '';
        }

        document.getElementById('modal').classList.add('open');
    }

    function cerrarModal() {
        document.getElementById('modal').classList.remove('open');
        solicitudActual = null;
        accionActual    = null;
    }

    /* Cerrar modal al hacer click en el backdrop */
    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    /* ── Ejecutar acción ── */
    function ejecutarAccion(accion) {
        if (!solicitudActual) return;

        var payload = {
            solicitud_id: solicitudActual,
            accion:       accion,
        };

        if (accion === 'rechazar') {
            payload.motivo_rechazo = document.getElementById('motivo-rechazo').value;
        }

        /* TODO: cuando la ruta esté lista, reemplazar la URL por:
           route('coordinador.clientes-morosos.resolver') */
        fetch('/coordinador/clientes-morosos/resolver', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(function(res) {
            if (!res.ok) return res.json().then(function(d) { throw d; });
            return res.json();
        })
        .then(function() {
            cerrarModal();
            marcarResuelta(solicitudActual, accion);
        })
        .catch(function(err) {
            console.error('Error al procesar solicitud:', err);
            cerrarModal();
            /* TODO: mostrar toast de error */
        });
    }

    /* ── Marcar la card como resuelta sin recargar ── */
    function marcarResuelta(id, accion) {
        var card = document.getElementById('sol-' + id);
        if (!card) return;

        /* Quitar acciones y mostrar banner de resultado */
        var actionsEl = card.querySelector('.sol-actions');
        if (actionsEl) actionsEl.remove();

        var banner = document.createElement('div');
        banner.className = 'sol-resolved-banner ' + (accion === 'confirmar' ? 'confirmado' : 'rechazado');
        banner.innerHTML = accion === 'confirmar'
            ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 13l4 4L19 7"/></svg> Cliente confirmado como moroso — distribuidora notificada'
            : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"/></svg> Solicitud rechazada — distribuidora notificada';
        card.appendChild(banner);

        card.classList.add('sol-card-resuelta');
        card.classList.remove('urgente', 'revisar');
    }
</script>
@endpush