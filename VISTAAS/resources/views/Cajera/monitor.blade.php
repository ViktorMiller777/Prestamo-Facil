@extends('layouts.app')

@section('title', 'Monitor de distribuidoras')

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
    .btn-exportar {
        padding: 10px 22px;
        background: #0B1F3A;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: .88rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-exportar:hover { background: #1E3A5F; }

    /* ── Alerta banner ── */
    .alerta-banner {
        margin: 0 32px 20px 32px;
        background: #FEF2F2;
        border: 1.5px solid #FECACA;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }
    .alerta-icon {
        width: 36px; height: 36px;
        background: #FEE2E2;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .alerta-title { font-size: .92rem; font-weight: 700; color: #B91C1C; margin-bottom: 3px; }
    .alerta-sub   { font-size: .82rem; color: #DC2626; opacity: .85; line-height: 1.5; }

    /* ── KPI Row ── */
    .kpi-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 0 32px 20px 32px; }
    .kpi-card {
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        padding: 18px 20px;
    }
    .kpi-card.red-left    { border-left: 4px solid #DC2626; }
    .kpi-card.amber-left  { border-left: 4px solid #D97706; }
    .kpi-card.green-left  { border-left: 4px solid #16A34A; }
    .kpi-label { font-size: .70rem; font-weight: 700; letter-spacing: .08em; color: #94A3B8; text-transform: uppercase; margin-bottom: 8px; }
    .kpi-value { font-family: 'DM Mono', monospace; font-size: 2rem; font-weight: 500; color: #0B1F3A; margin-bottom: 4px; }
    .kpi-value.red   { color: #DC2626; }
    .kpi-value.amber { color: #D97706; }
    .kpi-value.green { color: #16A34A; }
    .kpi-sub { font-size: .78rem; color: #94A3B8; font-weight: 500; }

    /* ── Main card ── */
    .main-card {
        margin: 0 32px 32px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }
    .card-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px;
        border-bottom: 1px solid #F1F5F9;
    }
    .card-header-title { font-size: 1rem; font-weight: 700; color: #0B1F3A; }
    .search-input {
        padding: 8px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .85rem;
        font-family: 'DM Sans', sans-serif;
        color: #0B1F3A;
        outline: none;
        width: 200px;
    }
    .search-input:focus { border-color: #2563EB; }

    /* ── Tabs ── */
    .tabs-row {
        display: flex;
        padding: 0 22px;
        border-bottom: 1.5px solid #E2E8F0;
        gap: 4px;
    }
    .tab-btn {
        padding: 10px 16px;
        font-size: .86rem;
        font-weight: 500;
        color: #64748B;
        background: none;
        border: none;
        border-bottom: 2.5px solid transparent;
        margin-bottom: -1.5px;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        white-space: nowrap;
    }
    .tab-btn.active { color: #2563EB; border-bottom-color: #2563EB; font-weight: 700; }

    /* ── Lista distribuidoras ── */
    .dist-list { display: flex; flex-direction: column; }
    .dist-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 22px;
        border-bottom: 1px solid #F8FAFC;
        transition: background .12s;
    }
    .dist-item:last-child { border-bottom: none; }
    .dist-item.morosa   { background: #FFF1F2; }
    .dist-item.vencer   { background: #FFFBEB; }
    .dist-item.corriente { background: #fff; }

    .dist-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .88rem; font-weight: 700;
        flex-shrink: 0;
    }
    .dist-avatar.red   { background: #FEE2E2; color: #B91C1C; }
    .dist-avatar.amber { background: #FEF3C7; color: #B45309; }
    .dist-avatar.green { background: #DCFCE7; color: #15803D; }

    .dist-info { flex: 1; min-width: 0; }
    .dist-name { font-size: .92rem; font-weight: 700; color: #0B1F3A; }
    .dist-ref  { font-size: .75rem; color: #94A3B8; margin-top: 2px; font-family: 'DM Mono', monospace; }

    .dist-venc { flex: 1; }
    .dist-venc-label { font-size: .72rem; color: #94A3B8; margin-bottom: 3px; }
    .dist-venc-val { font-size: .85rem; font-weight: 700; }
    .dist-venc-val.red   { color: #DC2626; }
    .dist-venc-val.amber { color: #D97706; }
    .dist-venc-val.gray  { color: #334155; }

    .dist-deuda { text-align: right; }
    .dist-deuda-val { font-family: 'DM Mono', monospace; font-size: .95rem; font-weight: 700; }
    .dist-deuda-val.red   { color: #DC2626; }
    .dist-deuda-val.amber { color: #D97706; }
    .dist-deuda-val.green { color: #16A34A; }
    .dist-deuda-sub { font-size: .75rem; color: #94A3B8; margin-top: 2px; }

    .dist-badges { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; margin-left: 16px; }
    .badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 99px;
        font-size: .75rem; font-weight: 700;
    }
    .badge-red    { background: #FEE2E2; color: #B91C1C; }
    .badge-amber  { background: #FEF3C7; color: #B45309; }
    .badge-green  { background: #DCFCE7; color: #15803D; }
    .badge-block  { background: #FEE2E2; color: #B91C1C; border: 1px solid #FECACA; }
    .badge-warn   { background: #FEF3C7; color: #B45309; border: 1px solid #FDE68A; }
    .ok-text { font-size: .78rem; color: #16A34A; font-weight: 600; }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <div>
            <h1 class="topbar-title">Monitor de distribuidoras</h1>
            <div class="topbar-date">
                {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM, YYYY') }}
            </div>
        </div>
       
    </div>

    {{-- ── ALERTA BANNER ── --}}
    {{-- TODO: mostrar solo si hay distribuidoras morosas --}}
    <div class="alerta-banner">
        <div class="alerta-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>
        <div>
            {{-- TODO: mostrar número real de morosas desde BD --}}
            <div class="alerta-title">distribuidoras en cuenta morosa — operaciones bloqueadas</div>
            <div class="alerta-sub">No se pueden registrar prevales ni movimientos hasta que liquiden su adeudo.</div>
        </div>
    </div>

    {{-- ── KPI ROW ── --}}
    <div class="kpi-row">
        <div class="kpi-card red-left">
            <div class="kpi-label">En cuenta morosa</div>
            {{-- TODO: traer de BD --}}
            <div class="kpi-value red"></div>
            <div class="kpi-sub">Operaciones bloqueadas</div>
        </div>
        <div class="kpi-card amber-left">
            <div class="kpi-label">Deuda total</div>
            {{-- TODO: traer de BD --}}
            <div class="kpi-value amber"></div>
            <div class="kpi-sub">Saldo pendiente</div>
        </div>
        <div class="kpi-card green-left">
            <div class="kpi-label">Días promedio</div>
            {{-- TODO: traer de BD --}}
            <div class="kpi-value green"></div>
            <div class="kpi-sub">Sin pago</div>
        </div>
    </div>

    {{-- ── MAIN CARD ── --}}
    <div class="main-card">
        <div class="card-header-row">
            <span class="card-header-title">Estado de distribuidoras</span>
            <input type="text" class="search-input" placeholder="Buscar distribuidora...">
        </div>

        <div class="tabs-row">
            {{-- TODO: mostrar conteos reales desde BD --}}
            <button class="tab-btn active">Todas</button>
            <button class="tab-btn">Morosas</button>
            <button class="tab-btn">Por vencer</button>
            <button class="tab-btn">Al corriente</button>
        </div>

        <div class="dist-list">
            {{-- TODO: iterar distribuidoras desde BD --}}
            {{-- Ejemplo de estructura cuando haya BD:
            @foreach($distribuidoras as $dist)
                <div class="dist-item {{ $dist->estado }}">
                    ...
                </div>
            @endforeach
            --}}

            {{-- Placeholder vacío mientras no hay BD --}}
            <div style="padding: 40px; text-align: center; color: #94A3B8; font-size: .88rem;">
                Las distribuidoras aparecerán aquí cuando se conecte la base de datos.
            </div>
        </div>
    </div>

</div>

<script>
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>

@endsection