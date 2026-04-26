<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio en Mantenimiento | Préstamo Fácil</title>
    <meta name="description" content="Estamos realizando mantenimiento en nuestros servidores. Volveremos pronto.">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --bg: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --accent: #3b82f6;
            --border: #e2e8f0;
            --warning: #f59e0b;
            --success: #10b981;
            --danger: #ef4444;
        }

        *, *::before, *::after {
            margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text-main);
            display: flex; 
            align-items: center;
            justify-content: center;
            overflow-y: auto;
            padding: 20px;
            position: relative;
        }

        /* ── Cleanup for removed animated background ── */
        .bg-orb, .dot-grid { display: none; }

        /* ── Tarjeta principal ── */
        .card {
            position: relative;
            z-index: 10;
            background: var(--white);
            border-radius: 24px;
            padding: 50px;
            max-width: 580px;
            width: calc(100% - 2rem);
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid var(--border);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Ícono central ── */
        .icon-wrapper {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .icon-ring {
            position: absolute;
            border-radius: 50%;
            border: 1.5px solid rgba(239, 68, 68, 0.2);
            animation: ripple 2.5s ease-out infinite;
        }
        .icon-ring:nth-child(1) { width: 90px;  height: 90px;  animation-delay: 0s; }
        .icon-ring:nth-child(2) { width: 120px; height: 120px; animation-delay: 0.5s; }
        .icon-ring:nth-child(3) { width: 150px; height: 150px; animation-delay: 1s; }
        @keyframes ripple {
            0%   { transform: scale(0.9); opacity: 0.6; }
            100% { transform: scale(1.1); opacity: 0; }
        }
        .icon-circle {
            position: relative;
            z-index: 1;
            width: 80px; height: 80px;
            background: #fef2f2;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--danger);
            border: 1px solid #fee2e2;
        }
        .icon-circle i { width: 36px; height: 36px; }

        /* ── Código de error ── */
        .error-code {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--danger);
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 99px;
            padding: 4px 16px;
            display: inline-block;
            margin-bottom: 1.25rem;
        }

        /* ── Título ── */
        .error-title {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--text-main); /* Use main text color */
            margin-bottom: 1.2rem;
            line-height: 1.1;
        }

        /* ── Descripción ── */
        .error-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        /* ── Separador ── */
        .divider {
            height: 1px; /* Keep separator */
            background: var(--border);
            margin: 2rem 0;
        }

        /* ── Sugerencia y Botón (Consistencia con CSRF) ── */
        .suggestion-box {
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
        }

        .btn-reload {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--accent);
            color: white;
            padding: 14px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            width: 100%;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .btn-reload:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        /* ── Chips de estado ── */
        .status-chips {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 2.25rem;
        }
        .chip {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #f1f5f9;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 0.75rem;
            color: var(--text-main);
            font-weight: 700;
            text-transform: uppercase;
        }
        .chip-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
        }
        .chip-dot.red    { background: var(--danger); animation: blink 1.5s ease-in-out infinite; }
        .chip-dot.yellow { background: var(--warning); }
        .chip-dot.green  { background: var(--success); }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        /* ── Footer ── */
        .card-footer {
            margin-top: 2rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: var(--text-muted);
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
        }
        .logo-box {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 900;
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .card { padding: 30px 20px; width: 100%; }
            .error-title { font-size: 1.75rem; margin-bottom: 0.8rem; }
            .error-desc { font-size: 0.95rem; }
            .icon-ring:nth-child(1) { width: 75px;  height: 75px; }
            .icon-ring:nth-child(2) { width: 100px; height: 100px; }
            .icon-ring:nth-child(3) { width: 125px; height: 125px; }
            .icon-circle { width: 65px; height: 65px; border-radius: 18px; }
            .icon-circle i { width: 28px; height: 28px; }
            .status-chips { gap: 8px; margin-bottom: 1.5rem; }
        }
    </style>
</head>
<body>
    <!-- Tarjeta -->
    <div class="card">
        <!-- Logo -->
        <div style="margin-bottom: 2rem;">
            <div class="logo-badge" style="color: var(--text-main); font-size: 1.2rem;">
                <span class="logo-box" style="padding: 5px 12px; font-size: 0.9rem;">PF</span>
                Préstamo Fácil
            </div>
        </div>

        <!-- Ícono animado -->
        <div class="icon-wrapper">
            <div class="icon-ring"></div>
            <div class="icon-ring"></div>
            <div class="icon-ring"></div>
            <div class="icon-circle">
                <i data-lucide="server-off"></i>
            </div>
        </div>

        <span class="error-code">{{ isset($error_type) ? str_replace('_', ' ', $error_type) : 'Error 503 — Database Connection' }}</span>
        <h1 class="error-title">Servicio en Mantenimiento</h1>

        <!-- Descripción -->
        <p class="error-desc">
            {{ $message ?? 'Estamos experimentando dificultades técnicas con nuestro servidor de datos. Nuestro equipo ya está trabajando para restablecer el servicio.' }}
        </p>

        @if(isset($suggestion))
            <div class="suggestion-box">
                <i data-lucide="info" style="color: var(--accent); flex-shrink: 0; width: 20px;"></i>
                <span>{{ $suggestion }}</span>
            </div>
        @endif

        @if(isset($auto_reload) && $auto_reload)
            <button onclick="window.location.reload()" class="btn-reload">
                <i data-lucide="refresh-cw" style="width: 18px;"></i>
                Reintentar Conexión
            </button>
        @endif

        <div class="divider"></div>

        <!-- Estado de los componentes -->
        <div class="status-chips">
            <div class="chip">
                <span class="chip-dot red"></span>
                Base de datos
            </div>
            <div class="chip">
                <span class="chip-dot yellow"></span>
                Servidor web
            </div>
            <div class="chip">
                <span class="chip-dot green"></span>
                Aplicación
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <i data-lucide="shield-check" style="width: 16px;"></i>
            &copy; {{ date('Y') }} Préstamo Fácil &bull; Soporte Técnico
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>