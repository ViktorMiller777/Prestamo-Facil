<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidoras - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg: #f1f5f9;
            --primary: #3b82f6;
            --success-bg: #d1fae5;
            --success-text: #065f46;
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
            margin-top: 10px; 
        }

        .box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f8fafc;
            flex-wrap: wrap;
            gap: 20px;
        }

        .box-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Search Input */
        .search-container {
            position: relative;
            width: 100%;
            max-width: 350px;
        }
        .search-input {
            width: 100%;
            padding: 12px 14px 12px 45px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
        }
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: white;
        }
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .badge-total {
            background: #eff6ff;
            color: #1e40af;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            border: 1px solid #dbeafe;
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
        }

        tbody tr:hover {
            background-color: #f8fafc;
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

        .status-badge {
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .status-activo { background: var(--success-bg); color: var(--success-text); }
        .status-presolicitud { background: #fff7ed; color: #9a3412; }
        .status-moroso { background: var(--danger-bg); color: var(--danger-text); }
        .status-inactivo { background: #f1f5f9; color: #64748b; }

        /* Pagination Styles */
        .pagination-container { 
            padding: 24px 0; 
            margin-top: 20px;
            display: flex; 
            justify-content: center; 
        }
        .pagination { display: flex; list-style: none; gap: 8px; padding: 0; align-items: center; }
        .page-item .page-link { 
            display: flex; align-items: center; justify-content: center;
            min-width: 45px; height: 45px; padding: 0 16px; border-radius: 12px;
            border: 1px solid #e2e8f0; background: white; color: #475569;
            text-decoration: none; font-size: 1rem; font-weight: 600; transition: all 0.2s;
        }
        .page-item.active .page-link { 
            background: var(--primary); 
            color: white; 
            border-color: var(--primary); 
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }
        .page-item.disabled .page-link { color: #cbd5e1; background: #f8fafc; cursor: not-allowed; }
    </style>
</head>
<body>

    <x-header-bar />

    <div class="box-2">
        <div class="box-header">
            <h2>
                <i data-lucide="users" style="color: var(--primary); width: 32px; height: 32px;"></i>
                Listado General de Distribuidoras 
            </h2>
            
            <div class="search-container">
                <form action="{{ route('verificador.distribuidoras') }}" method="GET">
                    <i data-lucide="search" class="search-icon"></i>
                    <input type="text" name="buscar" class="search-input" placeholder="Buscar por nombre..." value="{{ request('buscar') }}">
                </form>
            </div>

            <span class="badge-total">{{ $distribuidoras->total() }} TOTAL</span>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Distribuidora</th>
                        <th>Contacto</th>
                        <th>Crédito</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distribuidoras as $dist)
                    <tr>
                        <td>
                            <span class="dist-name">{{ $dist->usuario->persona->nombre }} {{ $dist->usuario->persona->apellido }}</span>
                        </td>
                        <td>
                            <span class="dist-name" style="font-weight: 500;">{{ $dist->usuario->persona->celular }}</span>
                            <span class="dist-sub">{{ $dist->usuario->email }}</span>
                        </td>
                        <td>
                            <span class="credit-amount">${{ number_format($dist->linea_credito, 2) }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($dist->estado) {
                                    'activo' => 'status-activo',
                                    'presolicitud' => 'status-presolicitud',
                                    'moroso' => 'status-moroso',
                                    'inactivo' => 'status-inactivo',
                                    default => ''
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                {{ $dist->estado }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:100px; color:var(--text-muted);">
                            <i data-lucide="search-x" style="width: 60px; height: 60px; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p>No se encontraron distribuidoras.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $distribuidoras->appends(request()->input())->links('pagination::bootstrap-4') }}
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
