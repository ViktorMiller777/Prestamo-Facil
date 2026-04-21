<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coordinador - Listado de Vales</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<style>
    /* Reset y Base */
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
    body, html { width:100%; height:100%; background-color:#f8fafc; color: #1e293b; }
    
    .dashboard-container { display:flex; width:100%; height:100vh; }
    .contenido { flex:1; width:100%; padding:40px; overflow-y:auto; }
    
    /* Encabezado */
    .header-section { margin-bottom: 30px; }
    h1 { color:#0f172a; font-size:1.875rem; font-weight: 700; }
    .welcome-text { color: #64748b; margin-top: 4px; }

    /* Panel */
    .panel { 
        background:white; 
        border-radius:16px; 
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); 
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .box-header { 
        display:flex; 
        justify-content:space-between; 
        align-items:center; 
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 16px;
    }
    .box-header h2 { font-size:1.1rem; font-weight:700; color:#334155; }
    
    /* Search Input */
    .search-container {
        position: relative;
        width: 100%;
        max-width: 350px;
    }
    .search-input {
        width: 100%;
        padding: 10px 14px 10px 40px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.2s;
    }
    .search-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    /* Tabla Estilizada */
    .table-container { overflow-x: auto; }
    table { width:100%; border-collapse:collapse; font-size:0.875rem; }
    thead tr { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    th { text-align:left; padding:16px; color:#475569; font-weight:600; text-transform:uppercase; font-size:0.7rem; letter-spacing: 0.05em; }
    td { padding:16px; border-bottom:1px solid #f1f5f9; color:#334155; vertical-align: middle; }
    tbody tr:hover { background:#f1f5f9; transition: background 0.2s; }

    /* Componentes de celda */
    .folio-badge {
        font-family: monospace;
        font-weight: 700;
        color: #2563eb;
        background: #eff6ff;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .badge { padding:4px 12px; border-radius:9999px; font-size:0.7rem; font-weight:700; text-transform: uppercase; }
    .status-activo { background:#d1fae5; color:#065f46; border: 1px solid #a7f3d0; }
    .status-prevale { background:#fef3c7; color:#92400e; border: 1px solid #fde68a; }
    .status-liquidado { background:#e0e7ff; color:#3730a3; border: 1px solid #c7d2fe; }
    
    /* Paginación */
    .pagination-container { 
        padding: 24px 0; 
        margin: 0 24px;
        display: flex; 
        justify-content: center; 
        border-top: 1px solid #f1f5f9;
    }
    .pagination { display: flex; list-style: none; gap: 8px; padding: 0; align-items: center; }
    .page-item .page-link { 
        display: flex; align-items: center; justify-content: center;
        min-width: 40px; height: 40px; padding: 0 14px; border-radius: 8px;
        border: 1px solid #e2e8f0; background: white; color: #475569;
        text-decoration: none; font-size: 0.95rem; font-weight: 600; transition: all 0.2s;
    }
    .page-item.active .page-link { background: #3b82f6; color: white; border-color: #3b82f6; }
</style>
<body>
    <div class="dashboard-container">
        <x-aside-bar/>
        
        <main class="contenido">
            <div class="header-section">
                <h1>Listado General de Vales</h1>
                <p class="welcome-text">Supervisión y filtrado de vales emitidos en el sistema.</p>
            </div>

            <div class="panel">
                <div class="box-header">
                    <div>
                        <h2>Vales Registrados</h2>
                        <p style="font-size: 0.8rem; color: #64748b; font-weight: 400;">Filtrado por nombre de distribuidora.</p>
                    </div>
                    
                    <div class="search-container">
                        <form action="{{ route('coordinador.vales') }}" method="GET">
                            <i data-lucide="search" class="search-icon" style="width: 18px;"></i>
                            <input type="text" name="distribuidora" class="search-input" placeholder="Buscar por distribuidora..." value="{{ request('distribuidora') }}">
                        </form>
                    </div>

                    <span class="badge" style="background: #eff6ff; color: #1e40af; border: none; padding: 8px 16px;">
                        Total: {{ $vales->total() }}
                    </span>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Folio</th>
                                <th>Cliente</th>
                                <th>Distribuidora</th>
                                <th>Monto</th>
                                <th>Pago Q.</th>
                                <th>Plazo</th>
                                <th>Estado</th>
                                <th>Fecha Emisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vales as $v)
                            <tr>
                                <td><span class="folio-badge">{{ $v->folio }}</span></td>
                                <td>
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="font-weight: 600;">{{ $v->cliente->persona->nombre }} {{ $v->cliente->persona->apellido }}</span>
                                        <span style="font-size: 0.75rem; color: #64748b;">{{ $v->cliente->persona->CURP }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; flex-direction: column;">
                                        <span style="font-weight: 500;">{{ $v->distribuidora->usuario->persona->nombre }} {{ $v->distribuidora->usuario->persona->apellido }}</span>
                                    </div>
                                </td>
                                <td style="font-weight: 600;">${{ number_format($v->detalleVale->monto ?? $v->producto->monto, 2) }}</td>
                                <td style="color: #059669; font-weight: 600;">${{ number_format($v->detalleVale->pago ?? 0, 2) }}</td>
                                <td>{{ $v->producto->quincenas }} q.</td>
                                <td>
                                    @php
                                        $statusClass = match($v->estado) {
                                            'activo' => 'status-activo',
                                            'prevale' => 'status-prevale',
                                            'liquidado' => 'status-liquidado',
                                            default => ''
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $v->estado }}</span>
                                </td>
                                <td style="color: #64748b; font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($v->fecha_emision)->format('d/m/Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align:center; padding:60px;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                        <i data-lucide="search-x" style="width: 48px; height: 48px; color: #cbd5e1;"></i>
                                        <p style="color:#94a3b8; font-size: 1rem;">No se encontraron vales con esos criterios.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $vales->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
