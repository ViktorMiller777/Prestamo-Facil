@extends('layouts.app')

@section('title', 'Panel de puntos')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar ── */
    .topbar { padding: 24px 32px 16px 32px; }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-sub   { font-size: .82rem; color: #64748B; margin-top: 3px; }

    /* ── Cards ── */
    .main-card {
        margin: 0 32px 16px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }

    /* ── Banner oscuro puntos ── */
    .pts-banner {
        background: #0B1F3A;
        padding: 24px 28px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
    }
    .pts-main { display: flex; align-items: flex-end; gap: 8px; }
    .pts-num  { font-size: 3.5rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #fff; line-height: 1; }
    .pts-lbl  { font-size: .95rem; color: rgba(255,255,255,0.5); margin-bottom: 8px; }
    .pts-equiv { font-size: .88rem; color: #60A5FA; margin-top: 6px; }
    .pts-right { text-align: right; }
    .pts-right-label { font-size: .70rem; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
    .pts-right-val   { font-size: 1rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #fff; }
    .pts-right-sub   { font-size: .75rem; color: rgba(255,255,255,0.35); margin-top: 3px; }

    /* ── Barra progreso ── */
    .progress-wrap { padding: 14px 22px; border-bottom: 1px solid #F1F5F9; }
    .progress-labels { display: flex; justify-content: space-between; font-size: .82rem; margin-bottom: 7px; }
    .progress-bar { height: 8px; background: #E2E8F0; border-radius: 99px; overflow: hidden; margin-bottom: 5px; }
    .progress-fill { height: 100%; border-radius: 99px; background: #16A34A; }
    .progress-hint { font-size: .75rem; color: #94A3B8; }

    /* ── Sección transferencia ── */
    .transfer-section { padding: 20px 22px; }
    .transfer-title { font-size: .95rem; font-weight: 700; color: #0B1F3A; margin-bottom: 4px; }
    .transfer-sub   { font-size: .82rem; color: #94A3B8; margin-bottom: 18px; line-height: 1.5; }

    .multiplos-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 18px; }
    .multiplo-btn {
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 14px 10px;
        text-align: center;
        cursor: pointer;
        background: #fff;
        transition: border-color .15s, background .15s;
    }
    .multiplo-btn:hover { border-color: #2563EB; background: #EFF6FF; }
    .multiplo-btn.selected { border-color: #2563EB; background: #EFF6FF; border-width: 2px; }
    .multiplo-btn.disabled { opacity: .4; cursor: not-allowed; background: #F8FAFC; }
    .multiplo-pts { font-size: .75rem; color: #94A3B8; margin-bottom: 4px; }
    .multiplo-val { font-size: .95rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #0B1F3A; }

    .resumen-card {
        background: #F0FDF4;
        border: 1.5px solid #BBF7D0;
        border-radius: 9px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .resumen-title { font-size: .88rem; font-weight: 700; color: #15803D; }
    .resumen-sub   { font-size: .78rem; color: #64748B; margin-top: 2px; }
    .resumen-val   { font-size: 1.1rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #15803D; }

    .btn-solicitar {
        width: 100%;
        padding: 11px;
        background: #2563EB;
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: .92rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
    }
    .btn-solicitar:hover { background: #1D4ED8; }

    /* ── Historial ── */
    .historial-card {
        margin: 0 32px 32px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }
    .historial-header { padding: 18px 22px; border-bottom: 1px solid #F1F5F9; }
    .historial-title { font-size: .95rem; font-weight: 700; color: #0B1F3A; margin-bottom: 3px; }
    .historial-sub   { font-size: .82rem; color: #94A3B8; }

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

    .badge-corte { background: #DBEAFE; color: #1D4ED8; padding: 3px 10px; border-radius: 99px; font-size: .75rem; font-weight: 700; font-family: 'DM Mono', monospace; }
    .badge-corte.gray { background: #F1F5F9; color: #64748B; }
    .mono { font-family: 'DM Mono', monospace; }
    .pts-plus { color: #16A34A; font-weight: 700; }
    .pts-minus { color: #DC2626; font-weight: 700; }
    
@media (max-width: 1024px) {

    .topbar { padding: 20px; }
    .topbar-title { font-size: 1.3rem; }

    .main-card { margin: 0 16px 16px 16px; }
    .historial-card { margin: 0 16px 32px 16px; }

    .pts-banner { flex-direction: column; align-items: flex-start; gap: 14px; }
    .pts-right { text-align: left; }
    .pts-num { font-size: 2.8rem; }

    .multiplos-grid { grid-template-columns: 1fr 1fr; }

    .resumen-card { flex-direction: column; align-items: flex-start; gap: 8px; }

    .pf-table { display: block; overflow-x: auto; white-space: nowrap; }
    .pf-table th,
    .pf-table td { padding: 10px 14px; font-size: .78rem; }
}

</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <h1 class="topbar-title">Panel de puntos</h1>
        <div class="topbar-sub">Puntos acumulados por pagos anticipados</div>
    </div>

    {{-- ── CARD PRINCIPAL ── --}}
    <div class="main-card">

        {{-- Banner puntos --}}
        <div class="pts-banner">
            <div>
                <div class="pts-main">
                    {{-- TODO: traer puntos de la BD --}}
                    <div class="pts-num"></div>
                    <div class="pts-lbl">pts</div>
                </div>
                {{-- TODO: calcular equivalente en dinero (pts * $2) --}}
                <div class="pts-equiv"></div>
            </div>
            <div class="pts-right">
                <div class="pts-right-label">Valor del punto</div>
                <div class="pts-right-val">$2.00</div>
                <div class="pts-right-sub">por punto acumulado</div>
            </div>
        </div>

        {{-- Barra de progreso --}}
        <div class="progress-wrap">
            <div class="progress-labels">
                {{-- TODO: calcular desde BD --}}
                <span style="color:#64748B;">Puntos acumulados: <strong style="color:#0B1F3A;"></strong></span>
                <span style="color:#16A34A;">Siguiente múltiplo disponible: <strong></strong></span>
            </div>
            {{-- TODO: calcular porcentaje desde BD --}}
            <div class="progress-bar"><div class="progress-fill" style="width:0%;"></div></div>
            <div class="progress-hint"></div>
        </div>

        {{-- Sección transferencia --}}
        <div class="transfer-section">
            <div class="transfer-title">Solicitar transferencia</div>
            <div class="transfer-sub">Selecciona cuánto quieres transferir. Solo puedes solicitar en múltiplos de $200 (100 pts cada uno).</div>

            {{-- Botones de múltiplos --}}
            {{-- TODO: habilitar/deshabilitar según puntos disponibles de la BD --}}
            <div class="multiplos-grid">
                <div class="multiplo-btn selected" onclick="seleccionar(this, 100, 200)">
                    <div class="multiplo-pts">100 pts</div>
                    <div class="multiplo-val">$200</div>
                </div>
                <div class="multiplo-btn" onclick="seleccionar(this, 200, 400)">
                    <div class="multiplo-pts">200 pts</div>
                    <div class="multiplo-val">$400</div>
                </div>
                <div class="multiplo-btn" onclick="seleccionar(this, 300, 600)">
                    <div class="multiplo-pts">300 pts</div>
                    <div class="multiplo-val">$600</div>
                </div>
                <div class="multiplo-btn disabled">
                    <div class="multiplo-pts">400 pts</div>
                    <div class="multiplo-val">$800</div>
                </div>
            </div>

            {{-- Resumen selección --}}
            <div class="resumen-card">
                <div>
                    <div class="resumen-title">Transferencia seleccionada</div>
                    <div class="resumen-sub" id="resumen-sub">100 pts se descontarán de tu saldo</div>
                </div>
                <div class="resumen-val" id="resumen-val">$200</div>
            </div>

            <button class="btn-solicitar">Solicitar transferencia →</button>
        </div>

    </div>

    {{-- ── HISTORIAL ── --}}
    <div class="historial-card">
        <div class="historial-header">
            <div class="historial-title">Historial de puntos</div>
            <div class="historial-sub">Cómo has ganado y usado tus puntos</div>
        </div>
        <table class="pf-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Corte</th>
                    <th>Puntos</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                {{-- TODO: iterar historial desde BD --}}
                {{--
                @foreach($historial as $mov)
                <tr>
                    <td style="color:#64748B;" class="mono">{{ $mov->fecha }}</td>
                    <td>
                        <div style="font-weight:600;color:#0B1F3A;">{{ $mov->concepto }}</div>
                        <div style="font-size:.75rem;color:#94A3B8;">{{ $mov->descripcion }}</div>
                    </td>
                    <td><span class="badge-corte {{ $mov->activo ? '' : 'gray' }}">#{{ $mov->corte }}</span></td>
                    <td class="mono {{ $mov->puntos > 0 ? 'pts-plus' : 'pts-minus' }}">
                        {{ $mov->puntos > 0 ? '+' : '' }}{{ $mov->puntos }} pts
                    </td>
                    <td class="mono" style="color:#0B1F3A;">{{ $mov->saldo }} pts</td>
                </tr>
                @endforeach
                --}}
                <tr>
                    <td colspan="5" style="text-align:center;color:#94A3B8;padding:32px;font-size:.85rem;">
                        El historial aparecerá aquí cuando se conecte la base de datos.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
    function seleccionar(el, pts, val) {
        if (el.classList.contains('disabled')) return;
        document.querySelectorAll('.multiplo-btn').forEach(b => {
            b.classList.remove('selected');
        });
        el.classList.add('selected');
        document.getElementById('resumen-val').textContent = '$' + val;
        document.getElementById('resumen-sub').textContent = pts + ' pts se descontarán de tu saldo';
    }
</script>

@endsection