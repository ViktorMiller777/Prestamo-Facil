@extends('layouts.app')

@section('title', 'Gestión de prevales')

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

    /* ── Main card ── */
    .main-card {
        margin: 0 32px 16px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }
    .main-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #F1F5F9;
    }
    .main-card-title { font-size: 1rem; font-weight: 700; color: #0B1F3A; margin-bottom: 3px; }
    .main-card-sub   { font-size: .82rem; color: #2563EB; }

    /* ── Sección dentro del form ── */
    .section-divider {
        font-size: .70rem;
        font-weight: 700;
        letter-spacing: .09em;
        text-transform: uppercase;
        color: #94A3B8;
        padding: 18px 22px 6px 22px;
        border-top: 1px solid #F1F5F9;
    }
    .section-divider:first-child { border-top: none; padding-top: 0; }

    /* ── Form ── */
    .form-body { padding: 22px; display: flex; flex-direction: column; gap: 14px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
    .form-grid-4 { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 14px; }
    .field-label { font-size: .78rem; color: #64748B; margin-bottom: 5px; display: block; font-weight: 500; }

    .field-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .92rem;
        font-family: 'DM Sans', sans-serif;
        color: #0B1F3A;
        background: #F8FAFC;
        outline: none;
        transition: border-color .15s;
        box-sizing: border-box;
    }
    .field-input::placeholder { color: #CBD5E1; }
    .field-input:focus { border-color: #2563EB; background: #fff; }
    .field-input[readonly] { background: #F1F5F9; color: #64748B; cursor: not-allowed; }

    .field-select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .92rem;
        font-family: 'DM Sans', sans-serif;
        color: #0B1F3A;
        background: #F8FAFC;
        outline: none;
        cursor: pointer;
        box-sizing: border-box;
    }
    .field-select:focus { border-color: #2563EB; background: #fff; }

    /* ── Resumen calculado ── */
    .resumen-card {
        margin: 0 22px 14px 22px;
        background: #EFF6FF;
        border: 1.5px solid #BFDBFE;
        border-radius: 10px;
        padding: 14px 18px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    .resumen-item-label { font-size: .70rem; color: #3B82F6; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
    .resumen-item-val   { font-size: 1rem; font-weight: 700; font-family: 'DM Mono', monospace; color: #1E40AF; }

    /* ── Docs check ── */
    .docs-row {
        display: flex;
        gap: 12px;
        margin: 0 22px 14px 22px;
    }
    .doc-check {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: #F8FAFC;
        border: 1.5px solid #E2E8F0;
        border-radius: 9px;
        cursor: pointer;
        transition: border-color .15s, background .15s;
    }
    .doc-check:has(input:checked) { background: #F0FDF4; border-color: #BBF7D0; }
    .doc-check input { width: 16px; height: 16px; accent-color: #16A34A; cursor: pointer; }
    .doc-check-label { font-size: .88rem; font-weight: 600; color: #334155; cursor: pointer; }
    .doc-check:has(input:checked) .doc-check-label { color: #15803D; }

    /* ── Form footer ── */
    .form-footer {
        padding: 14px 22px;
        border-top: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cancel-hint { font-size: .82rem; color: #94A3B8; }
    .cancel-link {
        font-size: .82rem; color: #DC2626;
        cursor: pointer; text-decoration: underline;
        text-underline-offset: 2px;
    }
    .footer-btns { display: flex; gap: 10px; }

    .btn {
        padding: 9px 20px;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        font-family: 'DM Sans', sans-serif;
        transition: background .15s;
    }
    .btn-ghost   { background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; }
    .btn-ghost:hover { background: #F8FAFC; }
    .btn-primary { background: #2563EB; color: #fff; }
    .btn-primary:hover { background: #1D4ED8; }
    .btn-danger  { background: #FEE2E2; color: #B91C1C; border: 1.5px solid #FECACA; }
    .btn-danger:hover { background: #FECACA; }

    /* ── Modal overlay ── */
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
    .modal-sub   { font-size: .82rem; color: #94A3B8; margin-bottom: 18px; line-height: 1.5; }

    .folio-row { display: flex; align-items: flex-end; gap: 10px; margin-bottom: 14px; }
    .folio-row .field-wrap { flex: 1; }

    .vale-card {
        background: #F0FDF4;
        border: 1.5px solid #BBF7D0;
        border-radius: 9px;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        display: none;
    }
    .vale-card.show { display: flex; }
    .vale-name   { font-size: .92rem; font-weight: 700; color: #0B1F3A; }
    .vale-ref    { font-size: .78rem; color: #64748B; margin-top: 2px; font-family: 'DM Mono', monospace; }
    .vale-dist   { font-size: .78rem; color: #64748B; margin-top: 2px; }
    .vale-amount { font-family: 'DM Mono', monospace; font-size: 1rem; font-weight: 700; color: #DC2626; }

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

    .field-ta {
        width: 100%;
        height: 72px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: .85rem;
        font-family: 'DM Sans', sans-serif;
        color: #334155;
        background: #F8FAFC;
        resize: none;
        outline: none;
        box-sizing: border-box;
    }
    .field-ta:focus { border-color: #2563EB; background: #fff; }

    .hint { font-size: .74rem; color: #94A3B8; margin-top: 4px; }
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <div>
            <h1 class="topbar-title">Gestión de prevales</h1>
            <div class="topbar-date">
                {{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd D [de] MMMM, YYYY') }}
            </div>
        </div>
    </div>

    {{-- ── FORMULARIO ── --}}
    {{--
        Al guardar hace POST con estado = 'prevale' fijo (tabla Vales)
        El controller inserta en Vales + Detalle_vale
    --}}
    <div class="main-card">
        <div class="main-card-header">
            <div class="main-card-title">Capturar nuevo prevale</div>
            <div class="main-card-sub">Completa los datos del cliente y del vale — se guardará con estado "prevale"</div>
        </div>

        {{-- Sección 1: Vales --}}
        <div class="section-divider">Datos del vale</div>
        <div class="form-body" style="padding-top:10px;">

            <div class="form-grid-2">
                <div>
                    <label class="field-label">Distribuidora</label>
                    {{--
                        Vales.distribuidor_id
                        SELECT desde tabla Distribuidoras: IDdistribuidor_id, nombre
                        Al seleccionar, también guarda nombre_distribuidora en Detalle_vale
                    --}}
                    <select class="field-select" id="select-distribuidora" onchange="onDistribuidoraChange(this)">
                        <option value="">Seleccionar distribuidora...</option>
                        @foreach($distribuidoras ?? [] as $d)
                            <option value="{{ $d->IDdistribuidor_id }}" data-nombre="{{ $d->nombre }}">
                                {{ $d->nombre }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Campo oculto para guardar nombre_distribuidora en Detalle_vale --}}
                    <input type="hidden" id="nombre-distribuidora" name="nombre_distribuidora" />
                </div>
                <div>
                    <label class="field-label">Producto</label>
                    {{--
                        Vales.producto_id
                        SELECT desde tabla Productos: ID, nombre, monto, quincenas, interes_quincenal, seguro, comision
                        Al seleccionar se auto-llenan los campos de Detalle_vale
                    --}}
                    <select class="field-select" id="select-producto" onchange="onProductoChange(this)">
                        <option value="">Seleccionar producto...</option>
                        @foreach($productos ?? [] as $p)
                            <option value="{{ $p->ID }}"
                                data-monto="{{ $p->monto }}"
                                data-quincenas="{{ $p->quincenas }}"
                                data-interes="{{ $p->interes_quincenal }}"
                                data-seguro="{{ $p->seguro }}"
                                data-comision="{{ $p->comision }}">
                                {{ $p->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-grid-2">
                <div>
                    <label class="field-label">Cliente</label>
                    {{--
                        Vales.cliente_id
                        SELECT desde tabla Clientes: cliente_id, nombre completo
                        También guarda nombre_cliente en Detalle_vale
                    --}}
                    <select class="field-select" id="select-cliente" onchange="onClienteChange(this)">
                        <option value="">Seleccionar cliente...</option>
                        @foreach($clientes ?? [] as $c)
                            <option value="{{ $c->cliente_id }}"
                                data-nombre="{{ $c->nombre }} {{ $c->apellido }}"
                                data-ine="{{ $c->INE }}"
                                data-domicilio="{{ $c->comprobante_domicilio }}">
                                {{ $c->nombre }} {{ $c->apellido }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" id="nombre-cliente" name="nombre_cliente" />
                </div>
                <div>
                    <label class="field-label">Fecha de emisión</label>
                    {{-- Vales.fecha_emision --}}
                    <input class="field-input" type="date" name="fecha_emision"
                           value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
                </div>
            </div>

        </div>

        {{-- Sección 2: Detalle_vale — se auto-llena al escoger producto --}}
        <div class="section-divider">Detalle del vale</div>
        <div class="form-body" style="padding-top:10px; padding-bottom:6px;">

            <div class="form-grid-4">
                <div>
                    <label class="field-label">Monto</label>
                    {{-- Detalle_vale.monto — viene del producto --}}
                    <input class="field-input" type="text" id="campo-monto" name="monto"
                           placeholder="$0.00" readonly />
                </div>
                <div>
                    <label class="field-label">Quincenas</label>
                    {{-- Detalle_vale.quincenas --}}
                    <input class="field-input" type="text" id="campo-quincenas" name="quincenas"
                           placeholder="—" readonly />
                </div>
                <div>
                    <label class="field-label">Interés quincenal</label>
                    {{-- Detalle_vale.interes_quincenal --}}
                    <input class="field-input" type="text" id="campo-interes" name="interes_quincenal"
                           placeholder="—" readonly />
                </div>
                <div>
                    <label class="field-label">Seguro</label>
                    {{-- Detalle_vale.seguro --}}
                    <input class="field-input" type="text" id="campo-seguro" name="seguro"
                           placeholder="$0.00" readonly />
                </div>
            </div>

        </div>

        {{-- Resumen calculado automáticamente --}}
        <div class="resumen-card" id="resumen-card" style="display:none;">
            <div>
                <div class="resumen-item-label">% Comisión</div>
                {{-- Detalle_vale.porcentaje_comision --}}
                <div class="resumen-item-val" id="r-comision">—</div>
            </div>
            <div>
                <div class="resumen-item-label">Comisión calculada</div>
                {{-- Detalle_vale.monto_comision_calculada = monto * porcentaje_comision --}}
                <div class="resumen-item-val" id="r-comision-calc">—</div>
            </div>
            <div>
                <div class="resumen-item-label">Pago quincenal total</div>
                {{-- monto + interes_quincenal + seguro --}}
                <div class="resumen-item-val" id="r-pago-quin">—</div>
            </div>
            <div>
                <div class="resumen-item-label">Total a pagar</div>
                {{-- (monto + interes_quincenal + seguro) * quincenas --}}
                <div class="resumen-item-val" id="r-total">—</div>
            </div>
        </div>

        

        {{-- Footer --}}
        <div class="form-footer">
            <div>
                <span class="cancel-hint">¿Dudas con un vale existente? </span>
                <span class="cancel-link" onclick="document.getElementById('modal').classList.add('show')">
                    Cancelar vale existente
                </span>
            </div>
            <div class="footer-btns">
                <button class="btn btn-ghost" onclick="limpiarForm()">Limpiar</button>
                {{--
                    El controller recibe:
                    Vales: distribuidor_id, cliente_id, producto_id, fecha_emision, estado='prevale'
                    Detalle_vale: monto, porcentaje_comision, monto_comision_calculada,
                                  interes_quincenal, quincenas, seguro,
                                  nombre_cliente, nombre_distribuidora, fecha_emision, producto_folio
                --}}
                <button class="btn btn-primary" id="btn-registrar" disabled onclick="registrarVale()">
                    + Registrar prevale
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ── MODAL CANCELAR VALE ── --}}
<div id="modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-title">Cancelar vale existente</div>
        <div class="modal-sub">
            Busca el folio del vale e indica el motivo. La distribuidora recibirá una notificación.
        </div>

        <div class="folio-row">
            <div class="field-wrap">
                <label class="field-label">Folio del vale</label>
                {{-- Vales.IDfolio --}}
                <input class="field-input" type="text" placeholder="Ej. V-0051" id="folio-input" />
            </div>
            <button class="btn btn-primary" onclick="buscarVale()">Buscar →</button>
        </div>

        {{--
            Vale encontrado — datos de Vales JOIN Detalle_vale:
            nombre_cliente, nombre_distribuidora, IDfolio, monto
            Se llena desde BD al buscar el folio
        --}}
        <div class="vale-card" id="vale-encontrado">
            <div>
                <div class="vale-name" id="vale-nombre-cliente">—</div>
                <div class="vale-ref" id="vale-folio">—</div>
                <div class="vale-dist" id="vale-distribuidora">—</div>
            </div>
            <div class="vale-amount" id="vale-monto">—</div>
        </div>

        <div style="margin-bottom:12px;">
            <label class="field-label">Motivo de cancelación</label>
            {{--
                No existe campo en el diagrama — el controller debe manejarlo
                como campo adicional o en una tabla de log aparte
            --}}
            <select class="field-select" id="select-motivo" style="background:#F8FAFC;color:#0B1F3A;">
                <option value="">Seleccionar motivo...</option>
                <option>Referencia bancaria no encontrada</option>
                <option>Monto no coincide con el banco</option>
                <option>Cliente con adeudo activo</option>
                <option>Distribuidora en estado moroso</option>
                <option>Datos del cliente incorrectos</option>
                <option>Documentos incompletos (INE / comprobante domicilio)</option>
                <option>Otro</option>
            </select>
        </div>

        <div>
            <label class="field-label">Comentario adicional (opcional)</label>
            <textarea class="field-ta" id="ta-comentario"
                      placeholder="Descripción adicional para la distribuidora..."></textarea>
        </div>

        <div class="alert-warn">
            La distribuidora recibirá una notificación con el motivo indicado. El vale cambiará de estado a cancelado.
        </div>

        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="cerrarModal()">Cerrar</button>
            <button class="btn btn-danger" onclick="confirmarCancelacion()">Confirmar cancelación</button>
        </div>
    </div>
</div>

<script>
    // Al seleccionar distribuidora → guarda nombre_distribuidora (Detalle_vale)
    function onDistribuidoraChange(sel) {
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('nombre-distribuidora').value = opt.dataset.nombre || '';
        validarFormulario();
    }

    // Al seleccionar cliente → verifica INE y comprobante_domicilio (Clientes)
    function onClienteChange(sel) {
        const opt = sel.options[sel.selectedIndex];
        document.getElementById('nombre-cliente').value = opt.dataset.nombre || '';

        const tieneINE       = opt.dataset.ine       && opt.dataset.ine !== 'null';
        const tieneDomicilio = opt.dataset.domicilio  && opt.dataset.domicilio !== 'null';

        const chkIne = document.getElementById('chk-ine');
        const chkDom = document.getElementById('chk-domicilio');

        chkIne.checked = tieneINE;
        chkDom.checked = tieneDomicilio;

        validarFormulario();
    }

    // Al seleccionar producto → auto-llena campos de Detalle_vale y calcula resumen
    function onProductoChange(sel) {
        const opt = sel.options[sel.selectedIndex];

        const monto      = parseFloat(opt.dataset.monto)    || 0;
        const quincenas  = parseInt(opt.dataset.quincenas)  || 0;
        const interes    = parseFloat(opt.dataset.interes)  || 0;
        const seguro     = parseFloat(opt.dataset.seguro)   || 0;
        const comision   = parseFloat(opt.dataset.comision) || 0;

        document.getElementById('campo-monto').value     = monto     ? '$' + monto.toFixed(2)    : '';
        document.getElementById('campo-quincenas').value = quincenas ? quincenas + ' quincenas'  : '';
        document.getElementById('campo-interes').value   = interes   ? '$' + interes.toFixed(2)  : '';
        document.getElementById('campo-seguro').value    = seguro    ? '$' + seguro.toFixed(2)   : '';

        if (monto) {
            const comisionCalc = monto * (comision / 100);
            const pagoQuin     = monto + interes + seguro;
            const total        = pagoQuin * quincenas;

            document.getElementById('r-comision').textContent      = comision.toFixed(2) + '%';
            document.getElementById('r-comision-calc').textContent = '$' + comisionCalc.toFixed(2);
            document.getElementById('r-pago-quin').textContent     = '$' + pagoQuin.toFixed(2);
            document.getElementById('r-total').textContent         = '$' + total.toFixed(2);
            document.getElementById('resumen-card').style.display  = 'grid';
        } else {
            document.getElementById('resumen-card').style.display = 'none';
        }

        validarFormulario();
    }

    // Habilitar botón solo si hay distribuidora, cliente, producto e INE + domicilio
    function validarFormulario() {
        const dist     = document.getElementById('select-distribuidora').value;
        const cliente  = document.getElementById('select-cliente').value;
        const producto = document.getElementById('select-producto').value;
        const ine      = document.getElementById('chk-ine').checked;
        const dom      = document.getElementById('chk-domicilio').checked;

        document.getElementById('btn-registrar').disabled = !(dist && cliente && producto && ine && dom);
    }

    function limpiarForm() {
        document.getElementById('select-distribuidora').value = '';
        document.getElementById('select-cliente').value       = '';
        document.getElementById('select-producto').value      = '';
        document.getElementById('nombre-distribuidora').value = '';
        document.getElementById('nombre-cliente').value       = '';
        document.getElementById('campo-monto').value          = '';
        document.getElementById('campo-quincenas').value      = '';
        document.getElementById('campo-interes').value        = '';
        document.getElementById('campo-seguro').value         = '';
        document.getElementById('chk-ine').checked           = false;
        document.getElementById('chk-domicilio').checked     = false;
        document.getElementById('resumen-card').style.display = 'none';
        document.getElementById('btn-registrar').disabled    = true;
    }

    function registrarVale() {
        // El form hace POST al controller con todos los campos
        // El controller inserta en Vales (estado='prevale') y Detalle_vale
    }

    // Modal cancelación
    function cerrarModal() {
        document.getElementById('modal').classList.remove('show');
        document.getElementById('vale-encontrado').classList.remove('show');
        document.getElementById('folio-input').value    = '';
        document.getElementById('select-motivo').value  = '';
        document.getElementById('ta-comentario').value  = '';
    }

    function buscarVale() {
        // El controller busca en Vales JOIN Detalle_vale por IDfolio
        // y devuelve: nombre_cliente, nombre_distribuidora, IDfolio, monto
        // Aquí se llena el vale-card con los datos recibidos
        const folio = document.getElementById('folio-input').value.trim();
        if (!folio) return;

        // Cuando el backend responda:
        // document.getElementById('vale-nombre-cliente').textContent = data.nombre_cliente;
        // document.getElementById('vale-folio').textContent          = 'Folio: ' + data.IDfolio;
        // document.getElementById('vale-distribuidora').textContent  = data.nombre_distribuidora;
        // document.getElementById('vale-monto').textContent          = '$' + data.monto;
        // document.getElementById('vale-encontrado').classList.add('show');
    }

    function confirmarCancelacion() {
        const motivo = document.getElementById('select-motivo').value;
        if (!motivo) { alert('Selecciona un motivo de cancelación.'); return; }
        // POST al controller: IDfolio, motivo, comentario → cambia Vales.estado a 'cancelado'
        cerrarModal();
    }
</script>

@endsection