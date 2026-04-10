@extends('layouts.app')

@section('title', 'Dashboard — Gerente')

{{-- Importante: Estas secciones activan los encabezados de tu layout/app.blade.php --}}
@section('page-title', 'Dashboard')
@section('page-sub', 'Resumen general de la sucursal')

@push('styles')
<style>
    /* Quitamos el min-height 100vh para que no tape el layout */
    .page-wrapper { font-family: 'DM Sans', sans-serif; padding-top: 10px; }

    /* ── Topbar (Ajustado para no duplicar si el layout ya trae uno) ── */
    .topbar { padding: 10px 32px 16px 32px; display: flex; align-items: flex-end; justify-content: space-between; }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-sub   { font-size: .82rem; color: #64748B; margin-top: 3px; }
    .topbar-date  { font-size: .82rem; color: #94A3B8; }

    /* ── KPI Row ── */
    .kpi-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; padding: 0 32px 20px 32px; }
    .kpi-card { background: #fff; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 18px 20px; }
    .kpi-card.blue-left { border-left: 4px solid #2563EB; border-radius: 0 12px 12px 0; }
    .kpi-card.red-left  { border-left: 4px solid #DC2626; border-radius: 0 12px 12px 0; }
    .kpi-label { font-size: .70rem; font-weight: 700; letter-spacing: .08em; color: #94A3B8; text-transform: uppercase; margin-bottom: 8px; }
    .kpi-value { font-family: 'DM Mono', monospace; font-size: 2rem; font-weight: 500; color: #0B1F3A; margin-bottom: 4px; }
    .kpi-sub        { font-size: .78rem; font-weight: 500; }
    .kpi-sub.green  { color: #16A34A; }
    .kpi-sub.red    { color: #DC2626; }
    .kpi-sub.blue   { color: #2563EB; }
    .kpi-sub.muted  { color: #94A3B8; }

    /* ── Grids ── */
    .content-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 0 32px 20px 32px; }
    .content-grid.three-col { grid-template-columns: 2fr 1fr 1fr; }

    /* ── Cards ── */
    .main-card { background: #fff; border: 1.5px solid #E2E8F0; border-radius: 14px; overflow: hidden; }
    .card-header-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 22px; border-bottom: 1px solid #F1F5F9; }
    .card-header-title  { font-size: .95rem; font-weight: 700; color: #0B1F3A; }
    .card-header-action { font-size: .80rem; color: #2563EB; font-weight: 600; text-decoration: none; }
    .card-header-action:hover { text-decoration: underline; }

    /* ── Tabla ── */
    .pf-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
    .pf-table th { padding: 9px 22px; text-align: left; color: #94A3B8; font-weight: 700; font-size: .68rem; text-transform: uppercase; letter-spacing: .06em; border-bottom: 1px solid #F1F5F9; white-space: nowrap; }
    .pf-table td { padding: 13px 22px; color: #334155; border-bottom: 1px solid #F8FAFC; vertical-align: middle; }
    .pf-table tr:last-child td { border-bottom: none; }
    .pf-table tr:hover td { background: #F8FAFC; }
    .dist-name { font-size: .92rem; font-weight: 700; color: #0B1F3A; }
    .dist-sub  { font-size: .75rem; color: #94A3B8; margin-top: 2px; }
    .mono { font-family: 'DM Mono', monospace; }

    /* ── Badges ── */
    .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 99px; font-size: .72rem; font-weight: 700; }
    .badge-green { background: #DCFCE7; color: #15803D; }
    .badge-red   { background: #FEE2E2; color: #B91C1C; }
    .badge-amber { background: #FEF3C7; color: #B45309; }
    .badge-blue  { background: #DBEAFE; color: #1D4ED8; }
    .badge-gray  { background: #F1F5F9; color: #475569; }
    .cat-oro     { background: #FEF3C7; color: #92400E; }
    .cat-plata   { background: #F1F5F9; color: #475569; }
    .cat-cobre   { background: #FFEDD5; color: #9A3412; }

    /* ── Links tabla ── */
    .tbl-link { font-size: .78rem; font-weight: 600; color: #2563EB; cursor: pointer; background: none; border: none; font-family: 'DM Sans', sans-serif; padding: 0; text-decoration: none; }
    .tbl-link:hover { text-decoration: underline; }
    .tbl-link.danger { color: #DC2626; }
    .tbl-link.muted  { color: #94A3B8; cursor: default; pointer-events: none; }

    /* ── Barras ── */
    .bar-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .bar-row:last-child { margin-bottom: 0; }
    .bar-label { font-size: .80rem; color: #334155; width: 130px; flex-shrink: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .bar-track { flex: 1; height: 7px; background: #F1F5F9; border-radius: 4px; overflow: hidden; }
    .bar-fill  { height: 100%; border-radius: 4px; background: #2563EB; }
    .bar-fill.amber { background: #F59E0B; }
    .bar-fill.red   { background: #DC2626; }
    .bar-val { font-family: 'DM Mono', monospace; font-size: .80rem; color: #0B1F3A; width: 60px; text-align: right; flex-shrink: 0; }

    /* ── Stat 2×2 ── */
    .stat-grid { display: grid; grid-template-columns: 1fr 1fr; }
    .stat-cell { padding: 14px 18px; border-right: 1px solid #F1F5F9; border-bottom: 1px solid #F1F5F9; }
    .stat-cell:nth-child(even)              { border-right: none; }
    .stat-cell:nth-child(3), .stat-cell:nth-child(4) { border-bottom: none; }
    .stat-label { font-size: .68rem; color: #94A3B8; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
    .stat-val   { font-family: 'DM Mono', monospace; font-size: 1.5rem; font-weight: 500; color: #0B1F3A; margin-top: 4px; }
    .stat-val.red   { color: #DC2626; }
    .stat-val.amber { color: #D97706; }

    /* ── Notificaciones ── */
    .notif-item { display: flex; align-items: flex-start; gap: 10px; padding: 12px 18px; border-bottom: 1px solid #F8FAFC; }
    .notif-item:last-child { border-bottom: none; }
    .notif-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 4px; }
    .notif-dot.red   { background: #DC2626; }
    .notif-dot.green { background: #16A34A; }
    .notif-dot.blue  { background: #2563EB; }
    .notif-dot.amber { background: #D97706; }
    .notif-msg  { font-size: .84rem; color: #334155; line-height: 1.4; }
    .notif-time { font-size: .74rem; color: #94A3B8; margin-top: 2px; }

    /* ── Presolicitudes ── */
    .presol-row { display: flex; align-items: center; justify-content: space-between; padding: 11px 18px; border-bottom: 1px solid #F8FAFC; }
    .presol-row:last-child { border-bottom: none; }
    .presol-name { font-size: .88rem; font-weight: 600; color: #0B1F3A; }
    .presol-time { font-size: .74rem; color: #94A3B8; margin-top: 2px; }

    /* ── Botones ── */
    .btn-outline { padding: 8px 16px; border: 1.5px solid #E2E8F0; border-radius: 8px; background: #fff; color: #334155; font-size: .84rem; font-weight: 600; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .btn-outline:hover { background: #F8FAFC; }

    /* ── Empty ── */
    .empty-state { text-align: center; color: #94A3B8; padding: 32px; font-size: .85rem; }
</style>
@endpush

@section('content')
<div class="page-wrapper">

    {{-- TOPBAR INTERNO (Opcional si tu layout ya tiene uno) --}}
    <div class="topbar">
        <div>
            <h1 class="topbar-title">Resumen Ejecutivo</h1>
            <div class="topbar-sub">Estado actual de las operaciones</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <span class="topbar-date">{{ now()->isoFormat('dddd D [de] MMMM, YYYY') }}</span>
            <button class="btn-outline">Exportar reporte</button>
        </div>
    </div>

    {{-- KPI ROW --}}
    <div class="kpi-row">
        <div class="kpi-card blue-left">
            <div class="kpi-label">Cartera activa</div>
            <div class="kpi-value mono">${{ number_format($kpis['cartera_activa'] ?? 0, 0, '.', ',') }}</div>
            <div class="kpi-sub blue">{{ $kpis['distribuidoras_activas'] ?? 0 }} distribuidoras activas</div>
        </div>

        <div class="kpi-card red-left">
            <div class="kpi-label">Cartera morosa</div>
            <div class="kpi-value mono">${{ number_format($kpis['cartera_morosa'] ?? 0, 0, '.', ',') }}</div>
            <div class="kpi-sub red">{{ $kpis['distribuidoras_morosas'] ?? 0 }} en mora</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Cortes pendientes</div>
            <div class="kpi-value mono">{{ $kpis['cortes_pendientes'] ?? 0 }}</div>
            <div class="kpi-sub {{ ($kpis['cortes_vencidos'] ?? 0) > 0 ? 'red' : 'green' }}">
                {{ $kpis['cortes_vencidos'] ?? 0 }} vencidos hoy
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Puntos en circulación</div>
            <div class="kpi-value mono">{{ number_format($kpis['puntos_total'] ?? 0, 0, '.', ',') }}</div>
            <div class="kpi-sub muted">= ${{ number_format(($kpis['puntos_total'] ?? 0) * ($kpis['valor_punto'] ?? 2), 0, '.', ',') }} en pts</div>
        </div>
    </div>

    {{-- FILA 1 --}}
    <div class="content-grid">
        <div class="main-card">
            <div class="card-header-row">
                <span class="card-header-title">Comportamiento de pagos</span>
                <a href="#" class="card-header-action">Ver historial →</a>
            </div>
            <table class="pf-table">
                <thead>
                    <tr>
                        <th>Distribuidora</th>
                        <th>Categoría</th>
                        <th>A tiempo</th>
                        <th>Anticipados</th>
                        <th>Tardíos</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distribuidoras ?? [] as $d)
                    <tr>
                        <td>
                            <div class="dist-name">{{ $d->persona->nombre }} {{ $d->persona->apellido }}</div>
                            <div class="dist-sub">Ref: {{ $d->referencia_actual ?? '—' }}</div>
                        </td>
                        <td>
                            <span class="badge 
                                {{ $d->categoria->nombre === 'Oro'   ? 'cat-oro'   : '' }}
                                {{ $d->categoria->nombre === 'Plata' ? 'cat-plata' : '' }}
                                {{ $d->categoria->nombre === 'Cobre' ? 'cat-cobre' : '' }}">
                                {{ $d->categoria->nombre }} {{ $d->categoria->porcentaje_comision }}%
                            </span>
                        </td>
                        <td class="mono" style="color:#16A34A;">{{ $d->pagos_tiempo }}/{{ $d->pagos_total }}</td>
                        <td class="mono" style="color:#2563EB;">{{ $d->pagos_anticipados }}</td>
                        <td class="mono" style="color:{{ $d->pagos_tardios > 0 ? '#DC2626' : '#94A3B8' }};">{{ $d->pagos_tardios }}</td>
                        <td>
                            <span class="badge {{ $d->estado === 'activa' ? 'badge-green' : ($d->estado === 'morosa' ? 'badge-red' : 'badge-gray') }}">
                                {{ ucfirst($d->estado) }}
                            </span>
                        </td>
                        <td>
                            @if($d->estado === 'activa' && $d->categoria->nombre !== 'Oro')
                                <a href="#" class="tbl-link">Subir categoría</a>
                            @elseif($d->estado === 'morosa')
                                <a href="#" class="tbl-link danger">Gestionar mora</a>
                            @else
                                <span class="tbl-link muted">Máx. categoría</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="empty-state">Sin datos disponibles.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="main-card">
                <div class="card-header-row"><span class="card-header-title">Morosidad global</span></div>
                <div class="stat-grid">
                    <div class="stat-cell"><div class="stat-label">Dist. morosas</div><div class="stat-val red">{{ $morosidad['distribuidoras'] ?? 0 }}</div></div>
                    <div class="stat-cell"><div class="stat-label">Monto en mora</div><div class="stat-val red">${{ number_format($morosidad['monto'] ?? 0, 0, '.', ',') }}</div></div>
                    <div class="stat-cell"><div class="stat-label">Clientes morosos</div><div class="stat-val amber">{{ $morosidad['clientes'] ?? 0 }}</div></div>
                    <div class="stat-cell"><div class="stat-label">% cartera riesgo</div><div class="stat-val amber">{{ number_format($morosidad['porcentaje'] ?? 0, 1) }}%</div></div>
                </div>
            </div>

            <div class="main-card">
                <div class="card-header-row"><span class="card-header-title">Notificaciones</span><a href="#" class="card-header-action">Ver todas →</a></div>
                @forelse($notificaciones ?? [] as $notif)
                <div class="notif-item">
                    <div class="notif-dot {{ $notif->tipo === 'mora' ? 'red' : ($notif->tipo === 'pago' ? 'green' : ($notif->tipo === 'presolicitud' ? 'blue' : 'amber')) }}"></div>
                    <div>
                        <div class="notif-msg">{{ $notif->mensaje }}</div>
                        <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state">Sin notificaciones recientes.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- FILA 2 --}}
    <div class="content-grid three-col" style="padding-bottom:32px;">
        <div class="main-card">
            <div class="card-header-row"><span class="card-header-title">Saldo de cortes activos</span></div>
            <div style="padding:16px 22px;">
                @forelse($saldo_cortes ?? [] as $sc)
                <div class="bar-row">
                    <span class="bar-label">{{ $sc->nombre }}</span>
                    <div class="bar-track"><div class="bar-fill {{ $sc->estado === 'morosa' ? 'red' : '' }}" style="width:{{ $sc->porcentaje }}%"></div></div>
                    <span class="bar-val">${{ number_format($sc->total_deuda, 0, '.', ',') }}</span>
                </div>
                @empty
                <p class="empty-state">Sin cortes activos.</p>
                @endforelse
            </div>
        </div>

        <div class="main-card">
            <div class="card-header-row"><span class="card-header-title">Saldo de puntos</span></div>
            <div style="padding:16px 22px;">
                @forelse($saldo_puntos ?? [] as $sp)
                <div class="bar-row">
                    <span class="bar-label">{{ $sp->nombre }}</span>
                    <div class="bar-track"><div class="bar-fill amber" style="width:{{ $sp->porcentaje }}%"></div></div>
                    <span class="bar-val">{{ number_format($sp->puntos_saldo, 0, '.', ',') }}</span>
                </div>
                @empty
                <p class="empty-state">Sin registros.</p>
                @endforelse
            </div>
        </div>

        <div class="main-card">
            <div class="card-header-row"><span class="card-header-title">Presolicitudes</span><a href="#" class="card-header-action">Revisar →</a></div>
            @forelse($presolicitudes ?? [] as $ps)
            <div class="presol-row">
                <div>
                    <div class="presol-name">{{ $ps->persona->nombre }} {{ $ps->persona->apellido }}</div>
                    <div class="presol-time">Hace {{ $ps->created_at->diffForHumans(null, true) }}</div>
                </div>
                <span class="badge {{ $ps->estado === 'revision_verificador' ? 'badge-blue' : ($ps->estado === 'revision_coordinador' ? 'badge-amber' : 'badge-gray') }}">
                    {{ ucfirst(str_replace('_', ' ', $ps->estado)) }}
                </span>
            </div>
            @empty
            <div class="empty-state">Sin pendientes.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection