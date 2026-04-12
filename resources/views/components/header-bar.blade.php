<style>
    .box {
        display: flex;
        flex-direction: row;
        align-items: center; 
        justify-content: space-between;
        padding: 0 40px;
        border-radius: 12px;    
        position: fixed;    /* Se queda fija en la pantalla */
        top: 10px;          /* Mantiene la separación del techo */
        left: 10px;         /* Alineada con el padding lateral */
        right: 10px;        /* Alineada con el padding lateral */
        width: calc(100% - 20px); /* Ajuste para que no se salga por el padding */
        z-index: 1000;      /* La pone por encima de todo */
        height: 5.5rem; 
        background: #111827;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .pf-header {
        display: flex;
        align-items: center;
        padding: 0; 
        border-bottom: none; 
    }
    .pf-logo-link {
        text-decoration: none;
        transition: opacity 0.2s ease;
    }
    .pf-logo {
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        font-size: 1.4rem;
    }
    .pf-logo-box {
        background: #305be3;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
    }
    .content-bar {
        color: white;
        font-weight: 800;
        font-size: 1.4rem; /* Un poco más pequeño para que quepa bien en la tablet */
        margin: 0;
    }
</style>
<div class="box">
    @if(auth()->check() && auth()->user()->role_id == 4)
    <a href="{{ route('distribuidora.dashboard') }}" class="pf-logo-link">
        <div class="pf-logo">
            <div class="pf-logo-box">PF</div>
            <span>Préstamo Fácil</span>
        </div>
    </a>
    @endif
    @if(auth()->check() && auth()->user()->role_id == 3)
    <a href="{{ route('verificador.dashboard') }}" class="pf-logo-link">
        <div class="pf-logo">
            <div class="pf-logo-box">PF</div>
            <span>Préstamo Fácil</span>
        </div>
    </a>
    @endif
    <h1 class="content-bar">
        Bienvenido(a)
        <span style="text-transform: capitalize; color:white">
            {{ auth()->user()->persona->nombre }}!!
        </span>
    </h1>
</div>