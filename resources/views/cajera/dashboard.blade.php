<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cajera - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }
    body, html {
        width: 100%;
        height: 100%;
        background-color: #f4f7f6;
    }
    .dashboard-container {
        display: flex;
        width: 100%;
        height: 100vh;
    }
    .contenido {
        flex: 1;
        width: 100%;
        padding: 40px;
        overflow-y: auto;
    }

    h1 {
        color: #111827;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    /* Panel de contenido dinámico */
    .panel {
        background: white;
        border-radius: 12px;
        padding: 24px;
        height: 90%;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        overflow-y: auto;
    }  
    .loading {
        text-align: center;
        padding: 40px;
        color: #9ca3af;
    }
</style>
<body>
    <div class="dashboard-container">
        <x-aside-bar/>
        <main class="contenido">
            <h1>
                Bienvenido al Panel
                <span style="text-transform: capitalize;">
                    {{ auth()->user()->persona->nombre }}!!
                </span>
            </h1>

            <div class="panel" id="panel-contenido">
                <p class="loading">Selecciona una opción del menú.</p>
            </div>
        </main>
    </div>
</body>
</html>