<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #f8fafc;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --white: #ffffff;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); min-height: 100vh; display: flex; }

        .main-content { flex: 1; padding: 40px; overflow-y: auto; }
        
        .header-section { margin-bottom: 40px; }
        .header-section h1 { font-size: 2rem; font-weight: 800; color: var(--text-main); }
        .header-section p { color: var(--text-muted); margin-top: 5px; }

        .profile-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
            max-width: 1200px;
        }

        .card {
            background: var(--white);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.03);
            border: 1px solid var(--border);
        }

        /* Sidebar del Perfil */
        .profile-side {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .avatar-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
        }
        .user-name { font-size: 1.5rem; font-weight: 700; margin-bottom: 5px; }
        .user-role { 
            background: #eef2ff; 
            color: var(--primary); 
            padding: 5px 15px; 
            border-radius: 999px; 
            font-size: 0.85rem; 
            font-weight: 700; 
            text-transform: uppercase;
        }

        .info-list { width: 100%; margin-top: 30px; border-top: 1px solid var(--border); padding-top: 25px; }
        .info-item { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; color: var(--text-muted); font-size: 0.95rem; }
        .info-item i { color: var(--primary); width: 18px; }

        /* Formularios */
        .form-section { margin-bottom: 30px; }
        .form-section h3 { font-size: 1.2rem; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; margin-bottom: 20px; }
        .form-group label { font-size: 0.9rem; font-weight: 600; color: var(--text-main); }
        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 1rem;
            outline: none;
            transition: all 0.2s;
            background: #fcfdfe;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: white;
        }

        .btn-save {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .btn-save:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(99, 102, 241, 0.3); }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }

        @media (max-width: 1000px) {
            .profile-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <x-aside-bar />

    <main class="main-content">
        <div class="header-section">
            <h1>Configuración de Cuenta</h1>
            <p>Gestiona tu información personal y seguridad</p>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success">¡Tu información de perfil ha sido actualizada!</div>
        @endif
        @if (session('status') === 'password-updated')
            <div class="alert alert-success">¡Tu contraseña se ha cambiado correctamente!</div>
        @endif

        <div class="profile-grid">
            <!-- Sidebar del Usuario -->
            <div class="card profile-side">
                <div class="avatar-circle">
                    {{ substr($user->persona->nombre, 0, 1) }}
                </div>
                <h2 class="user-name">{{ $user->persona->nombre }} {{ $user->persona->apellido }}</h2>
                <span class="user-role">{{ $user->role->role }}</span>

                <div class="info-list">
                    <div class="info-item">
                        <i data-lucide="mail"></i>
                        <span>{{ $user->email }}</span>
                    </div>
                    <div class="info-item">
                        <i data-lucide="smartphone"></i>
                        <span>{{ $user->persona->celular }}</span>
                    </div>
                    <div class="info-item">
                        <i data-lucide="building-2"></i>
                        <span>Sucursal: {{ $user->sucursal->nombre ?? 'Principal' }}</span>
                    </div>
                    <div class="info-item">
                        <i data-lucide="calendar"></i>
                        <span>Miembro desde: {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Formularios de Edición -->
            <div class="card">
                <!-- Información de Perfil -->
                <div class="form-section">
                    <h3><i data-lucide="user"></i> Información Personal</h3>
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nombre completo</label>
                                <input type="text" class="form-control" value="{{ $user->persona->nombre }} {{ $user->persona->apellido }}" readonly style="background: #f1f5f9; cursor: not-allowed;">
                                <small style="color: #94a3b8; font-size: 0.75rem;">El nombre solo puede ser cambiado por un administrador.</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                @error('email') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn-save">
                            <i data-lucide="save"></i> Guardar Cambios
                        </button>
                    </form>
                </div>

                <div style="height: 40px;"></div>

                <!-- Seguridad -->
                <div class="form-section">
                    <h3><i data-lucide="shield-check"></i> Seguridad y Contraseña</h3>
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="current_password">Contraseña Actual</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" autocomplete="current-password">
                            @error('current_password', 'updatePassword') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="password">Nueva Contraseña</label>
                                <input type="password" id="password" name="password" class="form-control" autocomplete="new-password">
                                @error('password', 'updatePassword') <small style="color: #ef4444;">{{ $message }}</small> @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirmar Contraseña</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
                            </div>
                        </div>

                        <button type="submit" class="btn-save">
                            <i data-lucide="key"></i> Actualizar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
