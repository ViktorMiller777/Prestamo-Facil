@extends('layouts.app')

@section('title', 'Mis clientes')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar ── */
    .topbar { padding: 24px 32px 16px 32px; }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-sub   { font-size: .82rem; color: #64748B; margin-top: 3px; }

    /* ── KPI Row ── */
    .kpi-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 0 32px 20px 32px; }
    .kpi-card {
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        padding: 18px 20px;
    }
    .kpi-card.blue-left  { border-left: 4px solid #2563EB; }
    .kpi-label { font-size: .70rem; font-weight: 700; letter-spacing: .08em; color: #94A3B8; text-transform: uppercase; margin-bottom: 8px; }
    .kpi-value { font-family: 'DM Mono', monospace; font-size: 2rem; font-weight: 500; color: #0B1F3A; margin-bottom: 4px; }
    .kpi-sub { font-size: .78rem; font-weight: 500; }
    .kpi-sub.green { color: #16A34A; }
    .kpi-sub.red   { color: #DC2626; }
    .kpi-sub.blue  { color: #2563EB; }

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
        background: #0B1F3A;
        color: #fff;
        outline: none;
        width: 200px;
    }
    .search-input::placeholder { color: #64748B; }

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

    /* ── Tabla ── */
    .pf-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
    .pf-table th {
        padding: 10px 22px; text-align: left;
        color: #94A3B8; font-weight: 700; font-size: .68rem;
        text-transform: uppercase; letter-spacing: .06em;
        border-bottom: 1px solid #F1F5F9;
    }
    .pf-table td { padding: 14px 22px; color: #334155; border-bottom: 1px solid #F8FAFC; vertical-align: middle; }
    .pf-table tr:last-child td { border-bottom: none; }
    .pf-table tr:hover td { background: #F8FAFC; }
    .row-moroso   td { background: #FFF1F2; }
    .row-pendiente td { background: #FFFBEB; }

    .cliente-name { font-size: .92rem; font-weight: 700; color: #0B1F3A; }
    .cliente-sub  { font-size: .75rem; color: #94A3B8; margin-top: 2px; }

    .badge {
        display: inline-flex; align-items: center;
        padding: 3px 10px; border-radius: 99px;
        font-size: .75rem; font-weight: 700;
    }
    .badge-blue   { background: #DBEAFE; color: #1D4ED8; }
    .badge-green  { background: #DCFCE7; color: #15803D; }
    .badge-red    { background: #FEE2E2; color: #B91C1C; }
    .badge-amber  { background: #FEF3C7; color: #B45309; }

    .mono { font-family: 'DM Mono', monospace; }

    .btn-moroso {
        padding: 5px 12px;
        border-radius: 7px;
        border: 1.5px solid #FECACA;
        background: #FEE2E2;
        color: #B91C1C;
        font-size: .78rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-moroso:hover { background: #FECACA; }

    /* ── Modal ── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        align-items: center;
        justify-content: center;
        z-index: 999;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 12px;
        border: 1.5px solid #E2E8F0;
        padding: 26px;
        width: 440px;
        max-width: 95vw;
    }
    .modal-title { font-size: 1rem; font-weight: 700; color: #0B1F3A; margin-bottom: 4px; }
    .modal-sub   { font-size: .82rem; color: #94A3B8; margin-bottom: 16px; line-height: 1.5; }
    .modal-cliente {
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 9px;
        padding: 12px 16px;
        margin-bottom: 16px;
    }
    .modal-cliente-name { font-size: .92rem; font-weight: 700; color: #0B1F3A; }
    .modal-cliente-sub  { font-size: .78rem; color: #94A3B8; margin-top: 2px; }
    .field-label { font-size: .78rem; color: #64748B; margin-bottom: 5px; display: block; font-weight: 500; }
    .field-select {
        width: 100%;
        padding: 9px 12px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .88rem;
        font-family: 'DM Sans', sans-serif;
        color: #0B1F3A;
        outline: none;
        margin-bottom: 12px;
    }
    .field-ta {
        width: 100%;
        height: 72px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: .85rem;
        font-family: 'DM Sans', sans-serif;
        color: #334155;
        resize: none;
        outline: none;
    }
    .alert-warn {
        background: #FEF3C7;
        border: 1.5px solid #FDE68A;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: .82rem;
        color: #92400E;
        line-height: 1.5;
        margin-top: 12px;
    }
    .modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 18px; }
    .btn { padding: 9px 18px; border-radius: 8px; font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .btn-ghost  { background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; }
    .btn-danger { background: #FEE2E2; color: #B91C1C; border: 1.5px solid #FECACA; }
    .btn-danger:hover { background: #FECACA; }

    /* ── ADAPTACIÓN SOLO PARA TABLET 10" ── */
@media (max-width: 1280px) {

    /* Márgenes generales */
    .topbar,
    .kpi-row,
    .main-card {
        padding-left: 16px !important;
        padding-right: 16px !important;
        margin-left: 16px !important;
        margin-right: 16px !important;
    }

    /* Topbar más compacto */
    .topbar {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    /* KPI → 2 columnas (no 3) */
    .kpi-row {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    /* Header tabla */
    .card-header-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    /* Buscador más usable */
    .search-input {
        width: 100%;
    }

    /* Tabs con scroll (clave) */
    .tabs-row {
        overflow-x: auto;
        scrollbar-width: none;
    }

    .tabs-row::-webkit-scrollbar {
        display: none;
    }

    /* Tabla */
    .pf-table {
        min-width: 800px;
    }

    .main-card {
        overflow-x: auto;
    }

    /* Padding tabla más compacto */
    .pf-table th,
    .pf-table td {
        padding: 10px 12px;
    }

    /* Tipografía ligera */
    .kpi-value {
        font-size: 1.6rem;
    }

    .topbar-title {
        font-size: 1.3rem;
    }

    /* Modal más cómodo en tablet */
    .modal-box {
        width: 90%;
        padding: 20px;
    }

    .modal-footer {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <h1 class="topbar-title">Mis clientes</h1>
        <div class="topbar-sub">Gestión de tu red de clientes finales</div>
    </div>

    {{-- ── KPI ROW ── --}}
    <div class="kpi-row">
        <div class="kpi-card blue-left">
            <div class="kpi-label">Total clientes</div>
            {{-- TODO: traer de BD --}}
            <div class="kpi-value"></div>
            <div class="kpi-sub blue">En tu red</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Al corriente</div>
            {{-- TODO: traer de BD --}}
            <div class="kpi-value"></div>
            <div class="kpi-sub green">Pagando bien</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Con problemas</div>
            {{-- TODO: traer de BD --}}
            <div class="kpi-value"></div>
            <div class="kpi-sub red">Requieren atención</div>
        </div>
    </div>

    {{-- ── TABLA ── --}}
    <div class="main-card">
        <div class="card-header-row">
            <span class="card-header-title">Listado de clientes</span>
            <input type="text" class="search-input" placeholder="Buscar cliente...">
        </div>

        <div class="tabs-row">
            {{-- TODO: mostrar conteos reales desde BD --}}
            <button class="tab-btn active">Todos</button>
            <button class="tab-btn">Al corriente</button>
            <button class="tab-btn">Morosos</button>
            <button class="tab-btn">Solicitud pendiente</button>
        </div>

        <table class="pf-table">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Pagos realizados</th>
                    <th>Adeudo</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                {{-- TODO: iterar clientes desde BD --}}
                {{--
                @foreach($clientes as $cliente)
                <tr class="
                    {{ $cliente->estado === 'moroso' ? 'row-moroso' : '' }}
                    {{ $cliente->estado === 'solicitud_enviada' ? 'row-pendiente' : '' }}
                ">
                    <td>
                        <div class="cliente-name">{{ $cliente->nombre }}</div>
                        <div class="cliente-sub">Activo desde {{ $cliente->desde }}</div>
                    </td>
                    <td><span class="badge badge-blue">{{ $cliente->producto }}</span></td>
                    <td class="mono" style="color:{{ $cliente->pagos_ok ? '#16A34A' : '#D97706' }};">
                        {{ $cliente->pagos_realizados }}/{{ $cliente->pagos_total }}
                    </td>
                    <td class="mono" style="color:{{ $cliente->estado === 'moroso' ? '#DC2626' : '#334155' }};">
                        ${{ $cliente->adeudo }}
                    </td>
                    <td>
                        @if($cliente->estado === 'corriente')
                            <span class="badge badge-green">Al corriente</span>
                        @elseif($cliente->estado === 'moroso')
                            <span class="badge badge-red">Moroso</span>
                        @elseif($cliente->estado === 'solicitud_enviada')
                            <span class="badge badge-amber">Solicitud enviada</span>
                        @endif
                    </td>
                    <td>
                        @if($cliente->estado === 'corriente')
                            <button class="btn-moroso"
                                onclick="abrirModal('{{ $cliente->nombre }}', '{{ $cliente->producto }}', '${{ $cliente->adeudo }}')">
                                Reportar moroso
                            </button>
                        @elseif($cliente->estado === 'solicitud_enviada')
                            <span style="font-size:.78rem;color:#94A3B8;">En revisión coordinador</span>
                        @else
                            <span style="font-size:.78rem;color:#94A3B8;">Confirmado</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                --}}
                <tr>
                    <td colspan="6" style="text-align:center;color:#94A3B8;padding:32px;font-size:.85rem;">
                        Los clientes aparecerán aquí cuando se conecte la base de datos.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

{{-- ── MODAL REPORTAR MOROSO ── --}}
<div id="modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-title">Solicitar cambio a moroso</div>
        <div class="modal-sub">Esta solicitud se enviará al coordinador para su aprobación. No se aplica automáticamente.</div>

        <div class="modal-cliente">
            {{-- TODO: llenar con datos del cliente seleccionado --}}
            <div class="modal-cliente-name" id="modal-nombre"></div>
            <div class="modal-cliente-sub" id="modal-sub"></div>
        </div>

        <label class="field-label">Motivo de la solicitud</label>
        <select class="field-select">
            <option value="">Seleccionar motivo...</option>
            <option>No ha realizado ningún pago</option>
            <option>Lleva varios pagos pendientes</option>
            <option>Se negó a pagar</option>
            <option>No localizable</option>
            <option>Otro</option>
        </select>

        <label class="field-label">Descripción adicional (opcional)</label>
        <textarea class="field-ta" placeholder="Explica la situación al coordinador..."></textarea>

        <div class="alert-warn">
            El coordinador recibirá tu solicitud y dará la aprobación final. Te notificaremos cuando sea revisada.
        </div>

        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="document.getElementById('modal').classList.remove('show')">Cancelar</button>
            <button class="btn btn-danger">Enviar solicitud al coordinador</button>
        </div>
    </div>
</div>

<script>
    function abrirModal(nombre, producto, adeudo) {
        document.getElementById('modal-nombre').textContent = nombre;
        document.getElementById('modal-sub').textContent = producto + ' · Adeudo: ' + adeudo;
        document.getElementById('modal').classList.add('show');
    }

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>

@endsection