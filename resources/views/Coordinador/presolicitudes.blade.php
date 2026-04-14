@extends('layouts.coordinador')

@section('title', 'Presolicitudes')
@section('page-title', 'Revisión de presolicitudes')
@section('page-sub', 'Evaluación de nuevas distribuidoras · Después de inspección del verificador')

@push('styles')
<style>
    /* ── KPI row ── */
    .kpi-row { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; margin-bottom: 16px; }
    .kpi-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px; }
    .kpi-card.blue-left  { border-left: 3px solid #2563EB; border-radius: 0 10px 10px 0; }
    .kpi-card.amber-left { border-left: 3px solid #D97706; border-radius: 0 10px 10px 0; }
    .kpi-label { font-size: 10px; color: #94A3B8; text-transform: uppercase; letter-spacing: .05em; font-weight: 500; margin-bottom: 6px; }
    .kpi-value { font-size: 22px; font-weight: 500; color: #0B1F3A; font-family: 'DM Mono', monospace; margin-bottom: 4px; }
    .kpi-sub        { font-size: 11px; }
    .kpi-sub.blue   { color: #2563EB; }
    .kpi-sub.amber  { color: #D97706; }
    .kpi-sub.green  { color: #16A34A; }
    .kpi-sub.muted  { color: #94A3B8; }

    /* ── Layout 2 columnas ── */
    .layout-grid { display: grid; grid-template-columns: 1fr 360px; gap: 16px; align-items: start; }

    /* ── Tabs ── */
    .tabs-row { display: flex; padding: 0 18px; border-bottom: 1px solid #E2E8F0; }
    .tab-btn {
        padding: 10px 16px; font-size: 12px; font-weight: 500;
        color: #94A3B8; background: none; border: none;
        border-bottom: 2px solid transparent; margin-bottom: -1px;
        cursor: pointer; font-family: 'DM Sans', sans-serif; white-space: nowrap;
    }
    .tab-btn.active { color: #2563EB; border-bottom-color: #2563EB; font-weight: 600; }

    /* ── Fila de solicitud en la lista ── */
    .presol-row {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 18px; border-bottom: 1px solid #F8FAFC;
        cursor: pointer; transition: background .12s;
    }
    .presol-row:last-child { border-bottom: none; }
    .presol-row:hover { background: #F8FAFC; }
    .presol-row.selected { background: #EFF6FF; border-left: 3px solid #2563EB; padding-left: 15px; }

    .presol-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: #DBEAFE; display: flex; align-items: center;
        justify-content: center; font-size: 12px; font-weight: 600;
        color: #1D4ED8; flex-shrink: 0;
    }
    .presol-name { font-size: 13px; font-weight: 600; color: #0B1F3A; }
    .presol-sub  { font-size: 11px; color: #64748B; margin-top: 2px; }
    .presol-time { font-size: 10px; color: #94A3B8; margin-top: 3px; }
    .presol-meta { margin-left: auto; text-align: right; flex-shrink: 0; }

    /* ── Panel de detalle ── */
    .detalle-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 10px; overflow: hidden; position: sticky; top: 16px; }
    .detalle-empty {
        padding: 48px 24px; text-align: center;
        display: flex; flex-direction: column; align-items: center; gap: 10px;
    }
    .detalle-empty-icon { width: 40px; height: 40px; background: #F1F5F9; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

    /* Secciones del detalle */
    .det-section { padding: 14px 18px; border-bottom: 1px solid #F1F5F9; }
    .det-section:last-child { border-bottom: none; }
    .det-section-title { font-size: 9px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 10px; }

    /* Datos personales */
    .det-persona { display: flex; align-items: center; gap: 12px; margin-bottom: 12px; }
    .det-avatar { width: 44px; height: 44px; border-radius: 50%; background: #DBEAFE; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 700; color: #1D4ED8; flex-shrink: 0; }
    .det-nombre { font-size: 15px; font-weight: 700; color: #0B1F3A; }
    .det-curp   { font-size: 11px; color: #64748B; font-family: 'DM Mono', monospace; margin-top: 2px; }

    .det-field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .det-field { }
    .det-field-label { font-size: 9px; color: #94A3B8; text-transform: uppercase; letter-spacing: .06em; font-weight: 500; margin-bottom: 2px; }
    .det-field-val   { font-size: 12px; color: #334155; font-weight: 500; }

    /* Documentos */
    .doc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .doc-item {
        display: flex; align-items: center; gap: 8px;
        padding: 8px 10px; border-radius: 7px;
        border: 1px solid #E2E8F0; background: #F8FAFC;
        font-size: 11px; color: #334155;
    }
    .doc-item.ok     { border-color: #BBF7D0; background: #F0FDF4; color: #15803D; }
    .doc-item.falta  { border-color: #FECACA; background: #FEF2F2; color: #B91C1C; }
    .doc-icon { width: 18px; height: 18px; flex-shrink: 0; }

    /* Verificador feedback */
    .verif-box {
        background: #F8FAFC; border: 1px solid #E2E8F0;
        border-radius: 8px; padding: 12px 14px;
    }
    .verif-header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
    .verif-nombre { font-size: 12px; font-weight: 600; color: #0B1F3A; }
    .verif-fecha  { font-size: 10px; color: #94A3B8; margin-left: auto; }
    .verif-text   { font-size: 12px; color: #334155; line-height: 1.5; }
    .verif-badge  { font-size: 9px; font-weight: 600; padding: 2px 7px; border-radius: 99px; }
    .verif-badge.ok   { background: #DCFCE7; color: #15803D; }
    .verif-badge.obs  { background: #FEF3C7; color: #B45309; }

    /* Historial de la solicitud */
    .hist-item { display: flex; gap: 10px; margin-bottom: 10px; }
    .hist-item:last-child { margin-bottom: 0; }
    .hist-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
    .hist-dot.blue  { background: #2563EB; }
    .hist-dot.green { background: #16A34A; }
    .hist-dot.amber { background: #D97706; }
    .hist-dot.gray  { background: #CBD5E1; }
    .hist-accion { font-size: 12px; font-weight: 500; color: #0B1F3A; }
    .hist-meta   { font-size: 10px; color: #94A3B8; margin-top: 2px; }

    /* Acciones del detalle */
    .det-actions { padding: 14px 18px; background: #F8FAFC; border-top: 1px solid #F1F5F9; display: flex; flex-direction: column; gap: 8px; }
    .btn-aprobar {
        width: 100%; padding: 10px; border-radius: 7px; font-size: 13px; font-weight: 600;
        background: #2563EB; color: #fff; border: none; cursor: pointer;
        font-family: 'DM Sans', sans-serif; transition: background .12s;
    }
    .btn-aprobar:hover { background: #1D4ED8; }
    .btn-obs {
        width: 100%; padding: 10px; border-radius: 7px; font-size: 13px; font-weight: 600;
        background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; cursor: pointer;
        font-family: 'DM Sans', sans-serif; transition: background .12s;
    }
    .btn-obs:hover { background: #FDE68A; }
    .btn-rechazar-det {
        width: 100%; padding: 10px; border-radius: 7px; font-size: 13px; font-weight: 600;
        background: #fff; color: #64748B; border: 1px solid #E2E8F0; cursor: pointer;
        font-family: 'DM Sans', sans-serif; transition: background .12s;
    }
    .btn-rechazar-det:hover { background: #F1F5F9; }
    .det-actions-info { font-size: 10px; color: #94A3B8; text-align: center; line-height: 1.4; }

    /* Empty state */
    .empty-state { text-align: center; padding: 48px 24px; color: #94A3B8; }

    /* ── Modal ── */
    .modal-backdrop { display: none; position: fixed; inset: 0; background: rgba(11,31,58,.45); z-index: 998; align-items: center; justify-content: center; }
    .modal-backdrop.open { display: flex; }
    .modal-box { background: #fff; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; width: 460px; max-width: 95vw; }
    .modal-title { font-size: 15px; font-weight: 700; color: #0B1F3A; margin-bottom: 4px; }
    .modal-sub   { font-size: 12px; color: #64748B; margin-bottom: 16px; line-height: 1.5; }
    .modal-solicitante { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; font-weight: 600; color: #0B1F3A; }
    .modal-field-label { font-size: 11px; color: #64748B; font-weight: 500; margin-bottom: 5px; display: block; }
    .modal-textarea { width: 100%; height: 80px; border: 1px solid #E2E8F0; border-radius: 7px; padding: 8px 12px; font-size: 12px; font-family: 'DM Sans', sans-serif; color: #334155; resize: none; outline: none; margin-bottom: 14px; }
    .modal-textarea:focus { border-color: #2563EB; }
    .modal-footer { display: flex; gap: 8px; justify-content: flex-end; }
    .modal-btn { padding: 8px 18px; border-radius: 7px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .modal-btn-cancel  { background: #fff; color: #64748B; border: 1px solid #E2E8F0; }
    .modal-btn-approve { background: #2563EB; color: #fff; }
    .modal-btn-approve:hover { background: #1D4ED8; }
    .modal-btn-obs     { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
    .modal-btn-reject  { background: #FEE2E2; color: #B91C1C; border: 1px solid #FECACA; }
    .modal-alert-green { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 7px; padding: 9px 12px; font-size: 11px; color: #14532D; line-height: 1.5; margin-bottom: 14px; }
    .modal-alert-amber { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 7px; padding: 9px 12px; font-size: 11px; color: #78350F; line-height: 1.5; margin-bottom: 14px; }
</style>
@endpush

@section('content')

{{-- ── KPI ROW ── --}}
{{--
    TODO: pasar $kpis con las claves:
      - en_revision    (int) → presolicitudes en estado 'revision_coordinador'
      - aprobadas_hoy  (int) → aprobadas por el coordinador hoy
      - con_obs        (int) → con observaciones pendientes de respuesta
      - rechazadas_hoy (int) → rechazadas hoy
--}}
<div class="kpi-row">
    <div class="kpi-card blue-left">
        <div class="kpi-label">En revisión</div>
        <div class="kpi-value">{{ $kpis['en_revision'] ?? 0 }}</div>
        <div class="kpi-sub blue">Listas para evaluar</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Aprobadas hoy</div>
        <div class="kpi-value">{{ $kpis['aprobadas_hoy'] ?? 0 }}</div>
        <div class="kpi-sub green">Pasan al gerente</div>
    </div>
    <div class="kpi-card amber-left">
        <div class="kpi-label">Con observaciones</div>
        <div class="kpi-value">{{ $kpis['con_obs'] ?? 0 }}</div>
        <div class="kpi-sub amber">Esperando respuesta</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Rechazadas hoy</div>
        <div class="kpi-value">{{ $kpis['rechazadas_hoy'] ?? 0 }}</div>
        <div class="kpi-sub muted">No procedieron</div>
    </div>
</div>

{{-- ── LAYOUT: lista izquierda + detalle derecha ── --}}
<div class="layout-grid">

    {{-- ── LISTA DE PRESOLICITUDES ── --}}
    <div class="pf-card">
        <div class="pf-card-header">
            <span class="pf-card-title">Presolicitudes en revisión</span>
            <span class="pf-card-badge">{{ $kpis['en_revision'] ?? 0 }} pendiente(s)</span>
        </div>

        <div class="tabs-row">
            <button class="tab-btn active" data-tab="revision">En revisión</button>
            <button class="tab-btn" data-tab="aprobadas">Aprobadas</button>
            <button class="tab-btn" data-tab="rechazadas">Rechazadas</button>
        </div>

        {{--
            TODO: iterar $presolicitudes desde el controlador.
            Cada item necesita:
              $p->id
              $p->persona->nombre / apellido
              $p->persona->telefono
              $p->persona->fecha_nacimiento
              $p->persona->curp
              $p->persona->rfc
              $p->domicilio                   (string resumen)
              $p->estado                      ('revision_coordinador' | 'aprobado' | 'rechazado')
              $p->created_at                  (Carbon)
              $p->documentos                  (colección: tipo, url_archivo)
              $p->historial                   (colección: accion, comentario, persona, fecha)
              $p->verificador_comentario      (string, nullable — feedback del verificador)
              $p->verificador_nombre          (string)
              $p->verificador_fecha           (Carbon)
              $p->verificador_resultado       ('aprobado' | 'observacion')
              $p->dias_espera                 (int)
        --}}
        <div id="lista-presolicitudes">
            @forelse($presolicitudes ?? [] as $p)
            <div class="presol-row {{ $loop->first ? 'selected' : '' }}"
                 id="row-{{ $p->id }}"
                 onclick="seleccionar({{ $p->id }})">
                <div class="presol-avatar">
                    {{ strtoupper(substr($p->persona->nombre, 0, 1)) }}{{ strtoupper(substr($p->persona->apellido, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="presol-name">{{ $p->persona->nombre }} {{ $p->persona->apellido }}</div>
                    <div class="presol-sub">{{ $p->persona->telefono ?? '—' }}</div>
                    <div class="presol-time">Hace {{ $p->created_at->diffForHumans(null, true) }}</div>
                </div>
                <div class="presol-meta">
                    @if($p->verificador_resultado === 'aprobado')
                        <span class="pf-badge pf-badge-green">Verificador ✓</span>
                    @else
                        <span class="pf-badge pf-badge-amber">Con observaciones</span>
                    @endif
                    <div style="font-size:10px;color:#94A3B8;margin-top:4px;">
                        {{ $p->dias_espera }} día(s)
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:40px 24px;">
                Sin presolicitudes en revisión.
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── PANEL DE DETALLE ── --}}
    <div class="detalle-card" id="panel-detalle">

        {{-- Estado vacío — se oculta cuando hay selección --}}
        <div class="detalle-empty" id="detalle-vacio">
            <div class="detalle-empty-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div style="font-size:13px;font-weight:500;color:#64748B;">Selecciona una solicitud</div>
            <div style="font-size:12px;color:#94A3B8;">El detalle aparecerá aquí</div>
        </div>

        {{-- Contenido del detalle — se llena con JS --}}
        <div id="detalle-contenido" style="display:none;">

            {{-- Datos personales --}}
            <div class="det-section">
                <div class="det-section-title">Solicitante</div>
                <div class="det-persona">
                    <div class="det-avatar" id="det-avatar"></div>
                    <div>
                        <div class="det-nombre" id="det-nombre"></div>
                        <div class="det-curp" id="det-curp"></div>
                    </div>
                </div>
                <div class="det-field-grid">
                    <div class="det-field">
                        <div class="det-field-label">Teléfono</div>
                        <div class="det-field-val" id="det-tel"></div>
                    </div>
                    <div class="det-field">
                        <div class="det-field-label">RFC</div>
                        <div class="det-field-val" id="det-rfc"></div>
                    </div>
                    <div class="det-field" style="grid-column:1/-1;">
                        <div class="det-field-label">Domicilio</div>
                        <div class="det-field-val" id="det-domicilio"></div>
                    </div>
                </div>
            </div>

            {{-- Documentos --}}
            <div class="det-section">
                <div class="det-section-title">Documentos</div>
                <div class="doc-grid" id="det-docs"></div>
            </div>

            {{-- Feedback del verificador --}}
            <div class="det-section">
                <div class="det-section-title">Evaluación del verificador</div>
                <div class="verif-box">
                    <div class="verif-header">
                        <span class="verif-badge ok" id="det-verif-badge">Aprobado</span>
                        <span class="verif-nombre" id="det-verif-nombre"></span>
                        <span class="verif-fecha" id="det-verif-fecha"></span>
                    </div>
                    <div class="verif-text" id="det-verif-comentario"></div>
                </div>
            </div>

            {{-- Historial --}}
            <div class="det-section">
                <div class="det-section-title">Historial</div>
                <div id="det-historial"></div>
            </div>

            {{-- Acciones --}}
            <div class="det-actions">
                <button class="btn-aprobar" onclick="abrirModal('aprobar')">
                    Aprobar y enviar al gerente
                </button>
                <button class="btn-obs" onclick="abrirModal('observacion')">
                    Agregar observación
                </button>
                <button class="btn-rechazar-det" onclick="abrirModal('rechazar')">
                    Rechazar solicitud
                </button>
                <div class="det-actions-info">
                    Al aprobar, la solicitud pasa al gerente para su validación final.
                </div>
            </div>

        </div>
    </div>

</div>

{{-- ══════════════════════════════════════
     MODAL
══════════════════════════════════════ --}}
<div class="modal-backdrop" id="modal">
    <div class="modal-box">

        {{-- Aprobar --}}
        <div id="modal-aprobar">
            <div class="modal-title">Aprobar presolicitud</div>
            <div class="modal-sub">La solicitud pasará al gerente para su aprobación final. El solicitante no es notificado todavía.</div>
            <div class="modal-solicitante" id="m-nombre-aprobar"></div>
            <div class="modal-alert-green">
                El verificador marcó esta solicitud como aprobada. Tu aprobación la envía directamente al gerente.
            </div>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button class="modal-btn modal-btn-approve" onclick="ejecutar('aprobar')">Sí, aprobar y enviar</button>
            </div>
        </div>

        {{-- Observación --}}
        <div id="modal-observacion" style="display:none;">
            <div class="modal-title">Agregar observación</div>
            <div class="modal-sub">La distribuidora que presentó la solicitud recibirá una notificación con tu comentario.</div>
            <div class="modal-solicitante" id="m-nombre-obs"></div>
            <div class="modal-alert-amber">
                La solicitud quedará en espera hasta que la situación sea resuelta.
            </div>
            <label class="modal-field-label">Observación para la distribuidora</label>
            <textarea class="modal-textarea" id="txt-obs" placeholder="Describe qué falta o qué debe corregirse..."></textarea>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button class="modal-btn modal-btn-obs" onclick="ejecutar('observacion')">Enviar observación</button>
            </div>
        </div>

        {{-- Rechazar --}}
        <div id="modal-rechazar" style="display:none;">
            <div class="modal-title">Rechazar presolicitud</div>
            <div class="modal-sub">Esta acción notificará al solicitante que su solicitud no fue aprobada.</div>
            <div class="modal-solicitante" id="m-nombre-rechazar"></div>
            <label class="modal-field-label">Motivo del rechazo</label>
            <textarea class="modal-textarea" id="txt-rechazo" placeholder="Explica el motivo del rechazo..."></textarea>
            <div class="modal-footer">
                <button class="modal-btn modal-btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button class="modal-btn modal-btn-reject" onclick="ejecutar('rechazar')">Rechazar solicitud</button>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    var csrfToken       = '{{ csrf_token() }}';
    var solicitudActual = null;
    var accionActual    = null;

    /*
     * TODO: cuando el controlador esté listo, reemplazar este array
     * o cargándolos vía fetch al seleccionar una fila.
     *
     * Estructura esperada por cada item:
     * {
     *   id, nombre, apellido, curp, rfc, telefono, domicilio,
     *   verificador_nombre, verificador_fecha, verificador_resultado,
     *   verificador_comentario,
     *   documentos: [{ tipo, ok }],
     *   historial:  [{ color, accion, meta }]
     * }
     */
    var datos = @json($presolicitudesJson ?? []);

    /* ── Tabs ── */
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            /* TODO: filtrar lista por tab cuando se conecte el controlador */
        });
    });

    /* ── Seleccionar fila ── */
    function seleccionar(id) {
        solicitudActual = id;

        /* Marcar fila activa */
        document.querySelectorAll('.presol-row').forEach(function(r) { r.classList.remove('selected'); });
        var row = document.getElementById('row-' + id);
        if (row) row.classList.add('selected');

        var d = datos.find(function(x) { return x.id === id; });
        if (!d) {
            /* Si no hay datos en JS, mostrar el panel vacío con placeholder */
            mostrarPlaceholder(id);
            return;
        }

        llenarDetalle(d);
    }

    function mostrarPlaceholder(id) {
        document.getElementById('detalle-vacio').style.display    = 'none';
        document.getElementById('detalle-contenido').style.display = 'block';

        var iniciales = 'NA';
        var row = document.getElementById('row-' + id);
        if (row) {
            var nombre = row.querySelector('.presol-name').textContent.trim().split(' ');
            iniciales = (nombre[0]?.[0] ?? '') + (nombre[1]?.[0] ?? '');
        }

        document.getElementById('det-avatar').textContent  = iniciales.toUpperCase();
        document.getElementById('det-nombre').textContent  = row ? row.querySelector('.presol-name').textContent : '—';
        document.getElementById('det-curp').textContent    = 'CURP: —';
        document.getElementById('det-tel').textContent     = '—';
        document.getElementById('det-rfc').textContent     = '—';
        document.getElementById('det-domicilio').textContent = '—';
        document.getElementById('det-docs').innerHTML      = '<div style="grid-column:1/-1;font-size:11px;color:#94A3B8;">Documentos disponibles al conectar la BD.</div>';
        document.getElementById('det-verif-badge').textContent    = '—';
        document.getElementById('det-verif-nombre').textContent   = '—';
        document.getElementById('det-verif-fecha').textContent    = '—';
        document.getElementById('det-verif-comentario').textContent = 'Comentario del verificador disponible al conectar la BD.';
        document.getElementById('det-historial').innerHTML = '<div style="font-size:11px;color:#94A3B8;">Historial disponible al conectar la BD.</div>';
    }

    function llenarDetalle(d) {
        document.getElementById('detalle-vacio').style.display    = 'none';
        document.getElementById('detalle-contenido').style.display = 'block';

        var ini = (d.nombre?.[0] ?? '') + (d.apellido?.[0] ?? '');
        document.getElementById('det-avatar').textContent    = ini.toUpperCase();
        document.getElementById('det-nombre').textContent    = d.nombre + ' ' + d.apellido;
        document.getElementById('det-curp').textContent      = 'CURP: ' + (d.curp ?? '—');
        document.getElementById('det-tel').textContent       = d.telefono ?? '—';
        document.getElementById('det-rfc').textContent       = d.rfc ?? '—';
        document.getElementById('det-domicilio').textContent = d.domicilio ?? '—';

        /* Documentos */
        var docsHtml = '';
        (d.documentos ?? []).forEach(function(doc) {
            docsHtml += '<div class="doc-item ' + (doc.ok ? 'ok' : 'falta') + '">'
                + '<svg class="doc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
                + (doc.ok
                    ? '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                    : '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>')
                + '</svg>'
                + doc.tipo + '</div>';
        });
        document.getElementById('det-docs').innerHTML = docsHtml || '<div style="font-size:11px;color:#94A3B8;">Sin documentos registrados.</div>';

        /* Verificador */
        var badge = document.getElementById('det-verif-badge');
        badge.textContent  = d.verificador_resultado === 'aprobado' ? 'Aprobado' : 'Con observación';
        badge.className    = 'verif-badge ' + (d.verificador_resultado === 'aprobado' ? 'ok' : 'obs');
        document.getElementById('det-verif-nombre').textContent   = d.verificador_nombre ?? '—';
        document.getElementById('det-verif-fecha').textContent    = d.verificador_fecha ?? '—';
        document.getElementById('det-verif-comentario').textContent = d.verificador_comentario ?? 'Sin comentarios.';

        /* Historial */
        var histHtml = '';
        (d.historial ?? []).forEach(function(h) {
            histHtml += '<div class="hist-item">'
                + '<div class="hist-dot ' + h.color + '"></div>'
                + '<div><div class="hist-accion">' + h.accion + '</div>'
                + '<div class="hist-meta">' + h.meta + '</div></div></div>';
        });
        document.getElementById('det-historial').innerHTML = histHtml || '<div style="font-size:11px;color:#94A3B8;">Sin historial.</div>';
    }

    /* ── Modal ── */
    function abrirModal(accion) {
        if (!solicitudActual) return;
        accionActual = accion;

        var nombre = document.getElementById('det-nombre').textContent;
        ['aprobar','observacion','rechazar'].forEach(function(a) {
            document.getElementById('modal-' + a).style.display = a === accion ? 'block' : 'none';
        });

        var mNombre = document.getElementById('m-nombre-' + accion);
        if (mNombre) mNombre.textContent = nombre;

        document.getElementById('modal').classList.add('open');
    }

    function cerrarModal() {
        document.getElementById('modal').classList.remove('open');
        accionActual = null;
    }

    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    /* ── Ejecutar acción ── */
    function ejecutar(accion) {
        if (!solicitudActual) return;

        var payload = { presolicitud_id: solicitudActual, accion: accion };

        if (accion === 'observacion') payload.comentario = document.getElementById('txt-obs').value;
        if (accion === 'rechazar')    payload.comentario = document.getElementById('txt-rechazo').value;

        /* TODO: cuando la ruta esté lista, reemplazar la URL por:
           route('coordinador.presolicitudes.resolver') */
        fetch('/coordinador/presolicitudes/resolver', {
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
            /* Quitar la fila de la lista y limpiar el panel */
            var row = document.getElementById('row-' + solicitudActual);
            if (row) row.remove();
            document.getElementById('detalle-vacio').style.display    = 'flex';
            document.getElementById('detalle-contenido').style.display = 'none';
            solicitudActual = null;
        })
        .catch(function(err) {
            console.error('Error al procesar presolicitud:', err);
            cerrarModal();
        });
    }

    /* ── Init: seleccionar primera fila si existe ── */
    var primeraFila = document.querySelector('.presol-row');
    if (primeraFila) {
        var primeraId = primeraFila.id.replace('row-', '');
        seleccionar(parseInt(primeraId));
    }
</script>
@endpush