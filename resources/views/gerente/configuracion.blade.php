<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración - Gerente</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body, html {
            width: 100%;
            height: 100%;
            background-color: #f8fafc;
        }
        .dashboard-container {
            display: flex;
            width: 100%;
            height: 100vh;
        }
        .contenido {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .header-title h1 {
            color: #0f172a;
            font-size: 2rem;
            font-weight: 800;
        }

        .icon-box {
            background: #8b5cf6;
            color: white;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.3);
        }

        .config-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .config-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s;
        }

        .config-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .card-icon {
            padding: 10px;
            border-radius: 10px;
            background: #f1f5f9;
            color: #64748b;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .card-content p {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #0f172a;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .btn-action:hover {
            background: #334155;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <x-aside-bar/>
        <main class="contenido">
            <div class="header-title">
                <div class="icon-box">
                    <i data-lucide="settings" style="width: 32px; height: 32px;"></i>
                </div>
                <h1>Configuración del Sistema</h1>
            </div>
            <div class="config-grid">
                <!-- Parámetros del Sistema (Dinámicos) -->
                <div class="config-card" style="grid-column: 1 / -1;">
                    <div class="card-header">
                        <div class="card-icon"><i data-lucide="database"></i></div>
                        <h2>Parámetros de Base de Datos</h2>
                    </div>
                    <div class="card-content">
                        <p>Valores actuales almacenados en la tabla de configuraciones.</p>
                        
                        <form action="{{ route('gerente.configuracion.update') }}" method="POST">
                            @csrf
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                @foreach($configuraciones as $config)
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    <label style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">
                                        {{ str_replace('_', ' ', $config->clave) }}
                                    </label>
                                    <div style="position: relative; display: flex; align-items: center;">
                                        @if(str_contains($config->clave, 'puntos') || str_contains($config->clave, 'recargo'))
                                            <span style="position: absolute; left: 12px; color: #64748b; font-weight: 700;">$</span>
                                            <input type="text" name="configuraciones[{{ $config->clave }}]" value="{{ $config->valor }}" 
                                                style="padding: 10px 10px 10px 25px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.9rem; width: 100%;">
                                        @else
                                            <input type="text" name="configuraciones[{{ $config->clave }}]" value="{{ $config->valor }}" 
                                                style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.9rem; width: 100%;">
                                        @endif
                                    </div>
                                    @if($config->descripcion)
                                        <small style="color: #94a3b8; font-size: 0.75rem;">{{ $config->descripcion }}</small>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            <button type="submit" class="btn-action" style="margin-top: 30px; border: none; cursor: pointer; width: 100%; justify-content: center;">
                                <i data-lucide="save" style="width: 18px;"></i> Guardar Cambios en Sistema
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
