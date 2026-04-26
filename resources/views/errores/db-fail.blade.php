<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio en Mantenimiento | Préstamo Fácil</title>
    <meta name="description" content="Estamos realizando mantenimiento en nuestros servidores. Volveremos pronto.">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        *, *::before, *::after {
            margin: 0; padding: 0; box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #0c1a3a 50%, #0f172a 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ── Fondo animado ── */
        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: drift 20s ease-in-out infinite alternate;
            pointer-events: none;
        }
        .bg-orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #1e40af, transparent);
            top: -200px; left: -200px;
            animation-delay: 0s;
        }
        .bg-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #7c3aed, transparent);
            bottom: -150px; right: -100px;
            animation-delay: -8s;
        }
        .bg-orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, #0369a1, transparent);
            top: 50%; left: 60%;
            animation-delay: -4s;
        }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, -40px) scale(1.08); }
        }

        /* ── Grid de puntos ── */
        .dot-grid {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        /* ── Tarjeta principal ── */
        .card {
            position: relative;
            z-index: 10;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 28px;
            padding: 3.5rem 4rem;
            max-width: 640px;
            width: calc(100% - 2rem);
            text-align: center;
            box-shadow:
                0 40px 80px rgba(0,0,0,0.5),
                0 0 0 1px rgba(255,255,255,0.05) inset;
            animation: slideUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to   { opacity: 1; transform: translateY(0); }
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
            border: 1px solid rgba(239, 68, 68, 0.3);
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
            width: 72px; height: 72px;
            background: linear-gradient(135deg, rgba(239,68,68,0.2), rgba(239,68,68,0.05));
            border: 1px solid rgba(239,68,68,0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f87171;
        }
        .icon-circle i { width: 32px; height: 32px; }

        /* ── Código de error ── */
        .error-code {
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #f87171;
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
            line-height: 1.2;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #ffffff, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Descripción ── */
        .error-desc {
            font-size: 1.05rem;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        /* ── Separador ── */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.1), transparent);
            margin: 2rem 0;
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
            gap: 8px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 99px;
            padding: 8px 16px;
            font-size: 0.85rem;
            color: #cbd5e1;
            font-weight: 500;
        }
        .chip-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .chip-dot.red    { background: #ef4444; box-shadow: 0 0 6px #ef4444; animation: blink 1.5s ease-in-out infinite; }
        .chip-dot.yellow { background: #f59e0b; box-shadow: 0 0 6px #f59e0b; }
        .chip-dot.green  { background: #22c55e; box-shadow: 0 0 6px #22c55e; }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }

        /* ── Footer ── */
        .card-footer {
            margin-top: 2.5rem;
            font-size: 0.8rem;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .card-footer i { width: 14px; height: 14px; }
        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 0.95rem;
            color: #e2e8f0;
            margin-bottom: 0.5rem;
        }
        .logo-box {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 900;
            box-shadow: 0 2px 8px rgba(37,99,235,0.4);
        }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .card { padding: 2.5rem 1.75rem; }
            .error-title { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

    <!-- Orbes de fondo -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <div class="dot-grid"></div>

    <!-- Tarjeta -->
    <div class="card">

        <!-- Logo -->
        <div style="margin-bottom: 2rem;">
            <div class="logo-badge">
                <span class="logo-box">PF</span>
                Préstamo Fácil
            </div>
        </div>

        <!-- Ícono animado -->
        <div class="icon-wrapper">
            <div class="icon-ring"></div>
            <div class="icon-ring"></div>
            <div class="icon-ring"></div>
            <div class="icon-circle">
                <i data-lucide="database-zap"></i>
            </div>
        </div>

        <!-- Badge de error -->
        <div class="error-code">Error 503 &mdash; Servicio no disponible</div>

        <!-- Título -->
        <h1 class="error-title">Servicio en<br>Mantenimiento</h1>

        <!-- Descripción -->
        <p class="error-desc">
            Estamos experimentando dificultades técnicas con nuestro servidor de datos.<br>
            Nuestro equipo ya está trabajando para restablecer el servicio.<br>
            Por favor, intenta de nuevo en unos minutos.
        </p>

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
            <i data-lucide="shield-check"></i>
            &copy; {{ date('Y') }} Préstamo Fácil México &mdash; Todos los derechos reservados.
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>