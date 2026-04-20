<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora | Relaciones</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
    body, html {
        padding-top: 4rem !important;
        display: flex;
        flex-direction: column;
        gap: 25px;
        padding: 20px;
        width: 100%;
        min-height: 100vh;
        background-color: #f4f7f6;
    }
    
    .box-2 { 
        border-radius:12px; 
        width:100%; 
        background:white; 
        box-shadow:0 4px 6px rgba(0,0,0,0.05); 
        padding:25px; 
    }

    h1 { font-size: 1.5rem; color: #111827; margin-bottom: 20px; font-weight: 700; }

    /* Estilos de la Tabla */
    .table-container { overflow-x: auto; }
    
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
    
    thead tr { background-color: #f8fafc; border-bottom: 2px solid #e5e7eb; }
    
    th { padding: 12px 15px; color: #4b5563; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
    
    td { padding: 15px; border-bottom: 1px solid #f1f5f9; color: #374151; vertical-align: middle; }
    
    tr:hover { background-color: #f9fafb; }

    /* Badges y Formatos */
    .badge-referencia { background: #e0e7ff; color: #3730a3; padding: 4px 8px; border-radius: 6px; font-family: monospace; font-weight: 600; }
    .text-bold { font-weight: 700; color: #111827; }
    .text-money { color: #059669; font-weight: 600; }
</style>
<body>
    <x-header-bar />

    <div class="box-2">
        <h1>Relaciones de Cobro - {{ Auth::user()->persona->nombre }}</h1>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Fecha Límite</th>
                        <th>Límite Crédito</th>
                        <th>Disponible</th>
                        <th>Recargos</th>
                        <th>Total a Pagar</th>
                        <th>Ver Detalle</th> 
                    </tr>
                </thead>
                <tbody>
                    @forelse($relaciones as $rel)
                        <tr>
                            <td>
                                <span class="badge-referencia">{{ $rel->referencia_de_pago }}</span>
                                <br><small style="color: #9ca3af">{{ $rel->pagos_realizados }}</small>
                            </td>
                            <td>
                                <span class="text-bold">{{ \Carbon\Carbon::parse($rel->fecha_limite_pago)->format('d/m/Y') }}</span>
                                <br><small>Anticipado: {{ \Carbon\Carbon::parse($rel->pago_anticipado)->format('d/m/Y') }}</small>
                            </td>
                            <td>${{ number_format($rel->limite_de_credito, 2) }}</td>
                            <td>${{ number_format($rel->credito_disponible, 2) }}</td>
                            <td style="{{ $rel->recargos > 0 ? 'color: #dc2626; font-weight: bold;' : '' }}">
                                ${{ number_format($rel->recargos, 2) }}
                            </td>
                            <td class="text-money" style="font-size: 1.1rem;">
                                ${{ number_format($rel->total_pagar, 2) }}
                            </td>
                             <td>
                            <a href="{{ route('detalle_vale', $rel->id) }}" class="btn-detalle">
                                Ver Detalle
                            </a>
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: #9ca3af;">
                                No se encontraron relaciones de pago registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>