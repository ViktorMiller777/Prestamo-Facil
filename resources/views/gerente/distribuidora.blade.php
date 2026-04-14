<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerente - Distribuidoras</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
    body, html { width:100%; height:100%; background-color:#f4f7f6; }
    .dashboard-container { display:flex; width:100%; height:100vh; }
    .contenido { flex:1; width:100%; padding:40px; overflow-y:auto; }
    h1 { color:#111827; font-size:1.8rem; margin-bottom:20px; }
    .panel { background:white; border-radius:12px; padding:24px; height:90%; box-shadow:0 1px 4px rgba(0,0,0,0.08); overflow-y:auto; }
    .box-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .box-header h2 { font-size:1.2rem; font-weight:700; color:#111827; }
    table { width:100%; border-collapse:collapse; font-size:0.9rem; }
    thead tr { background:#f9fafb; border-bottom:2px solid #e5e7eb; }
    th { text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem; }
    td { padding:12px 16px; border-bottom:1px solid #f3f4f6; color:#374151; }
    tbody tr:hover { background:#f9fafb; cursor:pointer; }
    .badge { padding:3px 10px; border-radius:99px; font-size:0.75rem; font-weight:600; }
    .badge-green { background:#d1fae5; color:#065f46; }
    .badge-red   { background:#fee2e2; color:#991b1b; }
</style>
<body>
    <div class="dashboard-container">
        <x-aside-bar/>
        <main class="contenido">
            <h1>
                Bienvenido al Panel
                <span style="text-transform:capitalize;">
                    {{ auth()->user()->persona->nombre }}!!
                </span>
            </h1>

            <div class="panel">
                <div class="box-header">
                    <h2>Distribuidoras</h2>
                    <span style="font-size:0.85rem; color:#6b7280;">
                        Total: {{ $distribuidoras->count() }}
                    </span>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Celular</th>
                            <th>Línea de Crédito</th>
                            <th>Puntos</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distribuidoras as $dist)
                        <tr>
                            <td>{{ $dist->id }}</td>
                            <td><b>{{ $dist->usuario->persona->nombre }} {{ $dist->usuario->persona->apellido }}</b></td>
                            <td>{{ $dist->usuario->email }}</td>
                            <td>{{ $dist->usuario->persona->celular }}</td>
                            <td>${{ number_format($dist->linea_credito, 2) }}</td>
                            <td>{{ $dist->puntos }}</td>
                            <td>
                                <span class="badge {{ $dist->estado == 'activo' ? 'badge-green' : 'badge-red' }}">
                                    {{ ucfirst($dist->estado) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                No hay distribuidoras registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>