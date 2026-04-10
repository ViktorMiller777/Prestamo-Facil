@extends('layouts.app')

@section('title', 'Fechas de corte')
@section('page-title', 'Gestión de fechas de corte')
@section('page-sub', 'Calendario programable por día y hora · Solo acceso Gerente')

@push('styles')
<style>
    .layout-cortes { display: grid; grid-template-columns: 1fr 300px; gap: 14px; }
    .cal-nav { display: flex; align-items: center; gap: 8px; }
    .cal-nav-btn { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 4px 10px; cursor: pointer; color: #64748B; font-size: 12px; font-weight: 500; }
    .cal-nav-btn:hover { background: #EFF6FF; color: #2563EB; border-color: #93C5FD; }
    .cal-month-label { font-size: 13px; font-weight: 500; color: #0B1F3A; min-width: 130px; text-align: center; }
    .cal-grid-wrap { padding: 12px 16px; }
    .cal-weekdays { display: grid; grid-template-columns: repeat(7,1fr); gap: 3px; margin-bottom: 4px; }
    .cal-wd { text-align: center; font-size: 9px; font-weight: 700; letter-spacing: .05em; color: #94A3B8; text-transform: uppercase; padding: 3px 0; }
    .cal-days { display: grid; grid-template-columns: repeat(7,1fr); gap: 3px; }
    .cal-day { aspect-ratio: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 6px; cursor: pointer; border: 1px solid transparent; font-size: 11px; font-weight: 500; color: #334155; transition: background .12s; }
    .cal-day:hover { background: #F0F7FF; border-color: #BFDBFE; }
    .cal-day.other-month { color: #CBD5E1; pointer-events: none; }
    .cal-day.today { background: #EFF6FF; border-color: #93C5FD; color: #1D4ED8; font-weight: 700; }
    .cal-day.has-corte { background: #EFF6FF; border-color: #2563EB; }
    .cal-day.has-corte .dnum { color: #1D4ED8; font-weight: 700; }
    .cal-day.editing { background: #FEF3C7; border-color: #D97706; }
    .cal-day.editing .dnum { color: #92400E; font-weight: 700; }
    .cal-day.selected { background: #2563EB; border-color: #2563EB; }
    .cal-day.selected .dnum { color: #fff; }
    .cal-day.past { opacity: .4; cursor: default; pointer-events: none; }
    .cdot { width: 4px; height: 4px; border-radius: 50%; background: #2563EB; margin-top: 1px; }
    .cal-day.editing .cdot { background: #D97706; }
    .dnum { font-size: 11px; }
    .legend-row { display: flex; gap: 10px; align-items: center; padding: 8px 16px 12px; border-top: 1px solid #F8FAFC; flex-wrap: wrap; }
    .leg-item { display: flex; align-items: center; gap: 4px; font-size: 10px; color: #64748B; }
    .leg-dot { width: 8px; height: 8px; border-radius: 2px; flex-shrink: 0; }
    .side-panel { background: #fff; border: 1px solid #E2E8F0; border-radius: 10px; overflow: hidden; display: flex; flex-direction: column; }
    .side-sec { padding: 14px; border-bottom: 1px solid #F1F5F9; }
    .side-sec:last-child { border-bottom: none; }
    .side-label { font-size: 9px; font-weight: 700; letter-spacing: .07em; text-transform: uppercase; color: #94A3B8; margin-bottom: 10px; }
    .fi { width: 100%; padding: 7px 10px; border: 1px solid #E2E8F0; border-radius: 6px; font-size: 11px; font-family: 'DM Mono', monospace; color: #0B1F3A; outline: none; transition: border-color .12s; }
    .fi:focus { border-color: #2563EB; }
    .fi.em { border-color: #D97706; background: #FFFBEB; }
    .frow { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 8px; }
    .flabel { font-size: 10px; font-weight: 500; color: #64748B; margin-bottom: 4px; display: block; }
    .mode-tag { display: inline-flex; align-items: center; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 99px; margin-bottom: 8px; }
    .mode-tag.new  { background: #DBEAFE; color: #1D4ED8; }
    .mode-tag.edit { background: #FEF3C7; color: #92400E; }
    .alert-i { background: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 6px; padding: 8px 10px; font-size: 10px; color: #1D4ED8; line-height: 1.5; margin-bottom: 8px; }
    .alert-w { background: #FFFBEB; border: 1px solid #FDE68A; border-radius: 6px; padding: 8px 10px; font-size: 10px; color: #92400E; line-height: 1.5; margin-top: 8px; }
    .alert-e { background: #FEF3C7; border: 1px solid #FDE68A; border-radius: 6px; padding: 8px 10px; font-size: 10px; color: #92400E; line-height: 1.5; margin-top: 8px; }
    .btn-row-s { display: flex; gap: 6px; padding: 10px 14px; border-top: 1px solid #F1F5F9; background: #F8FAFC; }
    .sbtn { padding: 6px 14px; border-radius: 6px; font-size: 11px; font-weight: 500; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background .12s; }
    .sbtn-p { background: #2563EB; color: #fff; } .sbtn-p:hover { background: #1D4ED8; }
    .sbtn-w { background: #D97706; color: #fff; } .sbtn-w:hover { background: #B45309; }
    .sbtn-g { background: #fff; color: #64748B; border: 1px solid #E2E8F0; } .sbtn-g:hover { background: #F8FAFC; }
    .sbtn-s { background: #16A34A; color: #fff; }
    .corte-list { overflow-y: auto; max-height: 240px; }
    .corte-item { display: flex; align-items: flex-start; justify-content: space-between; padding: 8px 10px; border-radius: 7px; border: 1px solid #E2E8F0; margin-bottom: 6px; transition: all .12s; }
    .corte-item:hover { border-color: #93C5FD; background: #F8FAFC; }
    .corte-item.editing { border-color: #D97706; background: #FFFBEB; }
    .corte-item:last-child { margin-bottom: 0; }
    .corte-fecha { font-family: 'DM Mono', monospace; font-size: 11px; font-weight: 700; color: #0B1F3A; }
    .ci-actions { display: flex; gap: 3px; flex-shrink: 0; }
    .bico { background: none; border: 1px solid #E2E8F0; border-radius: 5px; cursor: pointer; padding: 3px 6px; font-size: 11px; color: #94A3B8; transition: all .12s; }
    .bico.e:hover { color: #D97706; background: #FFFBEB; border-color: #FDE68A; }
    .bico.d:hover { color: #DC2626; background: #FEE2E2; border-color: #FECACA; }
    .bico.ae { color: #D97706; background: #FEF3C7; border-color: #FDE68A; }
    .ci-badge { display: inline-flex; align-items: center; padding: 1px 7px; border-radius: 99px; font-size: 9px; font-weight: 700; }
    .bb { background: #DBEAFE; color: #1D4ED8; }
    .bg { background: #DCFCE7; color: #15803D; }
    .ba { background: #FEF3C7; color: #B45309; }
    .empty-msg { text-align: center; color: #94A3B8; font-size: 11px; padding: 16px 0; }
    .sublbl { font-size: 9px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: #94A3B8; margin-bottom: 6px; }
    .sublbl.p { color: #CBD5E1; margin-top: 10px; }
</style>
@endpush

@section('content')

{{-- ── KPIs ── --}}
<div class="pf-metrics">
    <div class="pf-metric pf-metric-accent">
        <div class="pf-metric-label">Próximo corte</div>
        {{-- TODO: calcular desde $cortes --}}
        <div class="pf-metric-value" id="kk-prox" style="font-size:16px;">—</div>
        <div class="pf-metric-delta blue" id="kk-hora">Sin programar</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Este mes</div>
        <div class="pf-metric-value" id="kk-mes">{{ $cortesMes ?? 0 }}</div>
        <div class="pf-metric-delta">Cortes programados</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Días al próximo</div>
        <div class="pf-metric-value" id="kk-dias">—</div>
        <div class="pf-metric-delta amber">Días restantes</div>
    </div>
    <div class="pf-metric">
        <div class="pf-metric-label">Total programados</div>
        <div class="pf-metric-value" id="kk-total">{{ $totalCortes ?? 0 }}</div>
        <div class="pf-metric-delta blue">En todos los meses</div>
    </div>
</div>

{{-- ── LAYOUT DOS COLUMNAS ── --}}
<div class="layout-cortes">

    {{-- Calendario --}}
    <div class="pf-card">
        <div class="pf-card-header">
            <span class="pf-card-title">Calendario de cortes</span>
            <div class="cal-nav">
                <button type="button" class="cal-nav-btn" onclick="cambiarMes(-1)">&#8592;</button>
                <span class="cal-month-label" id="cal-label"></span>
                <button type="button" class="cal-nav-btn" onclick="cambiarMes(1)">&#8594;</button>
            </div>
        </div>
        <div class="cal-grid-wrap">
            <div class="cal-weekdays">
                <div class="cal-wd">Dom</div><div class="cal-wd">Lun</div><div class="cal-wd">Mar</div>
                <div class="cal-wd">Mié</div><div class="cal-wd">Jue</div><div class="cal-wd">Vie</div>
                <div class="cal-wd">Sáb</div>
            </div>
            <div class="cal-days" id="cal-days"></div>
        </div>
        <div class="legend-row">
            <div class="leg-item"><div class="leg-dot" style="background:#DBEAFE;border:1px solid #2563EB;"></div>Corte programado</div>
            <div class="leg-item"><div class="leg-dot" style="background:#FEF3C7;border:1px solid #D97706;"></div>Editando</div>
            <div class="leg-item"><div class="leg-dot" style="background:#EFF6FF;border:1px solid #93C5FD;"></div>Hoy</div>
            <div class="leg-item"><div class="leg-dot" style="background:#2563EB;"></div>Nuevo</div>
        </div>
    </div>

    {{-- Panel lateral --}}
    <div class="side-panel">
        <div class="side-sec" style="flex-shrink:0;">
            <div class="side-label">Programar corte</div>
            <div id="no-sel" class="alert-i">Selecciona un día libre para crear, o usa el ícono de editar en un corte existente.</div>
            <div id="form-c" style="display:none;">
                <div class="mode-tag new" id="mtag">+ Nuevo corte</div>
                <div class="frow">
                    <div>
                        <label class="flabel">Fecha</label>
                        <input type="text" class="fi" id="c-fecha" name="fecha" readonly>
                    </div>
                    <div>
                        <label class="flabel">Hora</label>
                        {{-- TODO: name="hora" → tabla Conciliacion --}}
                        <input type="time" class="fi" id="c-hora" name="hora" value="23:59">
                    </div>
                </div>
                <label class="flabel">Tipo de corte</label>
                {{-- TODO: name="tipo" → tabla Conciliacion --}}
                <select class="fi" id="c-tipo" name="tipo" style="margin-bottom:8px;font-family:'DM Sans',sans-serif;font-size:11px;">
                    <option value="quincenal">Quincenal</option>
                    <option value="mensual">Mensual</option>
                    <option value="especial">Especial / extraordinario</option>
                </select>
                <label class="flabel">Nota (opcional)</label>
                <input type="text" class="fi" id="c-nota" name="nota" placeholder="Ej. Fin de mes...">
                <div id="c-alerta" class="alert-w">Al guardar se notificará a todas las distribuidoras activas.</div>
            </div>
        </div>

        <div id="btn-row-c" class="btn-row-s" style="display:none;">
            <button type="button" class="sbtn sbtn-g" onclick="cancelarC()">Cancelar</button>
            <button type="button" class="sbtn sbtn-p" id="btn-gc" onclick="guardarC()">Guardar corte</button>
        </div>

        <div class="side-sec" style="flex:1;overflow:hidden;display:flex;flex-direction:column;min-height:0;">
            <div class="side-label">Cortes programados</div>
            <div class="corte-list" id="corte-list">
                <div class="empty-msg">Sin cortes programados.</div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    var hoy = new Date(); hoy.setHours(0,0,0,0);
    var vY = hoy.getFullYear(), vM = hoy.getMonth();
    var selDate = null, editIdx = null;
    var MESES = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    var TB = { quincenal:'bb', mensual:'bg', especial:'ba' };
    var TL = { quincenal:'Quincenal', mensual:'Mensual', especial:'Especial' };

    {{--
    TODO: reemplazar por datos reales:
    var cortes = {!! json_encode($cortes->map(fn($c) => [
        'id'    => $c->id,
        'fecha' => $c->fecha_limite_pago,
        'hora'  => $c->hora,
        'tipo'  => $c->tipo,
        'nota'  => $c->nota,
    ])) !!};
    cortes.forEach(function(c){ c.fecha = new Date(c.fecha); c.fecha.setHours(0,0,0,0); });
    --}}
    var cortes = [];

    function mF(a,b){ return a.getFullYear()===b.getFullYear()&&a.getMonth()===b.getMonth()&&a.getDate()===b.getDate(); }

    function renderCal() {
        document.getElementById('cal-label').textContent = MESES[vM] + ' ' + vY;
        var first=new Date(vY,vM,1).getDay(), dias=new Date(vY,vM+1,0).getDate(), prev=new Date(vY,vM,0).getDate();
        var c=document.getElementById('cal-days'); c.innerHTML='';
        var total=(first+dias)<=35?35:42;
        for(var i=0;i<total;i++){
            var d,m,a,other=false;
            if(i<first){d=prev-first+i+1;m=vM-1;a=vY;other=true;}
            else if(i>=first+dias){d=i-first-dias+1;m=vM+1;a=vY;other=true;}
            else{d=i-first+1;m=vM;a=vY;}
            var f=new Date(a,m,d); f.setHours(0,0,0,0);
            var isPast=f<hoy, isToday=mF(f,hoy), isSel=selDate&&mF(f,selDate);
            var ce=cortes.find(function(x){return mF(x.fecha,f);});
            var isEd=editIdx!==null&&ce&&cortes.indexOf(ce)===editIdx;
            var day=document.createElement('div'); day.className='cal-day';
            if(other){ day.classList.add('other-month'); }
            else {
                if(isPast&&!isToday) day.classList.add('past');
                if(isToday) day.classList.add('today');
                if(isEd) day.classList.add('editing');
                else if(isSel&&editIdx===null) day.classList.add('selected');
                else if(ce) day.classList.add('has-corte');
            }
            var dn=document.createElement('div'); dn.className='dnum'; dn.textContent=d; day.appendChild(dn);
            if(ce&&!other){ var dot=document.createElement('div'); dot.className='cdot'; day.appendChild(dot); }
            if(!other&&(!isPast||isToday)){
                (function(ff,cc){ day.addEventListener('click',function(){ if(cc) abrirEd(cortes.indexOf(cc)); else selDia(ff); }); })(f, ce||null);
            }
            c.appendChild(day);
        }
    }

    function selDia(f) {
        if(editIdx!==null) return;
        selDate=new Date(f); selDate.setHours(0,0,0,0);
        setMode('new'); llenarF(selDate,null); renderCal();
    }

    function abrirEd(idx) {
        editIdx=idx; selDate=null;
        setMode('edit'); llenarF(cortes[idx].fecha,cortes[idx]); renderCal(); renderCortes();
    }

    function setMode(mode) {
        document.getElementById('no-sel').style.display='none';
        document.getElementById('form-c').style.display='block';
        document.getElementById('btn-row-c').style.display='flex';
        var tag=document.getElementById('mtag'), btn=document.getElementById('btn-gc'), al=document.getElementById('c-alerta');
        var h=document.getElementById('c-hora'), t=document.getElementById('c-tipo'), n=document.getElementById('c-nota');
        if(mode==='edit'){
            tag.className='mode-tag edit'; tag.textContent='Editando corte existente';
            btn.className='sbtn sbtn-w'; btn.textContent='Guardar cambios';
            al.className='alert-e'; al.textContent='Modificando un corte existente. Las distribuidoras serán notificadas con la actualización.';
            h.classList.add('em'); t.classList.add('em'); n.classList.add('em');
        } else {
            tag.className='mode-tag new'; tag.textContent='+ Nuevo corte';
            btn.className='sbtn sbtn-p'; btn.textContent='Guardar corte';
            al.className='alert-w'; al.textContent='Al guardar se notificará a todas las distribuidoras activas.';
            h.classList.remove('em'); t.classList.remove('em'); n.classList.remove('em');
        }
    }

    function llenarF(f,c) {
        var dd=String(f.getDate()).padStart(2,'0'), mm=String(f.getMonth()+1).padStart(2,'0'), yy=f.getFullYear();
        document.getElementById('c-fecha').value=dd+'/'+mm+'/'+yy;
        document.getElementById('c-hora').value=c?c.hora:'23:59';
        document.getElementById('c-tipo').value=c?c.tipo:'quincenal';
        document.getElementById('c-nota').value=c?(c.nota||''):'';
    }

    function cancelarC() {
        editIdx=null; selDate=null;
        document.getElementById('no-sel').style.display='block';
        document.getElementById('form-c').style.display='none';
        document.getElementById('btn-row-c').style.display='none';
        renderCal(); renderCortes();
    }

    function guardarC() {
        var hora=document.getElementById('c-hora').value||'23:59';
        var tipo=document.getElementById('c-tipo').value;
        var nota=document.getElementById('c-nota').value;
        if(editIdx!==null){
            {{--
            TODO PATCH: fetch('{{ route("gerente.cortes.update", ":id") }}'.replace(':id', cortes[editIdx].id), {
                method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body:JSON.stringify({hora,tipo,nota})
            });
            --}}
            cortes[editIdx].hora=hora; cortes[editIdx].tipo=tipo; cortes[editIdx].nota=nota;
        } else if(selDate) {
            var ya=cortes.some(function(c){return mF(c.fecha,selDate);});
            if(!ya){
                {{--
                TODO POST: fetch('{{ route("gerente.cortes.store") }}', {
                    method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                    body:JSON.stringify({ fecha:selDate.toISOString().split('T')[0], hora, tipo, nota })
                }).then(r=>r.json()).then(res=>{ cortes[cortes.length-1].id=res.id; });
                --}}
                cortes.push({fecha:new Date(selDate),hora:hora,tipo:tipo,nota:nota});
            }
        }
        var btn=document.getElementById('btn-gc'), orig=btn.textContent;
        btn.textContent='✓ Guardado'; btn.className='sbtn sbtn-s';
        setTimeout(function(){btn.textContent=orig;btn.className=(editIdx!==null?'sbtn sbtn-w':'sbtn sbtn-p');},1600);
        setTimeout(function(){cancelarC();actualizarKpis();renderCortes();renderCal();},350);
    }

    function eliminarC(idx,e) {
        e.stopPropagation();
        if(editIdx===idx) cancelarC();
        {{--
        TODO DELETE: fetch('{{ route("gerente.cortes.destroy", ":id") }}'.replace(':id', cortes[idx].id), {
            method:'DELETE', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
        });
        --}}
        cortes.splice(idx,1); actualizarKpis(); renderCortes(); renderCal();
    }

    function crearCI(c) {
        var ri=cortes.indexOf(c), isEd=editIdx===ri;
        var div=document.createElement('div'); div.className='corte-item'+(isEd?' editing':'');
        var fs=c.fecha.toLocaleDateString('es-MX',{day:'numeric',month:'short',year:'numeric'});
        var dr=Math.round((c.fecha-hoy)/(1000*60*60*24));
        var dl=c.fecha<hoy?'Pasado':dr===0?'Hoy':dr===1?'Mañana':'En '+dr+' días';
        div.innerHTML='<div style="flex:1;min-width:0;">'
            +'<div class="corte-fecha">'+fs+'</div>'
            +'<div style="display:flex;gap:5px;align-items:center;margin-top:3px;flex-wrap:wrap;">'
            +'<span style="font-family:DM Mono,monospace;font-size:10px;color:#64748B;">'+c.hora+' hrs</span>'
            +'<span class="ci-badge '+TB[c.tipo]+'">'+TL[c.tipo]+'</span>'
            +'<span style="color:#94A3B8;font-size:10px;">'+dl+'</span></div>'
            +(c.nota?'<div style="font-size:10px;color:#94A3B8;margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">'+c.nota+'</div>':'')
            +'</div><div class="ci-actions">'
            +'<button type="button" class="bico e'+(isEd?' ae':'')+'" onclick="abrirEd('+ri+')">&#9998;</button>'
            +'<button type="button" class="bico d" onclick="eliminarC('+ri+',event)">&#10005;</button>'
            +'</div>';
        return div;
    }

    function renderCortes() {
        var l=document.getElementById('corte-list');
        var fut=cortes.filter(function(c){return c.fecha>=hoy;}).sort(function(a,b){return a.fecha-b.fecha;});
        var pas=cortes.filter(function(c){return c.fecha<hoy;}).sort(function(a,b){return b.fecha-a.fecha;});
        if(cortes.length===0){l.innerHTML='<div class="empty-msg">Sin cortes programados.</div>';return;}
        l.innerHTML='';
        if(fut.length>0){var s=document.createElement('div');s.className='sublbl';s.textContent='Próximos';l.appendChild(s);fut.forEach(function(c){l.appendChild(crearCI(c));});}
        if(pas.length>0){var s2=document.createElement('div');s2.className='sublbl p';s2.textContent='Anteriores';l.appendChild(s2);pas.forEach(function(c){var it=crearCI(c);it.style.opacity='.6';l.appendChild(it);});}
    }

    function actualizarKpis() {
        var fut=cortes.filter(function(c){return c.fecha>=hoy;}).sort(function(a,b){return a.fecha-b.fecha;});
        var em=cortes.filter(function(c){return c.fecha.getFullYear()===hoy.getFullYear()&&c.fecha.getMonth()===hoy.getMonth();});
        document.getElementById('kk-total').textContent=cortes.length;
        document.getElementById('kk-mes').textContent=em.length;
        if(fut.length>0){
            var p=fut[0];
            document.getElementById('kk-prox').textContent=p.fecha.toLocaleDateString('es-MX',{day:'numeric',month:'short'});
            document.getElementById('kk-hora').textContent=p.hora+' hrs';
            var d=Math.round((p.fecha-hoy)/(1000*60*60*24));
            document.getElementById('kk-dias').textContent=d===0?'Hoy':d;
        } else {
            document.getElementById('kk-prox').textContent='—';
            document.getElementById('kk-hora').textContent='Sin programar';
            document.getElementById('kk-dias').textContent='—';
        }
    }

    function cambiarMes(d) {
        vM+=d;
        if(vM>11){vM=0;vY++;} if(vM<0){vM=11;vY--;}
        renderCal();
    }

    renderCal(); actualizarKpis(); renderCortes();
</script>
@endpush