<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña | Préstamo Fácil</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { 
            min-height: 100vh; 
            display: flex; 
            background-color: #f8fafc;
            color: #1e293b;
        }

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

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

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
                <h1>Restablecer Acceso.</h1>
                <p>Estás a un paso de recuperar tu cuenta. Crea una nueva contraseña segura para volver a gestionar tus finanzas con nosotros.</p>
            </div>

            <div class="brand-footer">
                &copy; {{ date('Y') }} Préstamo Fácil México.
            </div>
        </div>

        <!-- Right Section -->
        <div class="form-section">
            <div class="login-box">
                <div class="login-header">
                    <h2>Nueva Contraseña</h2>
                    <p>Por favor ingresa tu nueva clave de acceso.</p>
                </div>

                @if ($errors->any())
                    <div class="alert-error">
                        @foreach ($errors->all() as $error)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i data-lucide="alert-circle" style="width: 18px;"></i>
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-group">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-wrapper">
                            <i data-lucide="mail"></i>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nueva Contraseña</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirmar Contraseña</label>
                        <div class="input-wrapper">
                            <i data-lucide="shield-check"></i>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        Actualizar Contraseña
                        <i data-lucide="save" style="width: 18px;"></i>
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
