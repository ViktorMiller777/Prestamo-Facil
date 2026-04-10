@extends('layouts.app')

@section('title', 'Conciliación bancaria')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar holaaaaaa ── */
    .topbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 24px 32px 16px 32px;
    }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-date  { font-size: .82rem; color: #64748B; margin-top: 3px; }
    .topbar-actions { display: flex; gap: 10px; }
    .btn-tb {
        padding: 9px 20px;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background .15s;
    }
    .btn-tb-outline { background: #fff; color: #0B1F3A; border: 1.5px solid #E2E8F0; }
    .btn-tb-outline:hover { border-color: #CBD5E1; }
    .btn-tb-primary { background: #2563EB; color: #fff; border: 1.5px solid #2563EB; }
    .btn-tb-primary:hover { background: #1D4ED8; }

    /* ── Pasos ── */
    .steps-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        padding: 0 32px 20px 32px;
    }
    .step-card {
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 20px 20px 18px 20px;
    }
    .step-badge {
        width: 32px; height: 32px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem;
        font-weight: 700;
        margin-bottom: 12px;
    }
    .step-badge.done  { background: #DCFCE7; color: #16A34A; font-size: 1rem; }
    .step-badge.blue  { background: #2563EB; color: #fff; }
    .step-badge.gray  { background: #F1F5F9; color: #94A3B8; border: 1.5px solid #E2E8F0; }
    .step-title { font-size: .95rem; font-weight: 700; color: #0B1F3A; margin-bottom: 6px; line-height: 1.3; }
    .step-desc  { font-size: .82rem; color: #64748B; line-height: 1.5; }

    /* ── Main card ── */
    .main-card {
        margin: 0 32px 16px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 22px 24px;
    }
    .main-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .main-card-title { font-size: 1rem; font-weight: 700; color: #0B1F3A; margin-bottom: 3px; }
    .main-card-sub   { font-size: .82rem; color: #94A3B8; }

    /* ── Archivo cargado row ── */
    .file-loaded-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 18px;
        background: #F0FDF4;
        border: 1.5px solid #BBF7D0;
        border-radius: 10px;
        margin-bottom: 14px;
    }
    .file-icon {
        width: 40px; height: 40px;
        background: #DCFCE7;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .file-icon svg { width: 20px; height: 20px; stroke: #16A34A; }
    .file-info { flex: 1; min-width: 0; }
    .file-name { font-size: .90rem; font-weight: 700; color: #2563EB; margin-bottom: 3px; }
    .file-meta { font-size: .78rem; color: #64748B; }
    .btn-quitar {
        display: flex; align-items: center; gap: 5px;
        padding: 7px 14px;
        background: #FEE2E2;
        color: #991B1B;
        border: 1.5px solid #FECACA;
        border-radius: 8px;
        font-size: .82rem;
        font-weight: 700;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background .12s;
        flex-shrink: 0;
    }
    .btn-quitar:hover { background: #FECACA; }

    /* ── Drop zone ── */
    .drop-zone {
        border: 2px dashed #BFDBFE;
        border-radius: 12px;
        background: #EFF6FF;
        padding: 48px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
        transition: border-color .15s, background .15s;
        text-align: center;
    }
    .drop-zone:hover { border-color: #2563EB; background: #DBEAFE; }
    .drop-zone.dragover { border-color: #2563EB; background: #DBEAFE; }
    .drop-icon {
        width: 56px; height: 56px;
        background: #DBEAFE;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 4px;
    }
    .drop-icon svg { width: 26px; height: 26px; stroke: #2563EB; }
    .drop-title { font-size: 1rem; font-weight: 700; color: #2563EB; }
    .drop-sub   { font-size: .84rem; color: #64748B; }
    .drop-formats {
        display: inline-flex;
        padding: 4px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 99px;
        background: #fff;
        font-size: .78rem;
        color: #64748B;
        font-weight: 500;
        margin-top: 4px;
    }
    #file-input { display: none; }

    /* ── Tabla de resultados ── */
    .resultados-card {
        margin: 0 32px 16px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
        display: none;
    }
    .resultados-header {
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #F1F5F9;
    }
    .resultados-title { font-size: 1rem; font-weight: 700; color: #0B1F3A; }
    .resultados-sub   { font-size: .82rem; color: #94A3B8; margin-top: 2px; }

    .kpi-concil {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-bottom: 1px solid #F1F5F9;
    }
    .kpi-cell {
        padding: 16px 20px;
        border-right: 1px solid #F1F5F9;
    }
    .kpi-cell:last-child { border-right: none; }
    .kpi-lbl { font-size: .68rem; color: #94A3B8; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
    .kpi-val { font-size: 1.2rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #0B1F3A; }
    .kpi-val.green  { color: #16A34A; }
    .kpi-val.red    { color: #DC2626; }
    .kpi-val.blue   { color: #2563EB; }

    .concil-table { width: 100%; border-collapse: collapse; font-size: .84rem; }
    .concil-table th {
        padding: 10px 18px;
        text-align: left;
        color: #94A3B8;
        font-weight: 700;
        font-size: .68rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        border-bottom: 1px solid #F1F5F9;
        background: #F8FAFC;
    }
    .concil-table td {
        padding: 13px 18px;
        color: #334155;
        border-bottom: 1px solid #F8FAFC;
        vertical-align: middle;
    }
    .concil-table tr:last-child td { border-bottom: none; }
    .concil-table tr:hover td { background: #F8FAFC; }

    .badge-ok    { background: #DCFCE7; color: #166534; padding: 3px 10px; border-radius: 99px; font-size: .75rem; font-weight: 700; }
    .badge-diff  { background: #FEF3C7; color: #92400E; padding: 3px 10px; border-radius: 99px; font-size: .75rem; font-weight: 700; }
    .badge-noenc { background: #FEE2E2; color: #991B1B; padding: 3px 10px; border-radius: 99px; font-size: .75rem; font-weight: 700; }
    .mono { font-family: 'DM Mono', monospace; }

    /* ── Nota inferior ── */
    .nota-card {
        margin: 0 32px 32px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        padding: 14px 20px;
        font-size: .82rem;
        color: #94A3B8;
        line-height: 1.5;
    }

    /* ── Tablet ── */
    @media (max-width: 1024px) {
        .topbar { flex-direction: column; align-items: flex-start; gap: 10px; padding: 20px; }
        .topbar-title { font-size: 1.3rem; }
        .steps-row { grid-template-columns: 1fr; padding: 0 16px 16px 16px; }
        .main-card { margin: 0 16px 16px 16px; }
        .resultados-card { margin: 0 16px 16px 16px; }
        .nota-card { margin: 0 16px 32px 16px; }
        .kpi-concil { grid-template-columns: 1fr 1fr; }
        .concil-table { display: block; overflow-x: auto; white-space: nowrap; }
        .file-loaded-row { flex-direction: column; align-items: flex-start; gap: 10px; }
        .btn-quitar { width: 100%; justify-content: center; }
    }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <div>
            <h1 class="topbar-title">Conciliación bancaria</h1>
            <div class="topbar-date">
                {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM, YYYY') }}
            </div>
        </div>
        <div class="topbar-actions">
            <button class="btn-tb btn-tb-primary" id="btn-conciliar" onclick="iniciarConciliacion()" disabled>
                Iniciar conciliación
            </button>
        </div>
    </div>

    {{-- ── PASOS ── --}}
    <div class="steps-row">
        <div class="step-card">
            <div class="step-badge done">✓</div>
            <div class="step-title">Descargar estado de cuenta</div>
            <div class="step-desc">Obtén el Excel del portal bancario en formato .xlsx o .csv</div>
        </div>
        <div class="step-card">
            <div class="step-badge blue">2</div>
            <div class="step-title">Subir el archivo aquí</div>
            <div class="step-desc">Arrastra o selecciona el archivo. El sistema lo procesa automáticamente.</div>
        </div>
        <div class="step-card">
            <div class="step-badge gray" id="step3-badge">3</div>
            <div class="step-title">Revisar y confirmar</div>
            <div class="step-desc">El sistema compara referencias y montos. Tú revisas las diferencias.</div>
        </div>
    </div>

    {{-- ── MAIN CARD — SUBIR ARCHIVO ── --}}
    <div class="main-card">
        <div class="main-card-header">
            <div>
                <div class="main-card-title">Archivo del banco</div>
                <div class="main-card-sub">Arrastra el Excel aquí o haz clic para seleccionarlo</div>
            </div>
            <span id="badge-estado" style="padding:4px 12px;background:#F1F5F9;color:#64748B;font-size:.78rem;font-weight:700;border-radius:99px;">
                Sin archivo
            </span>
        </div>

        {{-- Archivo cargado --}}
        <div class="file-loaded-row" id="file-loaded" style="display:none;">
            <div class="file-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div class="file-info">
                <div class="file-name" id="file-name"></div>
                <div class="file-meta" id="file-meta"></div>
            </div>
            <button class="btn-quitar" onclick="quitarArchivo()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
                Quitar
            </button>
        </div>

        {{-- Drop zone --}}
        <div class="drop-zone" id="drop-zone"
             onclick="document.getElementById('file-input').click()"
             ondragover="onDragOver(event)"
             ondragleave="onDragLeave(event)"
             ondrop="onDrop(event)">
            <div class="drop-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 16 12 12 8 16"/>
                    <line x1="12" y1="12" x2="12" y2="21"/>
                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                </svg>
            </div>
            <div class="drop-title">Arrastra tu archivo aquí</div>
            <div class="drop-sub">o haz clic para seleccionarlo desde tu computadora</div>
            <span class="drop-formats">.xlsx · .xls · .csv</span>
        </div>

        <input type="file" id="file-input" accept=".xlsx,.xls,.csv" onchange="onFileSelect(event)">
    </div>

    {{-- ── TABLA DE RESULTADOS ── --}}
    <div class="resultados-card" id="resultados-card">
        <div class="resultados-header">
            <div>
                <div class="resultados-title">Resultados de la conciliación</div>
                <div class="resultados-sub">Comparación entre el archivo del banco y los registros del sistema</div>
            </div>
        </div>

        <div class="kpi-concil">
            <div class="kpi-cell">
                <div class="kpi-lbl">Movimientos en banco</div>
                <div class="kpi-val blue" id="kpi-total">—</div>
            </div>
            <div class="kpi-cell">
                <div class="kpi-lbl">Coinciden</div>
                <div class="kpi-val green" id="kpi-ok">—</div>
            </div>
            <div class="kpi-cell">
                <div class="kpi-lbl">Diferencia de monto</div>
                <div class="kpi-val" id="kpi-diff" style="color:#D97706;">—</div>
            </div>
            <div class="kpi-cell">
                <div class="kpi-lbl">No encontrados</div>
                <div class="kpi-val red" id="kpi-noenc">—</div>
            </div>
        </div>

        <table class="concil-table">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Distribuidor</th>
                    <th>Monto esperado</th>
                    <th>Monto banco</th>
                    <th>Fecha límite</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="tabla-resultados">
                {{-- Se llena con JS al conciliar --}}
            </tbody>
        </table>
    </div>

    {{-- ── NOTA ── --}}
    <div class="nota-card">
        El archivo no se guarda en el servidor. Solo se usa para la comparación contra los registros de la base de datos.
    </div>

</div>{{-- /page-wrapper --}}

<script>
    function quitarArchivo() {
        document.getElementById('file-loaded').style.display = 'none';
        document.getElementById('drop-zone').style.display = 'flex';
        document.getElementById('resultados-card').style.display = 'none';
        document.getElementById('badge-estado').textContent = 'Sin archivo';
        document.getElementById('badge-estado').style.background = '#F1F5F9';
        document.getElementById('badge-estado').style.color = '#64748B';
        document.getElementById('btn-conciliar').disabled = true;
        document.getElementById('file-input').value = '';
    }

    function onFileSelect(event) {
        const file = event.target.files[0];
        if (file) cargarArchivo(file);
    }

    function onDragOver(event) {
        event.preventDefault();
        document.getElementById('drop-zone').classList.add('dragover');
    }

    function onDragLeave(event) {
        document.getElementById('drop-zone').classList.remove('dragover');
    }

    function onDrop(event) {
        event.preventDefault();
        document.getElementById('drop-zone').classList.remove('dragover');
        const file = event.dataTransfer.files[0];
        if (file) cargarArchivo(file);
    }

    function cargarArchivo(file) {
        const kb = Math.round(file.size / 1024);
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-meta').textContent = kb + ' KB · Subido ahora';
        document.getElementById('file-loaded').style.display = 'flex';
        document.getElementById('drop-zone').style.display = 'none';
        document.getElementById('badge-estado').textContent = 'Archivo cargado';
        document.getElementById('badge-estado').style.background = '#DCFCE7';
        document.getElementById('badge-estado').style.color = '#166534';
        document.getElementById('btn-conciliar').disabled = false;
    }

    function iniciarConciliacion() {
        document.getElementById('resultados-card').style.display = 'block';
        document.getElementById('step3-badge').className = 'step-badge done';
        document.getElementById('step3-badge').textContent = '✓';
        document.getElementById('kpi-total').textContent = '—';
        document.getElementById('kpi-ok').textContent = '—';
        document.getElementById('kpi-diff').textContent = '—';
        document.getElementById('kpi-noenc').textContent = '—';
        document.getElementById('tabla-resultados').innerHTML = `
            <tr>
                <td colspan="6" style="text-align:center;color:#94A3B8;padding:32px;">
                    El backend procesará el archivo y llenará esta tabla con los resultados.
                </td>
            </tr>
        `;
        document.getElementById('resultados-card').scrollIntoView({ behavior: 'smooth' });
    }
</script>

@endsection