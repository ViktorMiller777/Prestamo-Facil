<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión Expirada | Préstamo Fácil</title>
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

        .icon-circle {
            width: 80px; height: 80px;
            background: #fffbeb;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--warning);
            border: 1px solid #fef3c7;
            margin: 0 auto 2rem;
        }

        .error-code {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--warning);
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
            margin-bottom: 2rem;
        }

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
            padding: 16px 28px;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            width: 100%;
            justify-content: center;
        }

        .btn-reload:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        @media (max-width: 600px) {
            .card { padding: 30px 20px; }
            .error-title { font-size: 1.75rem; }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-circle">
            <i data-lucide="history" style="width: 36px; height: 36px;"></i>
        </div>

        <span class="error-code">Error 419 &mdash; Page Expired</span>
        <h1 class="error-title">Tu sesión ha expirado</h1>

        <p class="error-desc">{{ $message }}</p>

        <div class="suggestion-box">
            <i data-lucide="info" style="color: var(--accent); flex-shrink: 0; width: 20px;"></i>
            <span>{{ $suggestion }}</span>
        </div>

        <button onclick="window.location.reload()" class="btn-reload">
            <i data-lucide="refresh-cw" style="width: 18px;"></i>
            Actualizar Página
        </button>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>