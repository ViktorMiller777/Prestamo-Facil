@extends('layouts.app')

@section('title', 'Mi estado de cuenta')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar ── */
    .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 24px 32px 16px 32px;
    }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-sub   { font-size: .82rem; color: #64748B; margin-top: 3px; }
    .btn-pdf {
        padding: 10px 22px;
        background: #fff;
        color: #0B1F3A;
        border: 1.5px solid #E2E8F0;
        border-radius: 9px;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-pdf:hover { background: #F8FAFC; }

    /* ── Main card ── */
    .main-card {
        margin: 0 32px 32px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }

    /* ── Banner oscuro ── */
    .banner {
        background: #0B1F3A;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .banner-corte { font-size: .70rem; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 5px; }
    .banner-name  { font-size: 1.15rem; font-weight: 700; color: #fff; margin-bottom: 4px; }
    .banner-addr  { font-size: .80rem; color: rgba(255,255,255,0.4); }
    .banner-right { text-align: right; }
    .banner-ref-label { font-size: .70rem; color: rgba(255,255,255,0.35); margin-bottom: 4px; }
    .banner-ref   { font-family: 'DM Mono', monospace; font-size: .95rem; color: #60A5FA; letter-spacing: .05em; }
    .banner-dates { font-size: .75rem; color: rgba(255,255,255,0.3); margin-top: 5px; }

    /* ── Stats row ── */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); border-bottom: 1px solid #F1F5F9; }
    .stat-cell { padding: 16px 18px; border-right: 1px solid #F1F5F9; }
    .stat-cell:last-child { border-right: none; }
    .stat-label { font-size: .68rem; color: #94A3B8; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; font-weight: 700; }
    .stat-val { font-size: 1.15rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #0B1F3A; }
    .stat-val.accent { color: #2563EB; }
    .stat-val.green  { color: #16A34A; }
    .stat-val.danger { color: #DC2626; }
    .stat-sub { font-size: .75rem; color: #94A3B8; margin-top: 3px; }

    /* ── Barra crédito ── */
    .credito-bar-wrap { padding: 14px 20px; border-bottom: 1px solid #F1F5F9; }
    .credito-bar-labels { display: flex; justify-content: space-between; font-size: .82rem; margin-bottom: 7px; }
    .credito-bar { height: 8px; background: #F1F5F9; border-radius: 99px; overflow: hidden; }
    .credito-fill { height: 100%; border-radius: 99px; background: #2563EB; }

    /* ── Info banner ── */
    .info-banner {
        background: #EFF6FF;
        border-bottom: 1px solid #DBEAFE;
        padding: 12px 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: .82rem;
        color: #1D4ED8;
        line-height: 1.6;
    }

    /* ── Tabla ── */
    .pf-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
    .pf-table th {
        padding: 10px 18px; text-align: left;
        color: #94A3B8; font-weight: 700; font-size: .68rem;
        text-transform: uppercase; letter-spacing: .06em;
        border-bottom: 1px solid #F1F5F9;
    }
    .pf-table td { padding: 12px 18px; color: #334155; border-bottom: 1px solid #F8FAFC; vertical-align: middle; }
    .pf-table tr:last-child td { border-bottom: none; }
    .pf-table tr:hover td { background: #F8FAFC; }
    .row-warn td { background: #FFFBEB; }
    .tfoot-row td { background: #F8FAFC; font-weight: 700; border-top: 1px solid #E2E8F0; }

    .badge-blue { background: #DBEAFE; color: #1D4ED8; padding: 3px 10px; border-radius: 99px; font-size: .75rem; font-weight: 700; }
    .mono { font-family: 'DM Mono', monospace; }

    /* ── Total bar ── */
    .total-bar {
        background: #0B1F3A;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .total-label { font-size: .78rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: .05em; }
    .total-hint  { font-size: .75rem; color: rgba(255,255,255,0.3); margin-top: 3px; }
    .total-val   { font-size: 1.6rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #fff; }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <div>
            <h1 class="topbar-title">Mi estado de cuenta</h1>
            {{-- TODO: mostrar número de corte activo desde BD --}}
            <div class="topbar-sub">Corte — activo</div>
        </div>
        
    </div>

    {{-- ── MAIN CARD ── --}}
    <div class="main-card">

        {{-- Banner oscuro --}}
        <div class="banner">
            <div>
                {{-- TODO: traer datos del corte desde BD --}}
                <div class="banner-corte">Relación de pago — corte</div>
                <div class="banner-name"></div>
                <div class="banner-addr"></div>
            </div>
            <div class="banner-right">
                <div class="banner-ref-label">Tu referencia de pago</div>
                {{-- TODO: traer referencia desde BD --}}
                <div class="banner-ref"></div>
                <div class="banner-dates"></div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="stats-row">
            <div class="stat-cell">
                <div class="stat-label">Límite de crédito</div>
                {{-- TODO: traer de BD --}}
                <div class="stat-val"></div>
                <div class="stat-sub">Tu límite total</div>
            </div>
            <div class="stat-cell">
                <div class="stat-label">Crédito disponible</div>
                {{-- TODO: traer de BD --}}
                <div class="stat-val accent"></div>
                <div class="stat-sub">Puedes usar ahora</div>
            </div>
            <div class="stat-cell">
                <div class="stat-label">Puntos acumulados</div>
                {{-- TODO: traer de BD --}}
                <div class="stat-val green"></div>
                <div class="stat-sub"></div>
            </div>
            <div class="stat-cell">
                <div class="stat-label">Lo que debes pagar</div>
                {{-- TODO: traer de BD --}}
                <div class="stat-val danger"></div>
                <div class="stat-sub">Este corte</div>
            </div>
        </div>

        {{-- Barra de crédito --}}
        <div class="credito-bar-wrap">
            <div class="credito-bar-labels">
                {{-- TODO: calcular desde BD --}}
                <span style="color:#64748B;">Crédito usado: <strong style="color:#0B1F3A;"></strong></span>
                <span style="color:#2563EB;">Disponible: <strong></strong></span>
            </div>
            {{-- TODO: calcular porcentaje de uso desde BD --}}
            <div class="credito-bar"><div class="credito-fill" style="width:0%;"></div></div>
        </div>

        {{-- Info banner --}}
        <div class="info-banner">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2" style="flex-shrink:0;margin-top:2px;">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            La tabla muestra los vales que hiciste a tus clientes este corte. Lo que ellos no pagaron genera recargos que se suman a tu deuda con Préstamo Fácil.
        </div>

        {{-- Tabla de vales --}}
        <table class="pf-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producto</th>
                    <th>Tu cliente</th>
                    <th>Pagos de tu cliente</th>
                    <th>Tu comisión</th>
                    <th>Pago base</th>
                    <th>Recargos</th>
                    <th>Tu total</th>
                </tr>
            </thead>
            <tbody>
                {{-- TODO: iterar vales del corte desde BD --}}
                {{--
                @foreach($vales as $vale)
                <tr class="{{ $vale->con_recargo ? 'row-warn' : '' }}">
                    <td class="mono" style="color:#94A3B8;">{{ $loop->iteration }}</td>
                    <td><span class="badge-blue">{{ $vale->producto }}</span></td>
                    <td style="font-weight:600;color:#0B1F3A;">{{ $vale->cliente }}</td>
                    <td class="mono" style="color:{{ $vale->pagos_ok ? '#16A34A' : '#D97706' }};">{{ $vale->pagos_realizados }}/{{ $vale->pagos_total }}</td>
                    <td class="mono" style="color:#16A34A;">+${{ $vale->comision }}</td>
                    <td class="mono">${{ $vale->pago_base }}</td>
                    <td class="mono" style="color:{{ $vale->recargo > 0 ? '#DC2626' : '#94A3B8' }};">${{ $vale->recargo }}</td>
                    <td class="mono" style="font-weight:700;color:{{ $vale->recargo > 0 ? '#DC2626' : '#0B1F3A' }};">${{ $vale->total }}</td>
                </tr>
                @endforeach
                --}}
                <tr class="tfoot-row">
                    <td colspan="4" style="text-align:right;color:#94A3B8;font-size:.70rem;text-transform:uppercase;letter-spacing:.05em;">Totales</td>
                    {{-- TODO: calcular totales desde BD --}}
                    <td class="mono" style="color:#16A34A;"></td>
                    <td class="mono"></td>
                    <td class="mono" style="color:#DC2626;"></td>
                    <td class="mono" style="color:#0B1F3A;"></td>
                </tr>
            </tbody>
        </table>

        {{-- Total bar --}}
        <div class="total-bar">
            <div>
                <div class="total-label">Lo que debes depositar este corte</div>
                {{-- TODO: traer referencia y fecha desde BD --}}
                <div class="total-hint">Referencia: &nbsp;·&nbsp; Límite:</div>
            </div>
            {{-- TODO: traer monto total desde BD --}}
            <div class="total-val"></div>
        </div>

    </div>

</div>

@endsection