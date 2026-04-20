<style>
    .barra {
        width: 280px;
        background: #0f172a; /* Slate 900: más profundo y moderno */
        color: white;
        display: flex;
        flex-direction: column;
        height: 100vh;
        padding: 0;
        box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        position: sticky;
        top: 0;
    }

    /* Logo Section */
    .logo-container {
        padding: 32px 24px;
        text-align: center;
    }
    .logo-text {
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: -0.025em;
        background: linear-gradient(to right, #60a5fa, #2563eb);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 4px;
    }
    .role-badge {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #64748b;
        font-weight: 700;
    }

    /* Action Button */
    .nav-content {
        flex: 1;
        padding: 0 16px;
    }
    .btn-nueva {
        background: #2563eb;
        color: white;
        text-decoration: none;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-bottom: 32px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .btn-nueva:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* Navigation */
    .menu-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        padding: 0 12px 12px;
        letter-spacing: 0.05em;
    }
    .menu-nav { list-style: none; }
    .menu-nav li { margin-bottom: 4px; }
    .menu-nav a {
        color: #94a3b8;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .menu-nav a i { width: 18px; height: 18px; }
    .menu-nav a:hover, .menu-nav a.active {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }
    .menu-nav a.active {
        background: rgba(37, 99, 235, 0.1);
        color: #60a5fa;
    }

    /* User Footer */
    .user-footer {
        padding: 24px;
        background: rgba(0,0,0,0.2);
        border-top: 1px solid rgba(255,255,255,0.05);
    }
    .logout-btn {
        color: #f87171;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .logout-btn:hover {
        background: rgba(239, 68, 68, 0.1);
    }
</style>

<aside class="barra">
    <div class="logo-container">
        <div class="logo-text">Préstamo Fácil</div>
        <div class="role-badge">{{ auth()->user()->role->role }}</div>
    </div>

    <div class="nav-content">
        @if(auth()->check() && auth()->user()->role_id == 2)
            <a href="{{ route('distribuidoras.create') }}" class="btn-nueva">
                <i data-lucide="plus-circle"></i>
                <span>Nueva Distribuidora</span>
            </a>
        @endif

        <p class="menu-label">Menú Principal</p>
        <nav>
            <ul class="menu-nav">
                @if(auth()->check() && auth()->user()->role_id == 1)
                <li>
                    <a href="{{ route('gerente.productos') }}" class="{{ request()->routeIs('gerente.productos') ? 'active' : '' }}">
                        <i data-lucide="package"></i> Productos
                    </a>
                </li>
                <li>
                    <a href="{{ route('gerente.distribuidoras') }}" class="{{ request()->routeIs('gerente.distribuidoras') ? 'active' : '' }}">
                        <i data-lucide="users"></i> Distribuidoras
                    </a>
                </li>
                <li>
                    <a href="{{ route('gerente.presolicitud') }}" class="{{ request()->routeIs('gerente.presolicitud') ? 'active' : '' }}">
                        <i data-lucide="file-text"></i> Presolicitudes
                    </a>
                </li>
                <p class="menu-label">Sistema</p>
                <li>
                    <a href="{{ route('gerente.configuracion') }}" class="{{ request()->routeIs('gerente.configuracion') ? 'active' : '' }}">
                        <i data-lucide="settings"></i> Configuración
                    </a>
                </li>
                @endif
                @if(auth()->check() && auth()->user()->role_id == 2)
                <li>
                    <a href="{{ route('coordinador.notificaciones') }}" class="{{ request()->routeIs('coordinador.notificaciones') ? 'active' : '' }}">
                        <i data-lucide="bell"></i> Notificaciones
                    </a>
                </li>
                @endif
                @if(auth()->check() && auth()->user()->role_id == 5)
                <li><a href="{{ route('cajera.prevale') }}"><i data-lucide="git-merge"></i> Prevales</a></li>
                @endif
            </ul>
        </nav>
    </div>

    <div class="user-footer">
        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">
            @csrf
        </form>
        <a href="#" onclick="document.getElementById('logout-form').submit();" class="logout-btn">
            <i data-lucide="log-out"></i>
            Cerrar Sesión
        </a>
    </div>
</aside>

<script>
    // No olvides que necesitas Lucide inicializado en tu layout principal
    lucide.createIcons();
</script>