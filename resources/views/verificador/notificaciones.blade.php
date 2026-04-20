<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presolicitudes - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #f1f5f9;
            --primary: #6366f1;
            --danger-bg: #fef2f2;
            --danger-text: #991b1b;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg);
            padding: 30px;
            padding-top: 7rem !important; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .box-2 {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            padding: 35px;
            width: 100%;
            max-width: 1100px;
            border: 1px solid rgba(0,0,0,0.05);
            /* Margen adicional por seguridad */
            margin-top: 10px; 
        }

        .box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8fafc;
        }

        .box-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .badge-total {
            background: var(--danger-bg);
            color: var(--danger-text);
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            border: 1px solid #fee2e2;
        }

        .table-container {
            overflow: hidden;
            border-radius: 15px;
            border: 1px solid #f1f5f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            text-align: left;
            padding: 22px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
            background: #f8fafc;
        }

        td {
            padding: 25px 22px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-main);
            font-size: 1.1rem;
            vertical-align: middle;
        }

        tbody tr {
            transition: background 0.2s;
            cursor: pointer;
        }

        /* Feedback táctil para la tablet */
        tbody tr:active {
            background-color: #f1f5f9;
        }

        .dist-name { font-weight: 700; display: block; }
        .dist-sub { font-size: 0.95rem; color: var(--text-muted); display: block; margin-top: 4px; }
        
        .credit-amount {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #0f172a;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 8px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff7ed;
            color: #9a3412;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            border: 1px solid #ffedd5;
        }
    </style>
</head>
<body>

    <x-header-bar />

    <div class="box-2">
        <div class="box-header">
            <h2>
                <i data-lucide="users-round" style="color: var(--primary); width: 32px; height: 32px;"></i>
                Presolicitudes de Distribuidoras 
            </h2>
            <span class="badge-total">{{ $distribuidoras->count() }} PENDIENTES</span>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Distribuidora</th>
                        <th>Contacto</th>
                        <th>Crédito</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Ver</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distribuidoras as $dist)
                    <tr onclick="verDistribuidora({{ $dist->id }})">
                        <td>
                            <span class="dist-name">{{ $dist->usuario->persona->nombre }} {{ $dist->usuario->persona->apellido }}</span>
                            <span class="dist-sub">ID: #{{ $dist->id }}</span>
                        </td>
                        <td>
                            <span class="dist-name" style="font-weight: 500;">{{ $dist->usuario->persona->celular }}</span>
                            <span class="dist-sub">{{ $dist->usuario->email }}</span>
                        </td>
                        <td>
                            <span class="credit-amount">${{ number_format($dist->linea_credito, 2) }}</span>
                        </td>
                        <td>
                            <span class="status-pill">
                                <i data-lucide="clock" style="width: 16px; height: 16px;"></i>
                                Presolicitud
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <i data-lucide="chevron-right" style="color: var(--text-muted); width: 28px; height: 28px;"></i>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:100px; color:var(--text-muted);">
                            <i data-lucide="inbox" style="width: 60px; height: 60px; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p>No hay preosolicitudes pendientes.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function verDistribuidora(id) {
            window.location.href = `/verificador/distribuidora/${id}`;
        }
    </script>
</body>
</html>