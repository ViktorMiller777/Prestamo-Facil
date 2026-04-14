<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vales</title>
</head>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
    body, html {
        padding-top: 4rem !important;
        display: flex;
        flex-direction: column;
        gap: 25px;
        padding: 10px;
        width: 100%;
        height: 100%;
        background-color: #f4f7f6;
    }
    .box-2 { border-radius:10px; width:100%; background:white; box-shadow:0 4px 6px rgba(0,0,0,0.1); padding:20px; }
    .box-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .box-header h2 { font-size:1.1rem; font-weight:700; color:#111827; }
    table { width:100%; border-collapse:collapse; font-size:0.9rem; }
    thead tr { background:#f9fafb; border-bottom:2px solid #e5e7eb; }
    th { text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem; }
    td { padding:12px 16px; border-bottom:1px solid #f3f4f6; color:#374151; }
    tbody tr:hover { background:#f9fafb; }
    .badge { padding:3px 10px; border-radius:99px; font-size:0.75rem; font-weight:600; }
    .badge-green  { background:#d1fae5; color:#065f46; }
    .badge-yellow { background:#fef9c3; color:#854d0e; }
    .badge-red    { background:#fee2e2; color:#991b1b; }
</style>
<body>
    <x-header-bar />
    <div class="box-2">
        <div class="box-header">
            <h2>Mis Vales</h2>
            <span style="font-size:0.85rem; color:#6b7280;">Total: {{ $vales->count() }}</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Estado</th>
                    <th>Fecha Emisión</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vales as $vale)
                <tr>
                    <td><b>{{ $vale->folio }}</b></td>
                    <td>
                        {{ $vale->cliente->persona->nombre }}
                        {{ $vale->cliente->persona->apellido }}
                    </td>
                    <td>${{ number_format($vale->producto->monto, 2) }} / {{ $vale->producto->quincenas }} Qnas</td>
                    <td>
                        <span class="badge {{ 
                            $vale->estado == 'activo'  ? 'badge-green' : 
                            ($vale->estado == 'prevale' ? 'badge-yellow' : 'badge-red') 
                        }}">
                            {{ ucfirst($vale->estado) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($vale->fecha_emision)->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:#9ca3af;">
                        No tienes vales registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>