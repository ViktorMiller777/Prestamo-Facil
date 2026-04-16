<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Clientes | Préstamo Fácil</title>

    {{-- CARGA DE RECAPTCHA V2 (Checkbox) --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        /* RESET & VARIABLES */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --pf-dark-blue: #0f172a;
            --pf-main-blue: #305be3;
            --pf-main-hover: #1e40af;
            --pf-green: #22c55e;
            --pf-bg: #f8fafc;
            --pf-border: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--pf-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .pf-header {
            background: #111827;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--pf-border);
        }

        .pf-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.4rem;
            color: white;
        }

        .pf-logo-box {
            background: var(--pf-main-blue);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
        }

        /* LAYOUT */
        .pf-main {
            max-width: 1150px;
            margin: 20px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 40px;
            flex: 1; /* ✅ esto es lo único que le falta */
        }

        @media (max-width: 900px) {
            .pf-main { grid-template-columns: 1fr; margin: 10px auto; }
        }

        /* CARD LOGIN */
        .login-card {
            background: white;
            padding: 25px;
            border-radius: 28px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.03);
            border: 1px solid #f1f5f9;
        }

        .status-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 20px;
        }

        .status-badge svg { width: 18px; color: var(--pf-green); }

        .title { font-size: 1.6rem; font-weight: 800; margin-bottom: 20px; color: var(--pf-dark-blue); }

        /* FORMULARIO */
        .form-group { margin-bottom: 25px; }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 5px;
            margin-left: 4px;
        }

        .form-control {
            width: 100%;
            padding: 10px 6px;
            border: 2px solid var(--pf-border);
            border-radius: 5px;
            font-size: 0.9rem;
            transition: all 0.2s;
            outline: none;
            background: #fcfcfd;
        }

        .form-control:focus {
            border-color: var(--pf-main-blue);
            background: white;
            box-shadow: 0 0 0 4px rgba(48, 91, 227, 0.08);
        }

        /* Contenedor del Captcha */
        .captcha-wrapper {
            margin: 25px 0;
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-top: 20px;
        }

        .btn-primary {
            background: var(--pf-main-blue);
            color: white;
            border: none;
            padding: 18px 45px;
            border-radius: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(48, 91, 227, 0.2);
        }

        .btn-primary:hover {
            background: var(--pf-main-hover);
            transform: translateY(-2px);
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #B91C1C;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* SIDE PANEL */
        .side-info {
            background: #fffbeb;
            padding: 40px;
            border-radius: 28px;
            border: 1px solid #fef3c7;
            align-self: start;
        }

        .side-title { color: #92400e; font-weight: 800; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .side-text { color: #92400e; font-size: 0.9rem; line-height: 1.7; margin-bottom: 15px; }

        .footer {
            background: var(--pf-dark-blue);
            color: #64748b;
            padding: 40px;
            text-align: center;
            font-size: 1rem;
            margin-bottom:0px
        }
    </style>
</head>
<body>

    <header class="pf-header">
        <div class="pf-logo">
            <div class="pf-logo-box">PF</div>
            Préstamo Fácil
        </div>
    </header>

    <main class="pf-main">
        <section class="login-card">
            <div class="status-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Verificación de seguridad requerida
            </div>

            <h1 class="title">¡Bienvenido!</h1>

            {{-- Bloque de errores mejorado --}}
            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <span>• {{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">CORREO ELECTRÓNICO</label>
                    <input type="email" name="email" class="form-control" 
                           value="{{ old('email') }}" placeholder="ejemplo@correo.com" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">CONTRASEÑA</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    <a href="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                {{-- WIDGET RECAPTCHA V2 (Checkbox) --}}
                <div class="captcha-wrapper">
                    <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary">
                        Ingresar al Panel
                    </button>
                </div>
            </form>
        </section>

        <aside class="side-info">
            <div class="side-title">
                <svg style="width:24px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Consejo de Seguridad
            </div>
            <p class="side-text">
                Al usar <strong>reCAPTCHA v2</strong>, asegúrate de marcar la casilla y seleccionar las imágenes correspondientes si el sistema lo solicita.
            </p>
            <p class="side-text">
                Este proceso ayuda a mantener tus datos protegidos contra ataques automatizados.
            </p>
        </aside>
    </main>

    <footer class="footer">
        © 2026 Préstamo Fácil México. Todos los derechos reservados.
    </footer>

</body>
</html>