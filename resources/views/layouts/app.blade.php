
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Préstamo Fácil — @yield('titulo', 'Sistema de gestión')</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>

  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'DM Sans', sans-serif;
      background: #F0F2F7;
      min-height: 100vh;
      display: flex;
    }

    /* ─── SIDEBAR ─── */
    .pf-sidebar {
      width: 220px;
      min-width: 220px;
      background: #0B1F3A;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      position: fixed;
      top: 0; left: 0;
    }

    .pf-logo {
      padding: 20px 20px 16px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .pf-logo-mark {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .pf-logo-icon {
      width: 32px; height: 32px;
      background: #2563EB;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }

    .pf-logo-text {
      font-size: 14px; font-weight: 500; color: #fff; line-height: 1.2;
    }

    .pf-logo-sub {
      font-size: 10px; color: rgba(255,255,255,0.4);
      font-weight: 300; letter-spacing: 0.05em; text-transform: uppercase;
    }

    .pf-nav {
      flex: 1;
      padding: 12px 10px;
      display: flex; flex-direction: column; gap: 2px;
    }

    .pf-nav-section {
      font-size: 10px; color: rgba(255,255,255,0.3);
      text-transform: uppercase; letter-spacing: 0.08em;
      padding: 12px 10px 6px; font-weight: 500;
    }

    .pf-nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 8px 10px; border-radius: 7px;
      font-size: 13px; color: rgba(255,255,255,0.55);
      text-decoration: none; transition: all 0.15s;
    }

    .pf-nav-item:hover {
      background: rgba(255,255,255,0.07);
      color: rgba(255,255,255,0.85);
    }

    .pf-nav-item.active {
      background: #2563EB; color: #fff; font-weight: 500;
    }

    .pf-nav-dot {
      width: 6px; height: 6px; border-radius: 50%;
      background: currentColor; opacity: 0.6; flex-shrink: 0;
    }

    .pf-nav-badge {
      margin-left: auto;
      background: #DC2626; color: #fff;
      font-size: 10px; font-weight: 500;
      padding: 1px 6px; border-radius: 10px;
      min-width: 18px; text-align: center;
    }

    .pf-user {
      padding: 12px 14px;
      border-top: 1px solid rgba(255,255,255,0.08);
      display: flex; align-items: center; gap: 10px;
    }

    .pf-avatar {
      width: 30px; height: 30px; border-radius: 50%;
      background: #1D4ED8;
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 500; color: #fff; flex-shrink: 0;
    }

    .pf-user-name { font-size: 12px; color: rgba(255,255,255,0.8); font-weight: 500; }
    .pf-user-role { font-size: 10px; color: rgba(255,255,255,0.35); }

    /* ─── MAIN ─── */
    .pf-wrapper {
      margin-left: 220px;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .pf-topbar {
      background: #fff;
      border-bottom: 1px solid #E2E8F0;
      padding: 0 24px;
      height: 52px;
      display: flex; align-items: center; justify-content: space-between;
      position: sticky; top: 0; z-index: 100;
    }

    .pf-topbar-title { font-size: 15px; font-weight: 500; color: #0B1F3A; }
    .pf-topbar-sub   { font-size: 12px; color: #94A3B8; margin-top: 1px; }

    .pf-content {
      padding: 20px 24px;
      flex: 1;
      display: flex; flex-direction: column; gap: 16px;
    }

    /* ─── CARDS ─── */
    .pf-card {
      background: #fff;
      border: 1px solid #E2E8F0;
      border-radius: 10px;
      overflow: hidden;
    }

    .pf-card-header {
      padding: 14px 18px;
      border-bottom: 1px solid #F1F5F9;
      display: flex; align-items: center; justify-content: space-between;
    }

    .pf-card-title { font-size: 13px; font-weight: 500; color: #0B1F3A; }
    .pf-card-sub   { font-size: 11px; color: #94A3B8; margin-top: 2px; }

    /* ─── MÉTRICAS ─── */
    .pf-metrics {
      display: grid;
      grid-template-columns: repeat(4, minmax(0,1fr));
      gap: 12px;
    }

    .pf-metric {
      background: #fff; border: 1px solid #E2E8F0;
      border-radius: 10px; padding: 16px 18px;
    }

    .pf-metric-accent {
      border-left: 3px solid #2563EB;
      border-radius: 0 10px 10px 0;
    }

    .pf-metric-label {
      font-size: 11px; color: #94A3B8;
      text-transform: uppercase; letter-spacing: 0.05em; font-weight: 500;
    }

    .pf-metric-value {
      font-size: 22px; font-weight: 500; color: #0B1F3A;
      margin: 6px 0 4px; font-family: 'DM Mono', monospace;
    }

    .pf-metric-delta      { font-size: 11px; color: #16A34A; }
    .pf-metric-delta.warn { color: #D97706; }
    .pf-metric-delta.err  { color: #DC2626; }

    /* ─── TABLA ─── */
    .pf-table { width: 100%; border-collapse: collapse; font-size: 12px; }
    .pf-table th {
      padding: 8px 18px; text-align: left;
      color: #94A3B8; font-weight: 500; font-size: 10px;
      text-transform: uppercase; letter-spacing: 0.05em;
      border-bottom: 1px solid #F1F5F9;
    }
    .pf-table td {
      padding: 10px 18px; color: #334155;
      border-bottom: 1px solid #F8FAFC; vertical-align: middle;
    }
    .pf-table tr:last-child td { border-bottom: none; }
    .pf-table tr:hover td { background: #F8FAFC; }

    /* ─── BADGES ─── */
    .pf-badge {
      display: inline-flex; align-items: center;
      padding: 2px 8px; border-radius: 20px;
      font-size: 10px; font-weight: 500;
    }
    .pf-badge-green  { background: #DCFCE7; color: #15803D; }
    .pf-badge-red    { background: #FEE2E2; color: #B91C1C; }
    .pf-badge-amber  { background: #FEF3C7; color: #B45309; }
    .pf-badge-blue   { background: #DBEAFE; color: #1D4ED8; }
    .pf-badge-gray   { background: #F1F5F9; color: #475569; }

    /* ─── BOTONES ─── */
    .pf-btn {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 7px 14px; border-radius: 7px;
      font-size: 12px; font-weight: 500; cursor: pointer;
      border: none; font-family: 'DM Sans', sans-serif;
      text-decoration: none;
    }
    .pf-btn-primary { background: #2563EB; color: #fff; }
    .pf-btn-primary:hover { background: #1D4ED8; color: #fff; }
    .pf-btn-ghost   { background: transparent; color: #64748B; border: 1px solid #E2E8F0; }
    .pf-btn-ghost:hover { background: #F8FAFC; }
    .pf-btn-danger  { background: #FEE2E2; color: #B91C1C; border: 1px solid #FECACA; }
    .pf-btn-danger:hover { background: #FECACA; }
    .pf-btn-success { background: #DCFCE7; color: #15803D; border: 1px solid #BBF7D0; }

    /* ─── TABS ─── */
    .pf-tabs { display: flex; border-bottom: 1px solid #E2E8F0; padding: 0 18px; }
    .pf-tab {
      padding: 10px 14px; font-size: 12px; color: #94A3B8;
      cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px;
    }
    .pf-tab.active { color: #2563EB; border-bottom-color: #2563EB; font-weight: 500; }

    /* ─── FORMULARIOS ─── */
    .pf-input {
      width: 100%; padding: 7px 10px;
      border: 1px solid #E2E8F0; border-radius: 7px;
      font-size: 13px; font-family: 'DM Sans', sans-serif;
      color: #0B1F3A; outline: none;
    }
    .pf-input:focus { border-color: #2563EB; }
    .pf-select {
      width: 100%; padding: 7px 10px;
      border: 1px solid #E2E8F0; border-radius: 7px;
      font-size: 13px; font-family: 'DM Sans', sans-serif;
      color: #0B1F3A; outline: none; cursor: pointer; background: #fff;
    }
    .field-label {
      font-size: 11px; color: #64748B;
      margin-bottom: 4px; display: block;
    }
    .mono { font-family: 'DM Mono', monospace; }
  </style>

  @stack('styles')
</head>
<body>

  {{-- ─── SIDEBAR ─── --}}
  <aside class="pf-sidebar">
    <div class="pf-logo">
      <div class="pf-logo-mark">
        <div class="pf-logo-icon">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
            <rect x="2" y="8" width="4" height="6" rx="1" fill="white" opacity="0.9"/>
            <rect x="6" y="5" width="4" height="9" rx="1" fill="white"/>
            <rect x="10" y="2" width="4" height="12" rx="1" fill="white" opacity="0.7"/>
          </svg>
        </div>
        <div>
          <div class="pf-logo-text">Préstamo Fácil</div>
          <div class="pf-logo-sub">Sistema de gestión</div>
        </div>
      </div>
    </div>

<nav class="pf-nav">

  @php $rolPrueba = 'verificador'; @endphp

  @if($rolPrueba === 'verificador')
    <div class="pf-nav-section">Verificador</div>
    <a href="{{ route('verificador.bandeja') }}" class="pf-nav-item {{ request()->routeIs('verificador.bandeja') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Bandeja de presolicitudes
    </a>
    <a href="{{ route('verificador.expediente') }}" class="pf-nav-item {{ request()->routeIs('verificador.expediente') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Expediente digital
    </a>
    <a href="{{ route('verificador.domicilio') }}" class="pf-nav-item {{ request()->routeIs('verificador.domicilio') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Validación de domicilio
    </a>
  @endif

  @if($rolPrueba === 'cajera')
    <div class="pf-nav-section">Cajera</div>

    <a href="{{ route('cajera.conciliacion') }}" class="pf-nav-item {{ request()->routeIs('cajera.conciliacion') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Módulo de Conciliación Bancaria
    </a>
    <a href="{{ route('cajera.prevales') }}" class="pf-nav-item {{ request()->routeIs('cajera.prevales') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Gestión de Prevales
    </a>
    <a href="{{ route('cajera.monitor') }}" class="pf-nav-item {{ request()->routeIs('cajera.monitor') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Monitor de Distribuidoras Deshabilitadas
    </a>
    
    
  @endif

  @if($rolPrueba === 'distribuidora')
    <div class="pf-nav-section">Distribuidora</div>
    <a href="{{ route('distribuidora.cuenta') }}" class="pf-nav-item {{ request()->routeIs('distribuidora.cuenta') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Estado de Cuenta (Relación)
    </a>
    <a href="{{ route('distribuidora.puntos') }}" class="pf-nav-item {{ request()->routeIs('distribuidora.puntos') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Panel de Puntos
    </a>
    <a href="{{ route('distribuidora.clientes') }}" class="pf-nav-item {{ request()->routeIs('distribuidora.clientes') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Gestión de clientes
    </a> 
    <a href="{{ route('distribuidora.token') }}" class="pf-nav-item {{ request()->routeIs('distribuidora.token') ? 'active' : '' }}">
      <div class="pf-nav-dot"></div>Generador de Tokens
    </a>
  @endif

  {{-- ── NOTIFICACIONES (todos los roles) ── --}}
  <div class="pf-nav-section">General</div>
  <a href="{{ route('notificaciones.index') }}" class="pf-nav-item {{ request()->routeIs('notificaciones.index') ? 'active' : '' }}">
    <div class="pf-nav-dot"></div>Notificaciones
  </a>

</nav>


    @auth
    <div class="pf-user">
      <div class="pf-avatar">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
      </div>
      <div>
        <div class="pf-user-name">{{ auth()->user()->name }}</div>
        <div class="pf-user-role">{{ ucfirst(auth()->user()->rol) }}</div>
      </div>
    </div>
    @endauth
  </aside>

  {{-- ─── CONTENIDO PRINCIPAL ─── --}}
  <div class="pf-wrapper">

    {{-- TOPBAR --}}
    <div class="pf-topbar">
      <div>
        <div class="pf-topbar-title">@yield('page-title')</div>
        <div class="pf-topbar-sub">@yield('page-sub')</div>
      </div>
      <div class="d-flex align-items-center gap-2">
        @yield('topbar-actions')
      </div>
    </div>

    {{-- PÁGINA --}}
    <div class="pf-content">
      @yield('content')
    </div>

  </div>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
