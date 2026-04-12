{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Clientes | Préstamo Fácil</title>

    <style>
        /* RESET & VARIABLES */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        :root {
            --pf-dark-blue: #0f172a;
            --pf-main-blue: #305be3;
            --pf-main-hover: #1e40af;
            --pf-green: #22c55e;
            --pf-orange: #f59e0b;
            --pf-bg: #f8fafc;
            --pf-border: #e2e8f0;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: var(--pf-bg);
            color: #1e293b;
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
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1.8fr 1fr;
            gap: 40px;
        }

        @media (max-width: 900px) {
            .pf-main { grid-template-columns: 1fr; margin: 20px auto; }
        }

        /* CARD LOGIN */
        .login-card {
            background: white;
            padding: 20px;
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
            margin-bottom: 35px;
        }

        .status-badge svg { width: 18px; color: var(--pf-green); }

        .title { font-size: 2.6rem; font-weight: 800; margin-bottom: 40px; color: var(--pf-dark-blue); }

        /* FORMULARIO */
        .form-group { margin-bottom: 25px; }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            margin-left: 4px;
        }

        .form-control {
            width: 100%;
            padding: 15px 16px;
            border: 2px solid var(--pf-border);
            border-radius: 14px;
            font-size: 1rem;
            transition: all 0.2s;
            outline: none;
            background: #fcfcfd;
        }

        .form-control:focus {
            border-color: var(--pf-main-blue);
            background: white;
            box-shadow: 0 0 0 4px rgba(48, 91, 227, 0.08);
        }

        .actions {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-top: 35px;
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
            box-shadow: 0 12px 25px rgba(48, 91, 227, 0.3);
        }

        .link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .link:hover { text-decoration: underline; }

        /* SIDE PANEL */
        .side-info {
            background: #fffbeb;
            padding: 40px;
            border-radius: 28px;
            border: 1px solid #fef3c7;
            align-self: start;
        }

        .side-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #92400e;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .side-text { color: #92400e; font-size: 0.9rem; line-height: 1.7; margin-bottom: 15px; }

        /* FOOTER */
        .footer {
            background: var(--pf-dark-blue);
            color: #64748b;
            padding: 40px;
            text-align: center;
            font-size: 0.8rem;
            margin-top: 60px;
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
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Conexión cifrada de alta seguridad
            </div>

            <h1 class="title">¡Bienvenido!</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">CORREO</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required autofocus>
                    
                    @error('email')
                        <span style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">CONTRASEÑA</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
                    
                    @error('password')
                        <span style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="actions">
                    <button type="submit" class="btn-primary">Ingresar al Panel</button>
                </div>
            </form>
        </section>

        <aside class="side-info">
            <div class="side-title">
                <svg style="width:24px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Consejo de Seguridad
            </div>
            <p class="side-text">
                Nunca compartas tus claves con nadie. <strong>Préstamo Fácil</strong> no solicita contraseñas por WhatsApp o llamadas telefónicas.
            </p>
            <p class="side-text">
                Asegúrate de que la URL sea <strong>https://tu-dominio.com</strong> antes de ingresar.
            </p>
            <a href="#" class="link" style="font-size: 0.85rem">Centro de seguridad →</a>
        </aside>
    </main>

    <footer class="footer">
        © 2026 Préstamo Fácil México. Todos los derechos reservados.
    </footer>
</body>
</html>