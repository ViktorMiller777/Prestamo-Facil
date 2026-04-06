@extends('layouts.app')

@section('title', 'Bandeja de presolicitudes')

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
    .topbar-date  { font-size: .82rem; color: #64748B; margin-top: 3px; }
    .topbar-actions { display: flex; gap: 10px; align-items: center; }
    .btn-tb {
        padding: 9px 20px;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background .15s, border-color .15s;
    }
    .btn-tb-outline { background: #fff; color: #0B1F3A; border: 1.5px solid #E2E8F0; }
    .btn-tb-outline:hover { border-color: #CBD5E1; }
    .btn-tb-primary { background: #2563EB; color: #fff; border: 1.5px solid #2563EB; }
    .btn-tb-primary:hover { background: #1D4ED8; }

    /* ── KPI Row ── */
    .kpi-row { display: flex; gap: 16px; padding: 0 32px 20px 32px; }
    .kpi-card {
        flex: 1;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        padding: 18px 20px 16px 20px;
        min-width: 0;
    }
    .kpi-card.bordered-left { border-left: 4px solid #2563EB; }
    .kpi-label {
        font-size: .70rem;
        font-weight: 700;
        letter-spacing: .08em;
        color: #94A3B8;
        text-transform: uppercase;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .kpi-value {
        font-family: 'DM Mono', monospace;
        font-size: 2.1rem;
        font-weight: 500;
        color: #0B1F3A;
        line-height: 1;
        margin-bottom: 8px;
    }
    .kpi-sub { font-size: .78rem; font-weight: 500; }
    .kpi-sub.green  { color: #16A34A; }
    .kpi-sub.blue   { color: #2563EB; }
    .kpi-sub.orange { color: #D97706; }

    /* ── Main Card ── */
    .main-card {
        margin: 0 32px 32px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }

    /* ── Card Header ── */
    .card-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 0 22px;
    }
    .card-header-title { font-size: 1rem; font-weight: 700; color: #0B1F3A; }
    .card-header-actions { display: flex; gap: 8px; align-items: center; }

    .btn-icon {
        width: 36px; height: 36px;
        border-radius: 8px;
        border: 1.5px solid #E2E8F0;
        background: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        transition: background .12s;
    }
    .btn-icon:hover { background: #F0F2F7; }

    .btn-select {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        background: #0B1F3A;
        color: #fff;
        font-size: .83rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
    }

    /* ── Tabs ── */
    .tabs-row {
        display: flex;
        padding: 14px 22px 0 22px;
        border-bottom: 1.5px solid #E2E8F0;
        gap: 4px;
    }
    .tab-btn {
        padding: 8px 16px 10px 16px;
        font-size: .86rem;
        font-weight: 500;
        color: #64748B;
        background: none;
        border: none;
        border-bottom: 2.5px solid transparent;
        margin-bottom: -1.5px;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: color .15s;
        white-space: nowrap;
    }
    .tab-btn.active { color: #2563EB; border-bottom-color: #2563EB; font-weight: 700; }
    .tab-btn:hover:not(.active) { color: #0B1F3A; }

    /* ── Table ── */
    .presol-table { width: 100%; border-collapse: collapse; }
    .presol-table thead th {
        font-size: .70rem;
        font-weight: 700;
        letter-spacing: .08em;
        color: #94A3B8;
        text-transform: uppercase;
        padding: 14px 22px;
        text-align: left;
        background: #fff;
        border-bottom: 1.5px solid #E2E8F0;
    }
    .presol-table tbody tr {
        border-bottom: 1px solid #F1F5F9;
        transition: background .12s;
        cursor: pointer;
    }
    .presol-table tbody tr:last-child { border-bottom: none; }
    .presol-table tbody tr:hover { background: #F8FAFC; }
    .presol-table tbody td { padding: 18px 22px; vertical-align: middle; }

    /* ── Folio ── */
    .folio {
        font-family: 'DM Mono', monospace;
        font-size: .82rem;
        color: #94A3B8;
        font-weight: 500;
    }

    /* ── Interesada ── */
    .name { font-size: .92rem; font-weight: 600; color: #0B1F3A; line-height: 1.3; }
    .city { font-size: .78rem; color: #94A3B8; margin-top: 2px; }

    /* ── Fecha ── */
    .fecha { font-size: .88rem; color: #475569; font-weight: 500; }

    /* ── Progress bar expediente ── */
    .exp-cell { display: flex; align-items: center; gap: 10px; min-width: 150px; }
    .progress-bar-wrap {
        flex: 1;
        height: 6px;
        background: #E2E8F0;
        border-radius: 99px;
        overflow: hidden;
    }
    .progress-bar-fill { height: 100%; border-radius: 99px; }
    .progress-bar-fill.full    { background: #16A34A; }
    .progress-bar-fill.partial { background: #2563EB; }
    .progress-bar-fill.low     { background: #D97706; }
    .docs-count {
        font-size: .78rem;
        color: #64748B;
        font-weight: 500;
        white-space: nowrap;
        font-family: 'DM Mono', monospace;
    }

    /* ── Badges domicilio ── */
    .badge-dom {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 99px;
        font-size: .78rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-dom .dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }
    .badge-dom.pendiente  { background: #FEF3C7; color: #92400E; }
    .badge-dom.pendiente .dot  { background: #D97706; }
    .badge-dom.verificado { background: #DCFCE7; color: #166534; }
    .badge-dom.verificado .dot { background: #16A34A; }
    .badge-dom.sin-verif  { background: #FEE2E2; color: #991B1B; }
    .badge-dom.sin-verif .dot  { background: #EF4444; }
    .badge-dom.problema   { background: #FEE2E2; color: #991B1B; }
    .badge-dom.problema .dot   { background: #EF4444; }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <div>
            <h1 class="topbar-title">Bandeja de presolicitudes</h1>
            <div class="topbar-date">
                {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM, YYYY') }}
            </div>
        </div>
        <div class="topbar-actions">
            <button class="btn-tb btn-tb-outline">Exportar</button>
            <button class="btn-tb btn-tb-outline">Nueva presolicitud</button>
            <button class="btn-tb btn-tb-primary">Asignar verificador</button>
        </div>
    </div>

    {{-- ── KPI ROW ── --}}
    <div class="kpi-row">
        <div class="kpi-card bordered-left">
            <div class="kpi-label">Total<br>pendientes</div>
            <div class="kpi-value"></div>
            <div class="kpi-sub blue">Por revisar hoy</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">En<br>revisión</div>
            <div class="kpi-value"></div>
            <div class="kpi-sub blue">Expediente incompleto</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Aprobadas<br>este mes</div>
            <div class="kpi-value"></div>
            <div class="kpi-sub green">+3 vs mes anterior</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Rechazadas</div>
            <div class="kpi-value"></div>
            <div class="kpi-sub orange">Buró o domicilio</div>
        </div>
    </div>

    {{-- ── MAIN TABLE CARD ── --}}
    <div class="main-card">

        {{-- Header --}}
        <div class="card-header-row">
            <span class="card-header-title">Interesadas pendientes de validación</span>
            <div class="card-header-actions">
                <button class="btn-icon" title="Filtrar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
                        <line x1="4" y1="6"  x2="20" y2="6"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                        <line x1="11" y1="18" x2="13" y2="18"/>
                    </svg>
                </button>
               
            </div>
        </div>

        {{-- Tabs --}}
        <div class="tabs-row">
            <button class="tab-btn active" data-tab="todas">Todas (eje)</button>
            <button class="tab-btn" data-tab="pendiente">Pendiente </button>
            <button class="tab-btn" data-tab="revision">En revisión </button>
            <button class="tab-btn" data-tab="aprobadas">Aprobadas</button>
            <button class="tab-btn" data-tab="rechazadas">Rechazadas</button>
        </div>

        {{-- Tabla --}}
        <table class="presol-table">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Interesado</th>
                    <th>Fecha de solicitud</th>
                    <th>Expediente</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>

                {{-- PS-0081 --}}
                <tr onclick="window.location='{{ route('verificador.expediente', ['folio' => 'PS-0081']) }}'">
                    <td><span class="folio">foli</span></td>
                    <td>
                        <div class="name">An</div>
                        <div class="city">Tor.</div>
                    </td>
                    <td><span class="fecha"></span></td>
                    <td>
                        <div class="exp-cell">
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill partial" style="width:75%"></div>
                            </div>
                            <span class="docs-count">3/4 docs</span>
                        </div>
                    </td>
                    <td><span class="badge-dom pendiente"><span class="dot"></span>Pendiente</span></td>
                </tr>

                {{-- PS-0082 --}}
                <tr onclick="window.location='{{ route('verificador.expediente', ['folio' => 'PS-0082']) }}'">
                    <td><span class="folio">PS-0082</span></td>
                    <td>
                        <div class="name">G</div>
                        <div class="city">.</div>
                    </td>
                    <td><span class="fecha">3</span></td>
                    <td>
                        <div class="exp-cell">
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill full" style="width:100%"></div>
                            </div>
                            <span class="docs-count">4/4 docs</span>
                        </div>
                    </td>
                    <td><span class="badge-dom verificado"><span class="dot"></span>Verificado</span></td>
                </tr>

                {{-- PS-0083 --}}
                <tr onclick="window.location='{{ route('verificador.expediente', ['folio' => 'PS-0083']) }}'">
                    <td><span class="folio">PS-0083</span></td>
                    <td>
                        <div class="name">d</div>
                        <div class="city">Tor</div>
                    </td>
                    <td><span class="fecha">30 mar 2026</span></td>
                    <td>
                        <div class="exp-cell">
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill low" style="width:25%"></div>
                            </div>
                            <span class="docs-count">1/4 docs</span>
                        </div>
                    </td>
                    <td><span class="badge-dom sin-verif"><span class="dot"></span>Sin verificar</span></td>
                </tr>

                {{-- PS-0079 --}}
                <tr onclick="window.location='{{ route('verificador.expediente', ['folio' => 'PS-0079']) }}'">
                    <td><span class="folio">PS-0079</span></td>
                    <td>
                        <div class="name">S</div>
                        <div class="city">.</div>
                    </td>
                    <td><span class="fecha"></span></td>
                    <td>
                        <div class="exp-cell">
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill full" style="width:100%"></div>
                            </div>
                            <span class="docs-count">4/4 docs</span>
                        </div>
                    </td>
                    <td><span class="badge-dom verificado"><span class="dot"></span>Verificado</span></td>
                </tr>

                {{-- PS-0077 --}}
                <tr onclick="window.location='{{ route('verificador.expediente', ['folio' => 'PS-0077']) }}'">
                    <td><span class="folio">PS-0077</span></td>
                    <td>
                        <div class="name"></div>
                        <div class="city">.</div>
                    </td>
                    <td><span class="fecha"></span></td>
                    <td>
                        <div class="exp-cell">
                            <div class="progress-bar-wrap">
                                <div class="progress-bar-fill full" style="width:100%"></div>
                            </div>
                            <span class="docs-count">4/4 docs</span>
                        </div>
                    </td>
                    <td><span class="badge-dom problema"><span class="dot"></span>Problema buró</span></td>
                </tr>

            </tbody>
        </table>

    </div>{{-- /main-card --}}

</div>{{-- /page-wrapper --}}

<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>

@endsection