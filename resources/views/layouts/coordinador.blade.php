<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Préstamo Fácil') }} — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Reset base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: #F0F2F7; }

        /* ── Shell ── */
        .pf-shell { display: flex; height: 100vh; overflow: hidden; }

        /* ══════════════════════════════════════
           SIDEBAR
        ══════════════════════════════════════ */
        .pf-sidebar {
            width: 220px; min-width: 220px;
            background: #0B1F3A;
            display: flex; flex-direction: column;
            height: 100vh; position: sticky; top: 0;
        }

        /* Logo */
        .pf-logo { padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,0.08); flex-shrink: 0; }
        .pf-logo-mark { display: flex; align-items: center; gap: 10px; }
        .pf-logo-icon { width: 32px; height: 32px; background: #2563EB; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .pf-logo-text { font-size: 14px; font-weight: 500; color: #fff; line-height: 1.2; }
        .pf-logo-sub  { font-size: 10px; color: rgba(255,255,255,0.4); font-weight: 300; letter-spacing: .05em; text-transform: uppercase; }

        /* Nav */
        .pf-nav { flex: 1; padding: 12px 10px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
        .pf-nav-section { font-size: 10px; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: .08em; padding: 12px 10px 6px; font-weight: 500; }
        .pf-nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 7px; cursor: pointer;
            font-size: 13px; color: rgba(255,255,255,0.55);
            transition: all .15s; font-weight: 400; text-decoration: none;
        }
        .pf-nav-item:hover  { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.85); }
        .pf-nav-item.active { background: #2563EB; color: #fff; font-weight: 500; }
        .pf-nav-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; opacity: .6; flex-shrink: 0; }
        .pf-nav-badge { margin-left: auto; background: #DC2626; color: #fff; font-size: 10px; font-weight: 500; padding: 1px 6px; border-radius: 10px; min-width: 18px; text-align: center; }

        /* User footer */
        .pf-user { padding: 12px 14px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
        .pf-avatar { width: 30px; height: 30px; border-radius: 50%; background: #0F6E56; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 500; color: #fff; flex-shrink: 0; }
        .pf-user-name { font-size: 12px; color: rgba(255,255,255,0.8); font-weight: 500; }
        .pf-user-role { font-size: 10px; color: rgba(255,255,255,0.35); }

        /* ══════════════════════════════════════
           MAIN AREA
        ══════════════════════════════════════ */
        .pf-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }

        /* Topbar */
        .pf-topbar { background: #fff; border-bottom: 1px solid #E2E8F0; padding: 0 24px; height: 52px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
        .pf-topbar-title { font-size: 15px; font-weight: 500; color: #0B1F3A; }
        .pf-topbar-sub   { font-size: 12px; color: #94A3B8; margin-top: 1px; }
        .pf-topbar-right { display: flex; align-items: center; gap: 12px; }

        /* Botones globales */
        .pf-btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; border: none; font-family: 'DM Sans', sans-serif; text-decoration: none; transition: background .12s; }
        .pf-btn-primary { background: #2563EB; color: #fff; } .pf-btn-primary:hover { background: #1D4ED8; }
        .pf-btn-ghost { background: transparent; color: #64748B; border: 1px solid #E2E8F0; } .pf-btn-ghost:hover { background: #F8FAFC; }

        /* Área de contenido */
        .pf-content { flex: 1; padding: 20px 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 16px; background: #F0F2F7; }

        /* ── Componentes compartidos ── */

        /* KPI cards */
        .pf-metrics { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 12px; }
        .pf-metrics-3 { grid-template-columns: repeat(3, minmax(0,1fr)); }
        .pf-metric { background: #fff; border: 1px solid #E2E8F0; border-radius: 10px; padding: 14px 16px; }
        .pf-metric-accent { border-left: 3px solid #2563EB; border-radius: 0 10px 10px 0; }
        .pf-metric-label { font-size: 10px; color: #94A3B8; text-transform: uppercase; letter-spacing: .05em; font-weight: 500; margin-bottom: 6px; }
        .pf-metric-value { font-size: 22px; font-weight: 500; color: #0B1F3A; margin-bottom: 4px; font-family: 'DM Mono', monospace; }
        .pf-metric-delta { font-size: 11px; color: #16A34A; }
        .pf-metric-delta.neg   { color: #DC2626; }
        .pf-metric-delta.amber { color: #D97706; }
        .pf-metric-delta.blue  { color: #2563EB; }

        /* Cards */
        .pf-card { background: #fff; border: 1px solid #E2E8F0; border-radius: 10px; overflow: hidden; }
        .pf-card-header { padding: 14px 18px; border-bottom: 1px solid #F1F5F9; display: flex; align-items: center; justify-content: space-between; }
        .pf-card-title  { font-size: 13px; font-weight: 500; color: #0B1F3A; }
        .pf-card-action { font-size: 11px; color: #2563EB; cursor: pointer; }
        .pf-card-badge  { font-size: 10px; font-weight: 500; background: #DBEAFE; color: #1D4ED8; padding: 2px 8px; border-radius: 99px; }

        /* Tabla */
        .pf-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .pf-table th { padding: 8px 18px; text-align: left; color: #94A3B8; font-weight: 500; font-size: 10px; text-transform: uppercase; letter-spacing: .05em; border-bottom: 1px solid #F1F5F9; }
        .pf-table td { padding: 10px 18px; color: #334155; border-bottom: 1px solid #F8FAFC; vertical-align: middle; }
        .pf-table tr:last-child td { border-bottom: none; }
        .pf-table tr:hover td { background: #F8FAFC; }

        /* Badges */
        .pf-badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 500; }
        .pf-badge-green  { background: #DCFCE7; color: #15803D; }
        .pf-badge-red    { background: #FEE2E2; color: #B91C1C; }
        .pf-badge-amber  { background: #FEF3C7; color: #B45309; }
        .pf-badge-blue   { background: #DBEAFE; color: #1D4ED8; }

        /* Categorías */
        .pf-cat { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 500; }
        .pf-cat-oro   { background: #FEF3C7; color: #92400E; }
        .pf-cat-plata { background: #F1F5F9; color: #475569; }
        .pf-cat-cobre { background: #FFEDD5; color: #9A3412; }

        /* Tabs */
        .pf-tabs { display: flex; padding: 0 18px; border-bottom: 1px solid #E2E8F0; }
        .pf-tab  { padding: 10px 14px; font-size: 12px; color: #94A3B8; cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -1px; font-weight: 400; }
        .pf-tab.active { color: #2563EB; border-bottom-color: #2563EB; font-weight: 500; }

        /* Notificaciones */
        .pf-notif-list { display: flex; flex-direction: column; }
        .pf-notif { display: flex; align-items: flex-start; gap: 10px; padding: 11px 18px; border-bottom: 1px solid #F8FAFC; }
        .pf-notif:last-child { border-bottom: none; }
        .pf-notif-dot { width: 7px; height: 7px; border-radius: 50%; margin-top: 4px; flex-shrink: 0; }
        .pf-notif-dot.blue  { background: #2563EB; }
        .pf-notif-dot.green { background: #16A34A; }
        .pf-notif-dot.red   { background: #DC2626; }
        .pf-notif-dot.amber { background: #D97706; }
        .pf-notif-msg  { font-size: 12px; color: #334155; line-height: 1.5; }
        .pf-notif-time { font-size: 10px; color: #94A3B8; margin-top: 2px; }
    </style>

    @stack('styles')
</head>
<body>
<div class="pf-shell">

    {{-- ══════════════════════════════════════
         SIDEBAR
    ══════════════════════════════════════ --}}
    <aside class="pf-sidebar">

        <div class="pf-logo">
            <div class="pf-logo-mark">
                <div class="pf-logo-icon">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <rect x="2"  y="8" width="4" height="6" rx="1" fill="white" opacity=".9"/>
                        <rect x="6"  y="5" width="4" height="9" rx="1" fill="white"/>
                        <rect x="10" y="2" width="4" height="12" rx="1" fill="white" opacity=".7"/>
                    </svg>
                </div>
                <div>
                    <div class="pf-logo-text">Préstamo Fácil</div>
                    <div class="pf-logo-sub">Sistema de gestión</div>
                </div>
            </div>
        </div>

        <nav class="pf-nav">

            <div class="pf-nav-section">Principal</div>

            {{-- TODO: agregar route('coordinador.dashboard') cuando esté lista --}}
            <a href="#"
               class="pf-nav-item {{ request()->routeIs('coordinador.dashboard') ? 'active' : '' }}">
                <div class="pf-nav-dot"></div>
                Dashboard
            </a>

            <div class="pf-nav-section">Clientes</div>

            <a href="{{ route('coordinador.cambio_cliente') }}"
               class="pf-nav-item {{ request()->routeIs('coordinador.cambio_cliente') ? 'active' : '' }}">
                <div class="pf-nav-dot"></div>
                Cambios de distribuidora
            </a>

            <a href="#"
               class="pf-nav-item {{ request()->routeIs('coordinador.clientes-morosos*') ? 'active' : '' }}">
                <div class="pf-nav-dot"></div>
                Clientes morosos
                {{-- TODO: mostrar conteo real de pendientes --}}
                @if(($pendientesMorosos ?? 0) > 0)
                    <span class="pf-nav-badge">{{ $pendientesMorosos }}</span>
                @endif
            </a>

            <div class="pf-nav-section">Solicitudes</div>

            {{-- TODO: agregar route('coordinador.presolicitudes') cuando esté lista --}}
            <a href="{{ route('coordinador.presolicitudes') }}"
               class="pf-nav-item {{ request()->routeIs('coordinador.presolicitudes*') ? 'active' : '' }}">
                <div class="pf-nav-dot"></div>
                Presolicitudes
                {{-- TODO: mostrar conteo real de pendientes --}}
                @if(($pendientesPresolicitudes ?? 0) > 0)
                    <span class="pf-nav-badge">{{ $pendientesPresolicitudes }}</span>
                @endif
            </a>

        </nav>

        <div class="pf-user">
            {{-- TODO: usar Auth::user()->persona --}}
            <div class="pf-avatar">
                {{ strtoupper(substr(Auth::user()->nombre ?? 'U', 0, 1)) }}{{ strtoupper(substr(Auth::user()->apellido ?? '', 0, 1)) }}
            </div>
            <div>
                <div class="pf-user-name">{{ Auth::user()->nombre ?? 'Usuario' }} {{ Auth::user()->apellido ?? '' }}</div>
                <div class="pf-user-role">Coordinador</div>
            </div>
        </div>

    </aside>

    {{-- ══════════════════════════════════════
         MAIN
    ══════════════════════════════════════ --}}
    <div class="pf-main">

        <header class="pf-topbar">  
            <div>
                <div class="pf-topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="pf-topbar-sub">@yield('page-sub', '')</div>
            </div>
            <div class="pf-topbar-right">
                @yield('topbar-actions')
            </div>
        </header>

        <main class="pf-content">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>