<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Distribuidora</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #f1f5f9;
            --primary: #6366f1;
            --success: #16a34a;
            --danger: #ef4444;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { margin:0; padding:0; box-sizing:border-box; font-family:'Plus Jakarta Sans', sans-serif; }
        
        body {
            background-color: var(--bg);
            padding: 30px;
            /* Espacio para que no se meta debajo de tu x-header-bar */
            padding-top: 9rem !important; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        /* Encabezado Superior */
        .header-detalle {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 10px;
        }

        .header-info h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: white;
            color: var(--text-main);
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .btn-back:active { transform: scale(0.95); background: #f8fafc; }

        /* Tarjetas de Información */
        .info-card {
            background: white;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .grid-data {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .dato-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .dato-item label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .dato-item span {
            font-size: 1.2rem;
            color: var(--text-main);
            font-weight: 600;
        }

        .badge-status {
            background: #fee2e2;
            color: #991b1b;
            padding: 6px 16px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 800;
        }

        /* Botón de Acción Gigante para Tablet */
        .btn-activar {
            width: 100%;
            padding: 25px;
            background: var(--success);
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 800;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            box-shadow: 0 15px 30px rgba(22, 163, 74, 0.25);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 20px;
            margin-bottom: 50px;
        }

        .btn-activar:active {
            transform: scale(0.96);
            filter: brightness(0.9);
        }

        /* Toast de Notificación */
        #toast {
            position: fixed;
            top: 40px;
            right: 40px;
            padding: 20px 30px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            z-index: 10000;
            display: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        /* Estilos para Documentos */
        .doc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .btn-documento {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-documento:hover {
            border-color: var(--primary);
            background: #f1f5f9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .doc-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .doc-icon {
            width: 45px;
            height: 45px;
            background: #e0e7ff;
            color: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .doc-text {
            display: flex;
            flex-direction: column;
        }

        .doc-name {
            font-weight: 700;
            color: var(--text-main);
            font-size: 1rem;
        }

        .doc-type {
            font-size: 0.8rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <x-header-bar />

    <div class="container">
        <div class="header-detalle">
            <div class="header-info">
                <h2>
                    <i data-lucide="user-check" style="color: var(--primary); width: 36px; height: 36px;"></i>
                    Detalle del Registro
                    <span class="badge-status">Presolicitud</span>
                </h2>
            </div>
            <button class="btn-back" onclick="window.history.back()">
                <i data-lucide="arrow-left"></i> Volver
            </button>
        </div>

        <div class="info-card">
            <p class="section-title">
                <i data-lucide="id-card" style="width: 20px;"></i>
                Datos Personales
            </p>
            <div class="grid-data">
                <div class="dato-item">
                    <label>Nombre Completo</label>
                    <span>{{ $distribuidora->usuario->persona->nombre }} {{ $distribuidora->usuario->persona->apellido }}</span>
                </div>
                <div class="dato-item">
                    <label>Sexo</label>
                    <span>{{ $distribuidora->usuario->persona->sexo == 'M' ? 'Masculino' : 'Femenino' }}</span>
                </div>
                <div class="dato-item">
                    <label>Fecha de Nacimiento</label>
                    <span>{{ \Carbon\Carbon::parse($distribuidora->usuario->persona->fecha_nacimiento)->format('d/m/Y') }}</span>
                </div>
                <div class="dato-item">
                    <label>CURP</label>
                    <span style="font-family: monospace; letter-spacing: 1px;">{{ $distribuidora->usuario->persona->CURP }}</span>
                </div>
                <div class="dato-item">
                    <label>RFC</label>
                    <span>{{ $distribuidora->usuario->persona->RFC ?? 'No registrado' }}</span>
                </div>
                <div class="dato-item">
                    <label>Celular de Contacto</label>
                    <span>{{ $distribuidora->usuario->persona->celular }}</span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <p class="section-title">
                <i data-lucide="shield-check" style="width: 20px;"></i>
                Información de Distribuidora
            </p>
            <div class="grid-data">
                <div class="dato-item">
                    <label>Correo Electrónico</label>
                    <span>{{ $distribuidora->usuario->email }}</span>
                </div>
                <div class="dato-item">
                    <label>Línea de Crédito Solicitada</label>
                    <span style="color: var(--success); font-weight: 800;">
                        ${{ number_format($distribuidora->linea_credito, 2) }}
                    </span>
                </div>
                <div class="dato-item">
                    <label>ID de Sistema</label>
                    <span>#{{ $distribuidora->id }}</span>
                </div>
                <div class="dato-item">
                    <label>Puntos Iniciales</label>
                    <span>{{ $distribuidora->puntos }} pts</span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <p class="section-title">
                <i data-lucide="file-text" style="width: 20px;"></i>
                Documentación Digital
            </p>
            <div class="doc-grid">
                @forelse($distribuidora->documentos as $doc)
                    @php
                        // Generamos una URL temporal firmada (expira en 10 minutos)
                        // Esto soluciona el error de Access Denied al ser archivos privados
                        $url = \Illuminate\Support\Facades\Storage::disk('spaces')->temporaryUrl(
                            $doc->archivo_path, 
                            now()->addMinutes(10)
                        );
                    @endphp
                    <a href="{{ $url }}" target="_blank" class="btn-documento">
                        <div class="doc-info">
                            <div class="doc-icon">
                                <i data-lucide="file-digit"></i>
                            </div>
                            <div class="doc-text">
                                <span class="doc-name">{{ $doc->tipo }}</span>
                                <span class="doc-type">Ver Archivo Adjunto</span>
                            </div>
                        </div>
                        <i data-lucide="external-link" style="color: var(--text-muted); width: 20px;"></i>
                    </a>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 20px; color: var(--text-muted);">
                        <i data-lucide="alert-circle" style="margin-bottom: 10px; opacity: 0.5;"></i>
                        <p>No hay documentos digitales disponibles para esta distribuidora.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <button class="btn-activar" id="confirmBtn" onclick="activarDistribuidora({{ $distribuidora->id }})">
            <i data-lucide="check-circle-2" style="width: 32px; height: 32px;"></i>
            Confirmar Distribuidora
        </button>
    </div>

    <div id="toast"></div>

    <script>
        lucide.createIcons();

        function activarDistribuidora(id) {
            const btn = document.getElementById('confirmBtn');
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader-2" class="spin"></i> Procesando...';
            lucide.createIcons();

            fetch(`/api/inactivar/distribuidora/${id}`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.res) {
                    mostrarToast('✅ Activación exitosa. Redirigiendo...', 'success');
                    
                    // RUTA CORREGIDA: Regresa a notificaciones después del éxito
                    setTimeout(() => {
                        window.location.href = "{{ route('verificador.presolicitud') }}";
                    }, 1500);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<i data-lucide="check-circle-2"></i> Confirmar Distribuidora';
                    lucide.createIcons();
                    mostrarToast('❌ ' + (data.mensaje || 'Error al procesar'), 'error');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check-circle-2"></i> Confirmar Distribuidora';
                lucide.createIcons();
                mostrarToast('❌ Error de conexión con el servidor', 'error');
            });
        }

        function mostrarToast(mensaje, tipo) {
            const toast = document.getElementById('toast');
            toast.textContent = mensaje;
            toast.style.display = 'block';
            toast.style.background = tipo === 'success' ? '#16a34a' : '#ef4444';
            
            setTimeout(() => {
                toast.style.display = 'none';
            }, 4000);
        }
    </script>
</body>
</html>