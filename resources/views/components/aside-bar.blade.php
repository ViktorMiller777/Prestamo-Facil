<style>
     .barra {
        width: 280px;
        background: #111827;
        color: white;
        display: flex;
        flex-direction: column;
        padding: 20px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }

    .logo {
        font-weight: 800;
        font-size: 1.4rem;
        color: white;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #374151;
        text-align: center;
    }
    .btn-nueva {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 12px 15px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.3s ease;
        margin-bottom: 25px;
        text-decoration: none;
        font-size: 0.9rem;
    }
    .role {
        font-size: 15px;
        color: #5d6169;
    }
    .menu-nav { list-style: none; }
    .menu-nav li { margin-bottom: 10px; }
    .menu-nav a {
        color: #9ca3af;
        text-decoration: none;
        display: block;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .menu-nav a:hover, .menu-nav a.active {
        background: #1f2937;
        color: white;
    }
</style>
<aside class="barra">
    <div class="logo">
        Prestamo Fácil
        <p class="role">{{ auth()->user()->role->role }}</p>
    </div>

    @if(auth()->check() && auth()->user()->role_id == 2)
        <a href="{{ route('distribuidoras.create') }}" class="btn-nueva">
            <span>+</span> Nueva Distribuidora
        </a>
    @endif

    <nav>
        <ul class="menu-nav">
            @if(auth()->check() && auth()->user()->role_id == 1)
            <li><a href="{{ route('gerente.productos') }}">Productos</a></li>
            @endif
            @if(auth()->check() && auth()->user()->role_id == 1)
            <li><a href="{{ route('gerente.distribuidoras') }}">Distribuidoras</a></li>
            @endif
            <li><a href="#">Relaciones</a></li>
            <li><a href="#">Vales</a></li>
            <li><a href="#">Configuracion</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">
                    @csrf
                </form>
                <a href="#" onclick="document.getElementById('logout-form').submit();"
                    style="color: #ef4444; font-weight: bold;">
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </nav>
</aside>