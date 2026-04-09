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

    /* ── Form ── */
    .form-body { padding: 22px; display: flex; flex-direction: column; gap: 14px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
    .field-label { font-size: .78rem; color: #64748B; margin-bottom: 5px; display: block; font-weight: 500; }

    .field-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .92rem;
        font-family: 'DM Sans', sans-serif;
        color: #0B1F3A;
        background: #0B1F3A;
        color: #fff;
        outline: none;
        transition: border-color .15s;
    }
    .field-input::placeholder { color: #64748B; }
    .field-input:focus { border-color: #2563EB; }

    .field-select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .92rem;
        font-family: 'DM Sans', sans-serif;
        color: #fff;
        background: #0B1F3A;
        outline: none;
        cursor: pointer;
    }

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
    .btn-danger-solid { background: #DC2626; color: #fff; }
    .btn-danger-solid:hover { background: #B91C1C; }

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
        width: 420px;
        max-width: 95vw;
    }
    .modal-title { font-size: 1rem; font-weight: 700; color: #0B1F3A; margin-bottom: 4px; }
    .modal-sub   { font-size: .82rem; color: #94A3B8; margin-bottom: 18px; line-height: 1.5; }

    /* ── Folio search ── */
    .folio-row { display: flex; align-items: flex-end; gap: 10px; margin-bottom: 14px; }
    .folio-row .field-wrap { flex: 1; }

    /* ── Vale encontrado ── */
    .vale-card {
        background: #F0FDF4;
        border: 1.5px solid #BBF7D0;
        border-radius: 9px;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }
    .vale-name { font-size: .92rem; font-weight: 700; color: #0B1F3A; }
    .vale-ref  { font-size: .78rem; color: #64748B; margin-top: 2px; }
    .vale-amount { font-family: 'DM Mono', monospace; font-size: 1rem; font-weight: 700; color: #DC2626; }

    /* ── Alert ── */
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
        resize: none;
        outline: none;
    }
    .field-ta:focus { border-color: #2563EB; }
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
    <div class="main-card">
        <div class="main-card-header">
            <div class="main-card-title">Capturar nuevo vale</div>
            <div class="main-card-sub">Ingresa los datos del cliente final y la distribuidora</div>
        </div>
        <div class="form-body">

            <div class="form-grid-2">
                <div>
                    <label class="field-label">Distribuidora</label>
                    {{-- TODO: llenar con distribuidoras de la BD --}}
                    <select class="field-select">
                        <option value="">Seleccionar distribuidora...</option>
                    </select>
                </div>
                <div>
                    <label class="field-label">Cliente final</label>
                    <input class="field-input" type="text" placeholder="Nombre completo del cliente" />
                </div>
            </div>

            <div class="form-grid-3">
                <div>
                    <label class="field-label">Monto del vale</label>
                    <input class="field-input" type="text" placeholder="$0.00" />
                </div>
                <div>
                    <label class="field-label">No. de referencia banco</label>
                    <input class="field-input" type="text" placeholder="REF-00000" />
                </div>
                <div>
                    <label class="field-label">Producto / plazo</label>
                    {{-- TODO: llenar con productos de la BD --}}
                    <select class="field-select">
                        <option value="">Seleccionar...</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="field-label">Observaciones (opcional)</label>
                <input class="field-input" type="text" placeholder="Notas adicionales..." />
            </div>

        </div>
        <div class="form-footer">
            <div>
                <span class="cancel-hint">¿Necesitas cancelar un vale? </span>
                <span class="cancel-link" onclick="document.getElementById('modal').classList.add('show')">
                    Cancelar vale existente
                </span>
            </div>
            <div class="footer-btns">
                <button class="btn btn-ghost">Limpiar</button>
                <button class="btn btn-primary">+ Registrar vale</button>
            </div>
        </div>
    </div>

</div>

{{-- ── MODAL CANCELAR VALE ── --}}
<div id="modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-title">Cancelar vale existente</div>
        <div class="modal-sub">Escribe el folio del vale para buscarlo y luego indica el motivo de cancelación.</div>

        <div class="folio-row">
            <div class="field-wrap">
                <label class="field-label">Folio del vale</label>
                <input class="field-input" type="text" placeholder="Ej. V-0051" id="folio-input"
                       style="background:#F8FAFC;color:#0B1F3A;" />
            </div>
            <button class="btn btn-primary">Buscar →</button>
        </div>

        {{-- Vale encontrado (visible cuando se encuentre en BD) --}}
        <div class="vale-card" id="vale-encontrado">
            <div>
                {{-- TODO: llenar con datos del vale encontrado en BD --}}
                <div class="vale-name"></div>
                <div class="vale-ref"></div>
            </div>
            <div class="vale-amount"></div>
        </div>

        <div>
            <label class="field-label">Motivo de cancelación</label>
            <select class="field-select" style="background:#fff;color:#0B1F3A;margin-bottom:12px;">
                <option value="">Seleccionar motivo...</option>
                <option>Referencia bancaria no encontrada</option>
                <option>Monto no coincide con el banco</option>
                <option>Cliente con adeudo activo</option>
                <option>Distribuidora en estado moroso</option>
                <option>Datos del cliente incorrectos</option>
                <option>Otro</option>
            </select>
        </div>

        <div>
            <label class="field-label">Comentario adicional (opcional)</label>
            <textarea class="field-ta" placeholder="Descripción adicional para la distribuidora..."></textarea>
        </div>

        <div class="alert-warn">
            La distribuidora recibirá una notificación con el motivo indicado.
        </div>

        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="document.getElementById('modal').classList.remove('show')">
                Cerrar
            </button>
            <button class="btn btn-danger">Confirmar cancelación</button>
        </div>
    </div>
</div>

@endsection