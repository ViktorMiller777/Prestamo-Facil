<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algo salió mal | Préstamo Fácil</title>
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
        }

        .card {
            position: relative;
            background: var(--white);
            border-radius: 24px;
            padding: 50px;
            max-width: 580px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            border: 1px solid var(--border);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            margin-bottom: 2rem;
            font-size: 1.2rem;
        }
        .logo-box {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 5px 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 900;
        }

        .icon-circle {
            width: 80px; height: 80px;
            background: #f1f5f9;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            border: 1px solid var(--border);
            margin: 0 auto 2rem;
        }

        .error-code {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            display: block;
            opacity: 0.8;
        }

        .error-title {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 1.2rem;
            line-height: 1.1;
        }

        .error-desc {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--accent);
            color: white;
            padding: 16px 28px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
            width: 100%;
            justify-content: center;
        }

        .btn-home:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .card-footer {
            margin-top: 2rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: var(--text-muted);
        }

        @media (max-width: 600px) {
            .card { padding: 30px 20px; }
            .error-title { font-size: 1.75rem; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-badge">
            <span class="logo-box">PF</span>
            Préstamo Fácil
        </div>

        <div class="icon-circle">
            <i data-lucide="alert-circle" style="width: 36px; height: 36px;"></i>
        </div>

        <span class="error-code">Error 500 &mdash; Servidor</span>
        <h1 class="error-title">Algo salió mal</h1>

        <p class="error-desc">{{ $message ?? 'Ha ocurrido un error inesperado en nuestro sistema. Estamos trabajando para solucionarlo lo antes posible.' }}</p>

        <a href="{{ url('/') }}" class="btn-home">
            <i data-lucide="home" style="width: 18px;"></i>
            Volver al inicio
        </a>

        <div class="card-footer">
            <i data-lucide="shield-check" style="width: 16px;"></i>
            &copy; {{ date('Y') }} Préstamo Fácil &bull; Soporte Técnico
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>