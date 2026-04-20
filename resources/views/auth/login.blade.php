<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Clientes | Préstamo Fácil</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { 
            min-height: 100vh; 
            display: flex; 
            background-color: #f8fafc;
            color: #1e293b;
        }

        /* Split Screen Layout */
        .login-container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Side - Branding */
        .brand-section {
            flex: 1;
            background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .brand-section::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.04) 0%, transparent 60%);
            animation: pulse 15s infinite linear;
            pointer-events: none;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.5rem;
            font-weight: 800;
            z-index: 10;
        }

        .logo-icon {
            background: white;
            color: #1e40af;
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 900;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .brand-content {
            z-index: 10;
            max-width: 480px;
            margin-bottom: auto;
            margin-top: 15vh;
        }

        .brand-content h1 {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #ffffff, #93c5fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-content p {
            font-size: 1.1rem;
            color: #cbd5e1;
            line-height: 1.6;
        }

        .brand-footer {
            z-index: 10;
            font-size: 0.85rem;
            color: #94a3b8;
        }

        /* Right Side - Form */
        .form-section {
            flex: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            margin-bottom: 2.5rem;
        }

        .login-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #64748b;
            font-size: 1rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 20px;
            transition: color 0.3s;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            color: #0f172a;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .input-wrapper:focus-within i {
            color: #3b82f6;
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 0.85rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            margin-top: 0.5rem;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        /* Captcha */
        .captcha-container {
            margin: 2rem 0;
            display: flex;
            justify-content: center;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 12px;
            border: 1px dashed #cbd5e1;
        }

        /* Button */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25);
        }

        .btn-submit:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Errors */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-weight: 500;
        }

        .alert-error span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .brand-section { display: none; }
            .form-section { flex: 1; padding: 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Left Section -->
        <div class="brand-section">
            <div class="brand-logo">
                <span class="logo-icon">PF</span>
                Préstamo Fácil
            </div>
            
            <div class="brand-content">
                <h1>Tu futuro financiero empieza aquí.</h1>
                <p>Accede a tu panel de control para gestionar créditos, revisar estados de cuenta y administrar tus movimientos de forma segura y rápida.</p>
            </div>

            <div class="brand-footer">
                &copy; {{ date('Y') }} Préstamo Fácil México. Todos los derechos reservados.<br>
                Protegido por reCAPTCHA y Google.
            </div>
        </div>

        <!-- Right Section -->
        <div class="form-section">
            <div class="login-box">
                <div class="login-header">
                    <h2>Bienvenido de nuevo</h2>
                    <p>Ingresa tus credenciales para continuar</p>
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <span><i data-lucide="alert-circle" style="width: 18px;"></i> {{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <i data-lucide="mail"></i>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="ejemplo@correo.com" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contraseña</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <a href="forgot-password" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <div class="captcha-container">
                        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Ingresar al Sistema
                        <i data-lucide="arrow-right" style="width: 18px;"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

</body>
</html>