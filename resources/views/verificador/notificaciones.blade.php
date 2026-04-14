<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidoras Inactivas</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }
    body, html {
        padding-top: 4rem !important; 
        display:flex;
        flex-direction: column;
        gap:25px;
        padding:10px;
        width: 100%;
        height: 100%;
        background-color: #f4f7f6;
    }
    .box-2 {
        border-radius: 10px;
        width: 100%;
        background: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 20px;
    }
    table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
    thead tr { background: #f9fafb; border-bottom: 2px solid #e5e7eb; }
    th { text-align: left; padding: 12px 16px; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
    td { padding: 12px 16px; border-bottom: 1px solid #f3f4f6; color: #374151; }
    tbody tr:hover { background: #f9fafb; }
    .badge-red { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; }
    .btn-activar { padding: 6px 14px; background: #16a34a; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: 600; }
    .btn-activar:hover { background: #15803d; }
    .box-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .box-header h2 { font-size: 1.1rem; font-weight: 700; color: #111827; }
</style>
<body>
    <x-header-bar />
    <div class="box-2">
        <div class="box-header">
            <h2>Distribuidoras Inactivas</h2>
            <span class="badge-red">{{ $distribuidoras->count() }} pendientes</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Celular</th>
                    <th>Línea de Crédito</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($distribuidoras as $dist)
                <tr style="cursor:pointer;" onclick="verDistribuidora({{ $dist->id }})">
                    <td>{{ $dist->id }}</td>
                    <td><b>{{ $dist->usuario->persona->nombre }} {{ $dist->usuario->persona->apellido }}</b></td>
                    <td>{{ $dist->usuario->email }}</td>
                    <td>{{ $dist->usuario->persona->celular }}</td>
                    <td>${{ number_format($dist->linea_credito, 2) }}</td>
                    <td><span class="badge-red">Inactiva</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:#9ca3af;">
                        No hay distribuidoras inactivas.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
<script>
    function verDistribuidora(id) {
        window.location.href = `/verificador/distribuidora/${id}`;
    }
</script>
</html>