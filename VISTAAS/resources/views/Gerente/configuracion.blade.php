@extends('layouts.app')

@section('title', 'Gestión de Fechas de Corte')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; padding-bottom: 40px; }

    /* ── Topbar ── */
    .topbar { padding: 24px 32px 16px; }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-sub   { font-size: .82rem; color: #64748B; margin-top: 3px; }

    /* ── KPI Row ── */
    .kpi-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; padding: 0 32px 20px; }
    .kpi-card { background: #fff; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 18px 20px; }
    .kpi-card.blue-left { border-left: 4px solid #2563EB; border-radius: 0 12px 12px 0; }
    .kpi-label { font-size: .70rem; font-weight: 700; letter-spacing: .08em; color: #94A3B8; text-transform: uppercase; margin-bottom: 8px; }
    .kpi-value { font-family: 'DM Mono', monospace; font-size: 1.6rem; font-weight: 500; color: #0B1F3A; margin-bottom: 4px; }
    .kpi-sub { font-size: .78rem; font-weight: 500; }
    .kpi-sub.blue  { color: #2563EB; }
    .kpi-sub.green { color: #16A34A; }
    .kpi-sub.amber { color: #D97706; }

    /* ── Layout ── */
    .layout { display: grid; grid-template-columns: 1fr 360px; gap: 20px; padding: 0 32px 24px; }
    .main-card, .side-card { background: #fff; border: 1.5px solid #E2E8F0; border-radius: 14px; overflow: hidden; }
    .side-card { display: flex; flex-direction: column; }
    .card-hdr { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #F1F5F9; }
    .card-hdr-title { font-size: .95rem; font-weight: 700; color: #0B1F3A; }

    /* ── Navegación calendario ── */
    .cal-nav { display: flex; align-items: center; gap: 12px; }
    .cal-nav-btn { background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 7px; padding: 5px 12px; cursor: pointer; color: #64748B; font-size: .85rem; font-weight: 700; }
    .cal-nav-btn:hover { background: #EFF6FF; color: #2563EB; border-color: #93C5FD; }
    .cal-month-label { font-size: .92rem; font-weight: 700; color: #0B1F3A; min-width: 140px; text-align: center; }

    /* ── Grilla calendario ── */
    .cal-grid { padding: 16px 20px; }
    .cal-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; margin-bottom: 6px; }
    .cal-wd { text-align: center; font-size: .68rem; font-weight: 700; letter-spacing: .06em; color: #94A3B8; text-transform: uppercase; padding: 4px 0; }
    .cal-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
    .cal-day {
        aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
        border-radius: 8px; cursor: pointer; border: 1.5px solid transparent;
        font-size: .83rem; font-weight: 500; color: #334155; transition: background .12s, border-color .12s;
    }
    .cal-day:hover              { background: #F0F7FF; border-color: #BFDBFE; }
    .cal-day.other-month        { color: #CBD5E1; pointer-events: none; }
    .cal-day.today              { background: #EFF6FF; border-color: #93C5FD; color: #1D4ED8; font-weight: 700; }
    .cal-day.has-corte          { background: #EFF6FF; border-color: #2563EB; }
    .cal-day.has-corte .day-num { color: #1D4ED8; font-weight: 700; }
    .cal-day.editing            { background: #FEF3C7; border-color: #D97706; }
    .cal-day.editing .day-num   { color: #92400E; font-weight: 700; }
    .cal-day.selected           { background: #2563EB; border-color: #2563EB; }
    .cal-day.selected .day-num  { color: #fff; }
    .cal-day.past               { opacity: .4; cursor: default; pointer-events: none; }
    .corte-dot { width: 5px; height: 5px; border-radius: 50%; background: #2563EB; margin-top: 2px; }
    .cal-day.editing .corte-dot { background: #D97706; }
    .day-num { font-size: .83rem; }

    /* ── Leyenda ── */
    .legend-row { display: flex; gap: 14px; align-items: center; padding: 10px 20px 14px; border-top: 1px solid #F8FAFC; flex-wrap: wrap; }
    .leg-item { display: flex; align-items: center; gap: 5px; font-size: .74rem; color: #64748B; }
    .leg-dot  { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }

    /* ── Panel lateral ── */
    .side-section { padding: 16px 18px; border-bottom: 1px solid #F1F5F9; }
    .side-title { font-size: .70rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #94A3B8; margin-bottom: 12px; }

    /* ── Campos ── */
    .field-label { font-size: .78rem; font-weight: 500; color: #64748B; margin-bottom: 5px; display: block; }
    .fi { width: 100%; padding: 9px 12px; border: 1.5px solid #E2E8F0; border-radius: 8px; font-size: .88rem; font-family: 'DM Mono', monospace; color: #0B1F3A; outline: none; transition: border-color .15s; }
    .fi:focus { border-color: #2563EB; }
    .fi.edit-mode { border-color: #D97706; background: #FFFBEB; }
    .fi.edit-mode:focus { border-color: #B45309; }
    .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }

    /* ── Mode tag ── */
    .mode-tag { display: inline-flex; align-items: center; gap: 5px; font-size: .75rem; font-weight: 700; padding: 3px 10px; border-radius: 99px; margin-bottom: 10px; }
    .mode-tag.new  { background: #DBEAFE; color: #1D4ED8; }
    .mode-tag.edit { background: #FEF3C7; color: #92400E; }

    /* ── Alerts ── */
    .alert-info { background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 8px; padding: 10px 14px; font-size: .80rem; color: #1D4ED8; line-height: 1.5; margin-bottom: 10px; }
    .alert-warn { background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 8px; padding: 10px 14px; font-size: .80rem; color: #92400E; line-height: 1.5; }
    .alert-edit { background: #FEF3C7; border: 1.5px solid #FDE68A; border-radius: 8px; padding: 10px 14px; font-size: .80rem; color: #92400E; line-height: 1.5; }

    /* ── Badges ── */
    .badge { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 99px; font-size: .74rem; font-weight: 700; }
    .badge-blue  { background: #DBEAFE; color: #1D4ED8; }
    .badge-green { background: #DCFCE7; color: #15803D; }
    .badge-amber { background: #FEF3C7; color: #B45309; }

    /* ── Lista de cortes ── */
    .corte-item { display: flex; align-items: flex-start; justify-content: space-between; padding: 10px 12px; border-radius: 9px; border: 1.5px solid #E2E8F0; margin-bottom: 8px; transition: border-color .12s, background .12s; }
    .corte-item:hover   { border-color: #93C5FD; background: #F8FAFC; }
    .corte-item.editing { border-color: #D97706; background: #FFFBEB; }
    .corte-item:last-child { margin-bottom: 0; }
    .corte-fecha { font-family: 'DM Mono', monospace; font-size: .84rem; font-weight: 700; color: #0B1F3A; }
    .corte-actions { display: flex; gap: 4px; flex-shrink: 0; }
    .btn-icon { background: none; border: 1.5px solid #E2E8F0; border-radius: 6px; cursor: pointer; padding: 4px 8px; font-size: .75rem; color: #64748B; transition: all .12s; }
    .btn-icon.edit:hover  { color: #D97706; background: #FFFBEB; border-color: #FDE68A; }
    .btn-icon.del:hover   { color: #DC2626; background: #FEE2E2; border-color: #FECACA; }
    .btn-icon.active-edit { color: #D97706; background: #FEF3C7; border-color: #FDE68A; }

    /* ── Botones ── */
    .btn { padding: 9px 18px; border-radius: 8px; font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .12s; }
    .btn-primary { background: #2563EB; color: #fff; } .btn-primary:hover { background: #1D4ED8; }
    .btn-warning { background: #D97706; color: #fff; } .btn-warning:hover { background: #B45309; }
    .btn-ghost   { background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; } .btn-ghost:hover { background: #F8FAFC; }
    .btn-row { display: flex; gap: 8px; padding: 14px 18px; border-top: 1px solid #F1F5F9; background: #F8FAFC; }

    /* ── Lista ── */
    .proximos-list { overflow-y: auto; max-height: 280px; }
    .empty-msg { text-align: center; color: #94A3B8; font-size: .82rem; padding: 20px 0; }
    .list-sublabel { font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #94A3B8; margin-bottom: 8px; }
    .list-sublabel.pasados { color: #CBD5E1; margin-top: 12px; }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <h1 class="topbar-title">Gestión de fechas de corte</h1>
        <div class="topbar-sub">Calendario programable por día y hora · Solo acceso Gerente</div>
    </div>

    {{-- ── KPI ROW ── --}}
    <div class="kpi-row">
        <div class="kpi-card blue-left">
            <div class="kpi-label">Próximo corte</div>
            {{-- TODO: calcular desde $cortes --}}
            <div class="kpi-value" id="kpi-proximo">—</div>
            <div class="kpi-sub blue" id="kpi-proximo-hora">Sin programar</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Este mes</div>
            <div class="kpi-value" id="kpi-mes">{{ $cortesMes ?? 0 }}</div>
            <div class="kpi-sub green">Cortes programados</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Días al próximo</div>
            <div class="kpi-value" id="kpi-dias">—</div>
            <div class="kpi-sub amber">Días restantes</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Total programados</div>
            <div class="kpi-value" id="kpi-total">{{ $totalCortes ?? 0 }}</div>
            <div class="kpi-sub blue">En todos los meses</div>
        </div>
    </div>

    {{-- ── LAYOUT PRINCIPAL ── --}}
    <div class="layout">

        {{-- ── CALENDARIO ── --}}
        <div class="main-card">
            <div class="card-hdr">
                <span class="card-hdr-title">Calendario de cortes</span>
                <div class="cal-nav">
                    <button type="button" class="cal-nav-btn" onclick="cambiarMes(-1)">&#8592;</button>
                    <span class="cal-month-label" id="cal-label"></span>
                    <button type="button" class="cal-nav-btn" onclick="cambiarMes(1)">&#8594;</button>
                </div>
            </div>
            <div class="cal-grid">
                <div class="cal-weekdays">
                    <div class="cal-wd">Dom</div><div class="cal-wd">Lun</div><div class="cal-wd">Mar</div>
                    <div class="cal-wd">Mié</div><div class="cal-wd">Jue</div><div class="cal-wd">Vie</div>
                    <div class="cal-wd">Sáb</div>
                </div>
                <div class="cal-days" id="cal-days"></div>
            </div>
            <div class="legend-row">
                <div class="leg-item"><div class="leg-dot" style="background:#DBEAFE;border:1.5px solid #2563EB;"></div>Corte programado</div>
                <div class="leg-item"><div class="leg-dot" style="background:#FEF3C7;border:1.5px solid #D97706;"></div>Editando</div>
                <div class="leg-item"><div class="leg-dot" style="background:#EFF6FF;border:1.5px solid #93C5FD;"></div>Hoy</div>
                <div class="leg-item"><div class="leg-dot" style="background:#2563EB;"></div>Nuevo</div>
            </div>
        </div>

        {{-- ── PANEL LATERAL ── --}}
        <div class="side-card">

            <div class="side-section" style="flex-shrink:0;">
                <div class="side-title">Programar corte</div>

                <div id="no-sel-msg" class="alert-info">
                    Selecciona un día libre para crear un corte, o toca el ícono de editar en un corte existente.
                </div>

                <div id="form-panel" style="display:none;">
                    <div class="mode-tag new" id="mode-tag">+ Nuevo corte</div>

                    <div class="field-row">
                        <div>
                            <label class="field-label">Fecha</label>
                            <input type="text" class="fi" id="inp-fecha" name="fecha" readonly>
                        </div>
                        <div>
                            <label class="field-label">Hora</label>
                            {{-- TODO: campo hora en tabla Conciliacion --}}
                            <input type="time" class="fi" id="inp-hora" name="hora" value="23:59">
                        </div>
                    </div>

                    <label class="field-label">Tipo de corte</label>
                    {{-- TODO: campo tipo en tabla Conciliacion --}}
                    <select class="fi" id="inp-tipo" name="tipo" style="margin-bottom:10px;font-family:'DM Sans',sans-serif;">
                        <option value="quincenal">Quincenal</option>
                        <option value="mensual">Mensual</option>
                        <option value="especial">Especial / extraordinario</option>
                    </select>

                    <label class="field-label">Nota (opcional)</label>
                    <input type="text" class="fi" id="inp-nota" name="nota" placeholder="Ej. Corte fin de mes...">

                    <div id="alert-bottom" class="alert-warn" style="margin-top:10px;">
                        Al guardar se notificará a todas las distribuidoras activas.
                    </div>
                </div>
            </div>

            <div id="btn-row-panel" class="btn-row" style="display:none;">
                <button type="button" class="btn btn-ghost" onclick="cancelar()">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar" onclick="guardar()">Guardar corte</button>
            </div>

            <div class="side-section" style="flex:1;overflow:hidden;display:flex;flex-direction:column;min-height:0;">
                <div class="side-title">Cortes programados</div>
                <div class="proximos-list" id="proximos-list">
                    <div class="empty-msg">Sin cortes programados.</div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    var hoy = new Date(); hoy.setHours(0,0,0,0);
    var viewYear = hoy.getFullYear(), viewMonth = hoy.getMonth();
    var selectedDate = null, editIdx = null;

    var MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    var TIPOS_BADGE = { quincenal:'badge-blue', mensual:'badge-green', especial:'badge-amber' };
    var TIPOS_LABEL = { quincenal:'Quincenal', mensual:'Mensual', especial:'Especial' };

    {{--
    TODO: reemplazar por datos reales:
    var cortes = @json($cortes->map(fn($c) => [
        'id'    => $c->id,
        'fecha' => $c->fecha_limite_pago,
        'hora'  => $c->hora,
        'tipo'  => $c->tipo,
        'nota'  => $c->nota,
    ]));
    cortes.forEach(function(c){ c.fecha = new Date(c.fecha); c.fecha.setHours(0,0,0,0); });
    --}}
    var cortes = [];

    function mismaFecha(a, b) {
        return a.getFullYear()===b.getFullYear() && a.getMonth()===b.getMonth() && a.getDate()===b.getDate();
    }

    function renderCal() {
        document.getElementById('cal-label').textContent = MESES[viewMonth] + ' ' + viewYear;
        var first    = new Date(viewYear, viewMonth, 1).getDay();
        var dias     = new Date(viewYear, viewMonth + 1, 0).getDate();
        var prevDias = new Date(viewYear, viewMonth, 0).getDate();
        var container = document.getElementById('cal-days');
        container.innerHTML = '';
        var total = (first + dias) <= 35 ? 35 : 42;

        for (var i = 0; i < total; i++) {
            var d, mes, anno, other = false;
            if (i < first)            { d = prevDias - first + i + 1; mes = viewMonth - 1; anno = viewYear; other = true; }
            else if (i >= first+dias) { d = i - first - dias + 1;    mes = viewMonth + 1; anno = viewYear; other = true; }
            else                      { d = i - first + 1;           mes = viewMonth;     anno = viewYear; }

            var fecha = new Date(anno, mes, d); fecha.setHours(0,0,0,0);
            var isPast     = fecha < hoy;
            var isToday    = mismaFecha(fecha, hoy);
            var isSelected = selectedDate && mismaFecha(fecha, selectedDate);
            var corteEnDia = cortes.find(function(c){ return mismaFecha(c.fecha, fecha); });
            var isEditing  = editIdx !== null && corteEnDia && cortes.indexOf(corteEnDia) === editIdx;

            var day = document.createElement('div');
            day.className = 'cal-day';

            if (other) {
                day.classList.add('other-month');
            } else {
                if (isPast && !isToday) day.classList.add('past');
                if (isToday)            day.classList.add('today');
                if (isEditing)          day.classList.add('editing');
                else if (isSelected && editIdx === null) day.classList.add('selected');
                else if (corteEnDia)    day.classList.add('has-corte');
            }

            var dn = document.createElement('div'); dn.className = 'day-num'; dn.textContent = d;
            day.appendChild(dn);
            if (corteEnDia && !other) {
                var dot = document.createElement('div'); dot.className = 'corte-dot'; day.appendChild(dot);
            }
            if (!other && (!isPast || isToday)) {
                (function(f, c){
                    day.addEventListener('click', function(){
                        if (c) abrirEdicion(cortes.indexOf(c));
                        else   seleccionarDia(f);
                    });
                })(fecha, corteEnDia || null);
            }
            container.appendChild(day);
        }
    }

    function seleccionarDia(fecha) {
        if (editIdx !== null) return;
        selectedDate = new Date(fecha); selectedDate.setHours(0,0,0,0);
        setFormMode('new');
        llenarForm(selectedDate, null);
        renderCal();
    }

    function abrirEdicion(idx) {
        editIdx = idx; selectedDate = null;
        setFormMode('edit');
        llenarForm(cortes[idx].fecha, cortes[idx]);
        renderCal(); renderProximos();
    }

    function setFormMode(mode) {
        document.getElementById('no-sel-msg').style.display    = 'none';
        document.getElementById('form-panel').style.display    = 'block';
        document.getElementById('btn-row-panel').style.display = 'flex';
        var tag     = document.getElementById('mode-tag');
        var btn     = document.getElementById('btn-guardar');
        var alerta  = document.getElementById('alert-bottom');
        var inpHora = document.getElementById('inp-hora');
        var inpTipo = document.getElementById('inp-tipo');
        var inpNota = document.getElementById('inp-nota');
        if (mode === 'edit') {
            tag.className = 'mode-tag edit'; tag.textContent = 'Editando corte existente';
            btn.className = 'btn btn-warning'; btn.textContent = 'Guardar cambios';
            alerta.className = 'alert-edit';
            alerta.textContent = 'Estás modificando un corte existente. Las distribuidoras recibirán una notificación con la actualización.';
            inpHora.classList.add('edit-mode'); inpTipo.classList.add('edit-mode'); inpNota.classList.add('edit-mode');
        } else {
            tag.className = 'mode-tag new'; tag.textContent = '+ Nuevo corte';
            btn.className = 'btn btn-primary'; btn.textContent = 'Guardar corte';
            alerta.className = 'alert-warn';
            alerta.textContent = 'Al guardar se notificará a todas las distribuidoras activas.';
            inpHora.classList.remove('edit-mode'); inpTipo.classList.remove('edit-mode'); inpNota.classList.remove('edit-mode');
        }
    }

    function llenarForm(fecha, corte) {
        var dd = String(fecha.getDate()).padStart(2,'0');
        var mm = String(fecha.getMonth()+1).padStart(2,'0');
        var yy = fecha.getFullYear();
        document.getElementById('inp-fecha').value = dd+'/'+mm+'/'+yy;
        document.getElementById('inp-hora').value  = corte ? corte.hora : '23:59';
        document.getElementById('inp-tipo').value  = corte ? corte.tipo : 'quincenal';
        document.getElementById('inp-nota').value  = corte ? (corte.nota || '') : '';
    }

    function cancelar() {
        editIdx = null; selectedDate = null;
        document.getElementById('no-sel-msg').style.display    = 'block';
        document.getElementById('form-panel').style.display    = 'none';
        document.getElementById('btn-row-panel').style.display = 'none';
        renderCal(); renderProximos();
    }

    function guardar() {
        var hora = document.getElementById('inp-hora').value || '23:59';
        var tipo = document.getElementById('inp-tipo').value;
        var nota = document.getElementById('inp-nota').value;

        if (editIdx !== null) {
            {{--
            TODO: PATCH al controller:
            fetch('{{ route("gerente.cortes.update", ":id") }}'.replace(':id', cortes[editIdx].id), {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ hora, tipo, nota })
            });
            --}}
            cortes[editIdx].hora = hora;
            cortes[editIdx].tipo = tipo;
            cortes[editIdx].nota = nota;

        } else if (selectedDate) {
            var yaExiste = cortes.some(function(c){ return mismaFecha(c.fecha, selectedDate); });
            if (!yaExiste) {
                {{--
                TODO: POST al controller:
                fetch('{{ route("gerente.cortes.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        fecha: selectedDate.toISOString().split('T')[0],
                        hora, tipo, nota
                    })
                }).then(r => r.json()).then(res => { cortes[cortes.length-1].id = res.id; });
                --}}
                cortes.push({ fecha: new Date(selectedDate), hora: hora, tipo: tipo, nota: nota });
            }
        }

        var btn = document.getElementById('btn-guardar');
        var orig = btn.textContent;
        btn.textContent = '✓ Guardado'; btn.style.background = '#16A34A';
        setTimeout(function(){ btn.textContent = orig; btn.style.background = ''; }, 1800);
        setTimeout(function(){ cancelar(); actualizarKpis(); renderProximos(); renderCal(); }, 400);
    }

    function eliminarCorte(idx, e) {
        e.stopPropagation();
        if (editIdx === idx) cancelar();
        {{--
        TODO: DELETE al controller:
        fetch('{{ route("gerente.cortes.destroy", ":id") }}'.replace(':id', cortes[idx].id), {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        --}}
        cortes.splice(idx, 1);
        actualizarKpis(); renderProximos(); renderCal();
    }

    function crearItem(c) {
        var realIdx    = cortes.indexOf(c);
        var isEditando = editIdx === realIdx;
        var div = document.createElement('div');
        div.className = 'corte-item' + (isEditando ? ' editing' : '');
        var opts     = { day:'numeric', month:'short', year:'numeric' };
        var fechaStr = c.fecha.toLocaleDateString('es-MX', opts);
        var diasR    = Math.round((c.fecha - hoy) / (1000*60*60*24));
        var diasLabel = c.fecha < hoy ? 'Pasado' : diasR===0 ? 'Hoy' : diasR===1 ? 'Mañana' : 'En '+diasR+' días';
        div.innerHTML =
            '<div style="flex:1;min-width:0;">'
            + '<div class="corte-fecha">'+fechaStr+'</div>'
            + '<div style="display:flex;gap:6px;align-items:center;margin-top:4px;flex-wrap:wrap;">'
            + '<span style="font-family:DM Mono,monospace;font-size:.78rem;color:#64748B;">'+c.hora+' hrs</span>'
            + '<span class="badge '+TIPOS_BADGE[c.tipo]+'">'+TIPOS_LABEL[c.tipo]+'</span>'
            + '<span style="color:#94A3B8;font-size:.73rem;">'+diasLabel+'</span>'
            + '</div>'
            + (c.nota ? '<div style="font-size:.73rem;color:#94A3B8;margin-top:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+c.nota+'</div>' : '')
            + '</div>'
            + '<div class="corte-actions">'
            + '<button type="button" class="btn-icon edit'+(isEditando?' active-edit':'')+'" title="Editar" onclick="abrirEdicion('+realIdx+')">&#9998;</button>'
            + '<button type="button" class="btn-icon del" title="Eliminar" onclick="eliminarCorte('+realIdx+',event)">&#10005;</button>'
            + '</div>';
        return div;
    }

    function renderProximos() {
        var lista   = document.getElementById('proximos-list');
        var futuros = cortes.filter(function(c){ return c.fecha >= hoy; }).sort(function(a,b){ return a.fecha - b.fecha; });
        var pasados = cortes.filter(function(c){ return c.fecha <  hoy; }).sort(function(a,b){ return b.fecha - a.fecha; });
        if (cortes.length === 0) { lista.innerHTML = '<div class="empty-msg">Sin cortes programados.</div>'; return; }
        lista.innerHTML = '';
        if (futuros.length > 0) {
            var lbl = document.createElement('div'); lbl.className = 'list-sublabel'; lbl.textContent = 'Próximos';
            lista.appendChild(lbl);
            futuros.forEach(function(c){ lista.appendChild(crearItem(c)); });
        }
        if (pasados.length > 0) {
            var lbl2 = document.createElement('div'); lbl2.className = 'list-sublabel pasados'; lbl2.textContent = 'Anteriores';
            lista.appendChild(lbl2);
            pasados.forEach(function(c){ var item = crearItem(c); item.style.opacity = '.6'; lista.appendChild(item); });
        }
    }

    function actualizarKpis() {
        var futuros = cortes.filter(function(c){ return c.fecha >= hoy; }).sort(function(a,b){ return a.fecha - b.fecha; });
        var esMes   = cortes.filter(function(c){
            return c.fecha.getFullYear()===hoy.getFullYear() && c.fecha.getMonth()===hoy.getMonth();
        });
        document.getElementById('kpi-total').textContent = cortes.length;
        document.getElementById('kpi-mes').textContent   = esMes.length;
        if (futuros.length > 0) {
            var p = futuros[0];
            document.getElementById('kpi-proximo').textContent      = p.fecha.toLocaleDateString('es-MX', {day:'numeric',month:'short'});
            document.getElementById('kpi-proximo-hora').textContent = p.hora + ' hrs';
            var d = Math.round((p.fecha - hoy) / (1000*60*60*24));
            document.getElementById('kpi-dias').textContent = d === 0 ? 'Hoy' : d;
        } else {
            document.getElementById('kpi-proximo').textContent      = '—';
            document.getElementById('kpi-proximo-hora').textContent = 'Sin programar';
            document.getElementById('kpi-dias').textContent         = '—';
        }
    }

    function cambiarMes(d) {
        viewMonth += d;
        if (viewMonth > 11) { viewMonth = 0; viewYear++; }
        if (viewMonth < 0)  { viewMonth = 11; viewYear--; }
        renderCal();
    }

    renderCal(); actualizarKpis(); renderProximos();
</script>

@endsection