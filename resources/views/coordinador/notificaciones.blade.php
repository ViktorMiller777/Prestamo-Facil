<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-soft: #eff6ff;
            --bg: #f8fafc;
            --dark: #0f172a;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --white: #ffffff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            display: flex;
            min-height: 100vh;
        }

        /* Layout Structure */
        .main-content {
            flex: 1;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header-title h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            letter-spacing: -0.025em;
        }

        .header-title p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-top: 4px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--white);
            padding: 24px;
            border-radius: 20px;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #dcfce7; color: #16a34a; }
        .stat-icon.orange { background: #ffedd5; color: #ea580c; }

        .stat-info h3 { font-size: 1.5rem; font-weight: 800; color: var(--dark); }
        .stat-info p { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }

        /* Notifications Section */
        .notif-container {
            background: var(--white);
            border-radius: 24px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .notif-header {
            padding: 24px 32px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notif-header h2 { font-size: 1.1rem; font-weight: 700; color: var(--dark); }

        .notif-item {
            padding: 24px 32px;
            display: flex;
            gap: 20px;
            border-bottom: 1px solid var(--border);
            transition: background 0.2s;
            cursor: pointer;
            position: relative;
        }

        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #f8fafc; }

        .notif-item.unread::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
        }

        .notif-avatar {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notif-content { flex: 1; }
        .notif-content h4 { font-size: 0.95rem; font-weight: 700; color: var(--dark); margin-bottom: 4px; }
        .notif-content p { font-size: 0.9rem; color: var(--text-muted); line-height: 1.4; }
        
        .notif-meta {
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-width: 100px;
        }

        .notif-time { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); }
        .notif-badge {
            align-self: flex-end;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Type Badges */
        .badge-solicitud { background: #eff6ff; color: #2563eb; }
        .badge-pago { background: #ecfdf5; color: #10b981; }
        .badge-alerta { background: #fef2f2; color: #ef4444; }

        .btn-ver-todo {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-ver-todo:hover { background: var(--primary-soft); }

    </style>
</head>
<body>

    <!-- Sidebar Component -->
    @include('components.aside-bar')

    <main class="main-content">
        <header class="header">
            <div class="header-title">
                <h1>Centro de Notificaciones</h1>
                <p>Hola, {{ auth()->user()->persona->nombre }}. Aquí tienes las últimas actualizaciones.</p>
            </div>
            <div class="header-actions">
                <!-- Aquí podrías poner un botón de filtrar o algo similar -->
            </div>
        </header>

        <!-- Stats Overview (Sketch/Mockup) -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i data-lucide="bell"></i>
                </div>
                <div class="stat-info">
                    <h3>12</h3>
                    <p>Total Hoy</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i data-lucide="check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>8</h3>
                    <p>Atendidas</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i data-lucide="clock"></i>
                </div>
                <div class="stat-info">
                    <h3>4</h3>
                    <p>Pendientes</p>
                </div>
            </div>
        </div>

        <!-- Notification List (Mockup/Boceto) -->
        <div class="notif-container">
            <div class="notif-header">
                <h2>Actividad Reciente</h2>
                <a href="#" class="btn-ver-todo">Marcar todas como leídas</a>
            </div>

            <!-- Item 1 -->
            <div class="notif-item unread">
                <div class="notif-avatar" style="background: #e0f2fe;">
                    <i data-lucide="user-plus" style="color: #0369a1;"></i>
                </div>
                <div class="notif-content">
                    <h4>Nueva Pre-solicitud de Distribuidora</h4>
                    <p><strong>María García</strong> ha enviado una solicitud de registro para la sucursal Centro.</p>
                </div>
                <div class="notif-meta">
                    <span class="notif-time">Hace 5 min</span>
                    <span class="notif-badge badge-solicitud">Solicitud</span>
                </div>
            </div>

            <!-- Item 2 -->
            <div class="notif-item unread">
                <div class="notif-avatar" style="background: #dcfce7;">
                    <i data-lucide="dollar-sign" style="color: #15803d;"></i>
                </div>
                <div class="notif-content">
                    <h4>Pago Recibido - Vale #F-9928</h4>
                    <p>La distribuidora <strong>Juan Pérez</strong> ha liquidado el vale correspondiente a la quincena actual.</p>
                </div>
                <div class="notif-meta">
                    <span class="notif-time">Hace 25 min</span>
                    <span class="notif-badge badge-pago">Pago</span>
                </div>
            </div>

            <!-- Item 3 -->
            <div class="notif-item">
                <div class="notif-avatar" style="background: #fef2f2;">
                    <i data-lucide="alert-triangle" style="color: #b91c1c;"></i>
                </div>
                <div class="notif-content">
                    <h4>Alerta de Morosidad</h4>
                    <p>La distribuidora <strong>Laura Torres</strong> ha excedido la fecha límite de pago por 3 días.</p>
                </div>
                <div class="notif-meta">
                    <span class="notif-time">Hace 2 horas</span>
                    <span class="notif-badge badge-alerta">Urgente</span>
                </div>
            </div>

            <!-- Item 4 -->
            <div class="notif-item">
                <div class="notif-avatar" style="background: #f3f4f6;">
                    <i data-lucide="info" style="color: #4b5563;"></i>
                </div>
                <div class="notif-content">
                    <h4>Actualización de Sistema</h4>
                    <p>Se han actualizado las tasas de recargos según la nueva configuración del gerente.</p>
                </div>
                <div class="notif-meta">
                    <span class="notif-time">Ayer, 4:30 PM</span>
                    <span class="notif-badge" style="background: #f3f4f6; color: #4b5563;">Info</span>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Inicializar iconos de Lucide
        lucide.createIcons();
    </script>
</body>
</html>