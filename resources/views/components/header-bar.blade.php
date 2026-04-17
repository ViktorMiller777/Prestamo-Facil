<style>
    .box {
        display: flex;
        flex-direction: row;
        align-items: center; 
        justify-content: space-between;
        padding: 0 40px;
        border-radius: 16px; /* Bordes un poco más suaves */
        position: fixed;
        top: 10px;
        left: 10px;
        right: 10px;
        width: calc(100% - 20px);
        z-index: 1000;
        height: 5.5rem; 
        
        /* Efecto Glassmorphism Pro */
        background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    }

    .pf-logo {
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 800;
        font-size: 1.5rem;
        letter-spacing: -0.5px;
    }

    .pf-logo-box {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 8px 14px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        font-size: 1.2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .content-bar {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 600;
        font-size: 1.1rem; /* Reducido para mejor contraste con el logo */
        margin: 0;
        background: rgba(255, 255, 255, 0.05);
        padding: 10px 20px;
        border-radius: 99px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-name {
        text-transform: capitalize; 
        color: #60a5fa; /* Color azul brillante para el nombre */
        font-weight: 800;
    }

    .pf-logo-link {
        text-decoration: none;
        transition: transform 0.2s ease;
    }

    .pf-logo-link:active {
        transform: scale(0.95);
    }
</style>

<div class="box">
    @php
        $dashboardRoute = 'login';
        if(auth()->check()){
            if(auth()->user()->role_id == 4) $dashboardRoute = 'distribuidora.dashboard';
            if(auth()->user()->role_id == 3) $dashboardRoute = 'verificador.dashboard';
        }
    @endphp

    <a href="{{ route($dashboardRoute) }}" class="pf-logo-link">
        <div class="pf-logo">
            <div class="pf-logo-box">PF</div>
            <span>Préstamo Fácil</span>
        </div>
    </a>

    <h1 class="content-bar">
        <span style="opacity: 0.8;">Bienvenido,</span>
        <span class="user-name">
            {{ auth()->user()->persona->nombre }}
        </span>
    </h1>
</div>