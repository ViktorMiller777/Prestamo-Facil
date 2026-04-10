@extends('layouts.app')

@section('title', 'Perfil de usuario')

@section('content')
<style>
    .page-wrapper { background: #F0F2F7; min-height: 100vh; font-family: 'DM Sans', sans-serif; }

    /* ── Topbar ── */
    .topbar { padding: 24px 32px 16px 32px; }
    .topbar-title { font-size: 1.5rem; font-weight: 700; color: #0B1F3A; margin: 0; }
    .topbar-sub   { font-size: .82rem; color: #64748B; margin-top: 3px; }

    /* ── Cards ── */
    .section-card {
        margin: 0 32px 16px 32px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        overflow: hidden;
    }
    .section-card:last-child { margin-bottom: 32px; }

    /* ── Avatar section ── */
    .avatar-section {
        padding: 22px 24px;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .avatar-big {
        width: 64px; height: 64px;
        border-radius: 50%;
        background: #1D4ED8;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; font-weight: 700; color: #fff;
        flex-shrink: 0;
    }
    .avatar-name { font-size: 1rem; font-weight: 700; color: #0B1F3A; margin-bottom: 5px; }
    .avatar-meta { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
    .badge-rol { background: #DBEAFE; color: #1D4ED8; padding: 3px 10px; border-radius: 99px; font-size: .78rem; font-weight: 700; }
    .avatar-desde { font-size: .78rem; color: #94A3B8; }
    .btn-foto { font-size: .82rem; color: #2563EB; font-weight: 600; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; padding: 0; }
    .btn-foto:hover { text-decoration: underline; }

    /* ── Card header ── */
    .card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #F1F5F9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-title { font-size: .95rem; font-weight: 700; color: #0B1F3A; margin-bottom: 3px; }
    .card-sub   { font-size: .80rem; color: #94A3B8; }
    .btn-edit { font-size: .82rem; color: #2563EB; font-weight: 600; background: none; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }

    /* ── Form grid ── */
    .form-body { padding: 20px 24px; display: flex; flex-direction: column; gap: 14px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .field-label { font-size: .75rem; color: #64748B; margin-bottom: 5px; display: block; font-weight: 500; }
    .field-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #E2E8F0;
        border-radius: 8px;
        font-size: .90rem;
        font-family: 'DM Sans', sans-serif;
        color: #fff;
        background: #0B1F3A;
        outline: none;
    }
    .field-input::placeholder { color: #64748B; }
    .field-input:disabled { opacity: .7; cursor: not-allowed; }
    .field-input:focus { border-color: #2563EB; }

    /* ── Form footer ── */
    .form-footer {
        padding: 14px 24px;
        border-top: 1px solid #F1F5F9;
        display: flex; justify-content: flex-end; gap: 10px;
    }
    .btn { padding: 9px 20px; border-radius: 8px; font-size: .85rem; font-weight: 700; border: none; cursor: pointer; font-family: 'DM Sans', sans-serif; }
    .btn-ghost   { background: #fff; color: #64748B; border: 1.5px solid #E2E8F0; }
    .btn-ghost:hover { background: #F8FAFC; }
    .btn-primary { background: #2563EB; color: #fff; }
    .btn-primary:hover { background: #1D4ED8; }

    /* ── Toggles ── */
    .config-body { padding: 6px 24px 16px 24px; }
    .toggle-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 0; border-bottom: 1px solid #F8FAFC;
    }
    .toggle-row:last-child { border-bottom: none; }
    .toggle-label { font-size: .90rem; color: #0B1F3A; font-weight: 600; }
    .toggle-sub   { font-size: .78rem; color: #94A3B8; margin-top: 2px; }
    .toggle {
        width: 38px; height: 21px;
        border-radius: 99px;
        cursor: pointer;
        position: relative;
        flex-shrink: 0;
        transition: background .2s;
    }
    .toggle.on  { background: #2563EB; }
    .toggle.off { background: #CBD5E1; }
    .toggle-thumb {
        width: 17px; height: 17px;
        border-radius: 50%; background: #fff;
        position: absolute; top: 2px;
        transition: left .2s;
    }
    .toggle.on  .toggle-thumb { left: 19px; }
    .toggle.off .toggle-thumb { left: 2px; }

    /* ── Danger zone ── */
    .danger-card {
        margin: 0 32px 32px 32px;
        border: 1.5px solid #FECACA;
        border-radius: 14px;
        overflow: hidden;
    }
    .danger-header {
        padding: 14px 24px;
        background: #FEF2F2;
        border-bottom: 1px solid #FECACA;
    }
    .danger-title { font-size: .92rem; font-weight: 700; color: #B91C1C; margin-bottom: 2px; }
    .danger-sub   { font-size: .80rem; color: #DC2626; opacity: .85; }
    .danger-body {
        padding: 16px 24px;
        background: #fff;
        display: flex; align-items: center; justify-content: space-between; gap: 20px;
    }
    .danger-text { font-size: .84rem; color: #64748B; line-height: 1.6; }
    .btn-cerrar {
        padding: 9px 20px;
        background: #FEE2E2; color: #B91C1C;
        border: 1.5px solid #FECACA;
        border-radius: 8px;
        font-size: .85rem; font-weight: 700;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        white-space: nowrap;
    }
    .btn-cerrar:hover { background: #FECACA; }

    @media (max-width: 1024px) {

    .topbar { padding: 20px; }
    .topbar-title { font-size: 1.3rem; }

    .section-card { margin: 0 16px 16px 16px; }
    .danger-card  { margin: 0 16px 32px 16px; }

    .avatar-section { flex-direction: column; align-items: flex-start; gap: 14px; }
    .avatar-big { width: 54px; height: 54px; font-size: 1.1rem; }

    .form-grid-2 { grid-template-columns: 1fr; }

    .card-header { flex-direction: column; align-items: flex-start; gap: 8px; }

    .danger-body { flex-direction: column; align-items: flex-start; gap: 12px; }

    .form-footer { flex-direction: column; }
    .btn { width: 100%; text-align: center; }
}
</style>

<div class="page-wrapper">

    {{-- ── TOPBAR ── --}}
    <div class="topbar">
        <h1 class="topbar-title">Perfil de usuario</h1>
        <div class="topbar-sub">Datos personales y configuración</div>
    </div>

    {{-- ── DATOS PERSONALES ── --}}
    <div class="section-card">
        <div class="avatar-section">
            {{-- TODO: mostrar iniciales desde BD --}}
            <div class="avatar-big" id="avatar-iniciales"></div>
            <div>
                {{-- TODO: mostrar nombre desde BD --}}
                <div class="avatar-name" id="avatar-nombre"></div>
                <div class="avatar-meta">
                    {{-- TODO: mostrar rol desde BD --}}
                    <span class="badge-rol" id="avatar-rol"></span>
                    <span class="avatar-desde" id="avatar-desde"></span>
                </div>
                <button class="btn-foto">Cambiar foto de perfil</button>
            </div>
        </div>

        <div class="card-header">
            <div>
                <div class="card-title">Datos personales</div>
                <div class="card-sub">Tu información registrada en el sistema</div>
            </div>
            <button class="btn-edit">Editar</button>
        </div>

        <div class="form-body">
            <div class="form-grid-2">
                <div>
                    <label class="field-label">Nombre completo</label>
                    {{-- TODO: traer de BD --}}
                    <input class="field-input" type="text" placeholder="" disabled />
                </div>
                <div>
                    <label class="field-label">Teléfono</label>
                    {{-- TODO: traer de BD --}}
                    <input class="field-input" type="text" placeholder="" disabled />
                </div>
            </div>
            <div class="form-grid-2">
                <div>
                    <label class="field-label">Correo electrónico</label>
                    {{-- TODO: traer de BD --}}
                    <input class="field-input" type="text" placeholder="" disabled />
                </div>
                <div>
                    <label class="field-label">Referencia de pago</label>
                    {{-- TODO: traer de BD --}}
                    <input class="field-input" type="text" placeholder="" disabled style="font-family:'DM Mono',monospace;" />
                </div>
            </div>
            <div>
                <label class="field-label">Domicilio</label>
                {{-- TODO: traer de BD --}}
                <input class="field-input" type="text" placeholder="" disabled />
            </div>
        </div>
    </div>

    {{-- ── CAMBIAR CONTRASEÑA ── --}}
    <div class="section-card">
        <div class="card-header">
            <div>
                <div class="card-title">Cambiar contraseña</div>
                <div class="card-sub">Usa al menos 8 caracteres con letras y números</div>
            </div>
        </div>
        <div class="form-body">
            <div>
                <label class="field-label">Contraseña actual</label>
                <input class="field-input" type="password" placeholder="••••••••" />
            </div>
            <div class="form-grid-2">
                <div>
                    <label class="field-label">Nueva contraseña</label>
                    <input class="field-input" type="password" placeholder="••••••••" />
                </div>
                <div>
                    <label class="field-label">Confirmar nueva contraseña</label>
                    <input class="field-input" type="password" placeholder="••••••••" />
                </div>
            </div>
        </div>
        <div class="form-footer">
            <button class="btn btn-ghost">Cancelar</button>
            <button class="btn btn-primary">Guardar contraseña</button>
        </div>
    </div>

    {{-- ── CONFIGURACIÓN BÁSICA ── --}}
    <div class="section-card">
        <div class="card-header">
            <div>
                <div class="card-title">Configuración básica</div>
                <div class="card-sub">Preferencias de notificaciones y sistema</div>
            </div>
        </div>
        <div class="config-body">
            {{-- TODO: mostrar toggles según el rol del usuario autenticado --}}
            <div class="toggle-row">
                <div>
                    <div class="toggle-label">Notificaciones de corte</div>
                    <div class="toggle-sub">Recibe alertas cuando tu corte esté próximo a vencer</div>
                </div>
                <div class="toggle on" onclick="this.classList.toggle('on');this.classList.toggle('off')">
                    <div class="toggle-thumb"></div>
                </div>
            </div>
            <div class="toggle-row">
                <div>
                    <div class="toggle-label">Alertas de clientes morosos</div>
                    <div class="toggle-sub">Notificación cuando el coordinador apruebe un estado moroso</div>
                </div>
                <div class="toggle on" onclick="this.classList.toggle('on');this.classList.toggle('off')">
                    <div class="toggle-thumb"></div>
                </div>
            </div>
            <div class="toggle-row">
                <div>
                    <div class="toggle-label">Notificaciones de puntos</div>
                    <div class="toggle-sub">Aviso cuando acumules puntos por pago anticipado</div>
                </div>
                <div class="toggle on" onclick="this.classList.toggle('on');this.classList.toggle('off')">
                    <div class="toggle-thumb"></div>
                </div>
            </div>
            <div class="toggle-row">
                <div>
                    <div class="toggle-label">Tokens usados</div>
                    <div class="toggle-sub">Confirmar cuando un cliente use tu token de transferencia</div>
                </div>
                <div class="toggle off" onclick="this.classList.toggle('on');this.classList.toggle('off')">
                    <div class="toggle-thumb"></div>
                </div>
            </div>
        </div>
        <div class="form-footer">
            <button class="btn btn-primary">Guardar configuración</button>
        </div>
    </div>

    {{-- ── CERRAR SESIÓN ── --}}
    <div class="danger-card">
        <div class="danger-header">
            <div class="danger-title">Cerrar sesión</div>
            <div class="danger-sub">Sal del sistema de forma segura</div>
        </div>
        <div class="danger-body">
            <div class="danger-text">
                Al cerrar sesión tendrás que volver a iniciar con tus credenciales.<br>
                Asegúrate de haber guardado cualquier cambio pendiente.
            </div>
            {{-- TODO: conectar con ruta de logout de Breeze --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-cerrar">Cerrar sesión</button>
            </form>
        </div>
    </div>

</div>

@endsection