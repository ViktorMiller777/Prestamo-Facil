<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Distribuidora - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #f8fafc;
            --dark: #0f172a;
            --border: #e2e8f0;
            --btn-purple: #8b5cf6;
            --btn-blue: #3b82f6;
            --btn-green: #10b981;
            --btn-orange: #f59e0b;
            --btn-gray: #64748b;
            --btn-red: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            /* Espacio para que no lo tape tu header fijo de 5.5rem */
            padding-top: 6rem !important; 
        }

        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            max-width: 1200px; /* Un poco más ancho para las 6 opciones */
            margin: 0 auto;
            width: 100%;
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 3rem;
        }

        .welcome-section h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
        }

        .welcome-section p {
            color: #64748b;
            font-size: 1.2rem;
            margin-top: 5px;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            width: 100%;
        }

        .option-card {
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 280px; 
            border-radius: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 4px solid transparent;
        }

        .option-card:active {
            transform: scale(0.95);
            filter: brightness(0.9);
        }

        /* Colores usando tus mismas sombras de Verificador */
        .card-green  { background: var(--btn-green); box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.3); }
        .card-blue   { background: var(--btn-blue); box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.3); }
        .card-purple { background: var(--btn-purple); box-shadow: 0 20px 25px -5px rgba(139, 92, 246, 0.3); }
        .card-gray   { background: var(--btn-gray); box-shadow: 0 20px 25px -5px rgba(100, 116, 139, 0.3); }
        .card-orange { background: var(--btn-orange); box-shadow: 0 20px 25px -5px rgba(245, 158, 11, 0.3); }
        .card-red    { background: var(--btn-red); box-shadow: 0 20px 25px -5px rgba(239, 68, 68, 0.3); }

        .icon-container {
            background: rgba(255, 255, 255, 0.2);
            padding: 25px;
            border-radius: 50%;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .option-card h1 {
            color: white;
            font-size: 1.4rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .option-card span {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin-top: 8px;
            font-weight: 500;
        }

        @media (max-width: 850px) {
            .options-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    
    <x-header-bar />

    <div class="main-container">
        <div class="welcome-section">
            <h2>Panel de Distribuidora</h2>
            <p>Selecciona una acción para continuar</p>
        </div>

        <div class="options-grid">
            
            <a href="{{ route('productos') }}" class="option-card card-green">
                <div class="icon-container">
                    <i data-lucide="plus-circle" style="width: 48px; height: 48px; color: white;"></i>
                </div>
                <h1>Crear Prevale</h1>
                <span>Generar nuevo folio</span>
            </a>

            <a href="{{ route('distribuidora.clientes') }}" class="option-card card-blue">
                <div class="icon-container">
                    <i data-lucide="users" style="width: 48px; height: 48px; color: white;"></i>
                </div>
                <h1>Clientes</h1>
                <span>Administrar cartera</span>
            </a>

            <a href="{{ route('relaciones') }}" class="option-card card-purple">
                <div class="icon-container">
                    <i data-lucide="share-2" style="width: 48px; height: 48px; color: white;"></i>
                </div>
                <h1>Relaciones</h1>
                <span>Historial y vínculos</span>
            </a>

            <a href="#" class="option-card card-gray">
                <div class="icon-container">
                    <i data-lucide="settings" style="width: 48px; height: 48px; color: white;"></i>
                </div>
                <h1>Ajustes</h1>
                <span>Configurar cuenta</span>
            </a>

            <a href="{{ route('distribuidora.vale') }}" class="option-card card-orange">
                <div class="icon-container">
                    <i data-lucide="ticket" style="width: 48px; height: 48px; color: white;"></i>
                </div>
                <h1>Vales</h1>
                <span>Consulta de estados</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">
                @csrf
            </form>
            <a href="#" onclick="document.getElementById('logout-form').submit();" class="option-card card-red">
                <div class="icon-container">
                    <i data-lucide="log-out" style="width: 48px; height: 48px; color: white;"></i>
                </div>
                <h1>Cerrar Sesión</h1>
                <span>Salir del sistema</span>
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>