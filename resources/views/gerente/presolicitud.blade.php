<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerente - Pre-solicitudes</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
    body, html { width:100%; height:100%; background-color:#f8fafc; color: #1e293b; }
    
    .dashboard-container { display:flex; width:100%; height:100vh; }
    .contenido { flex:1; width:100%; padding:40px; overflow-y:auto; }
    
    .header-section { margin-bottom: 30px; }
    h1 { color:#0f172a; font-size:1.875rem; font-weight: 700; }
    .welcome-text { color: #64748b; margin-top: 4px; }

    .panel { 
        background:white; 
        border-radius:16px; 
        padding:0; 
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
        background-color: #ffffff;
    }
    .box-header h2 { font-size:1.1rem; font-weight:700; color:#334155; }
    
    .table-container { overflow-x: auto; }
    table { width:100%; border-collapse:collapse; font-size:0.875rem; }
    thead tr { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    th { text-align:left; padding:16px; color:#475569; font-weight:600; text-transform:uppercase; font-size:0.7rem; letter-spacing: 0.05em; }
    td { padding:16px; border-bottom:1px solid #f1f5f9; color:#334155; vertical-align: middle; }
    tbody tr:hover { background:#f1f5f9; transition: background 0.2s; }

    .user-info { display: flex; flex-direction: column; }
    .user-name { font-weight: 600; color: #0f172a; }
    .user-sub { font-size: 0.75rem; color: #64748b; }

    .badge { padding:4px 12px; border-radius:9999px; font-size:0.7rem; font-weight:700; text-transform: uppercase; }
    .badge-red { background:#fee2e2; color:#991b1b; border: 1px solid #fecaca; }
    
    .btn-action {
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #0f172a;
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-action:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-doc {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #64748b;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .btn-doc:hover {
        background: #3b82f6;
        color: white;
        transform: translateY(-2px);
    }
</style>
<body>
    <div class="dashboard-container">
        <x-aside-bar/>
        
        <main class="contenido">
            <div class="header-section">
                <h1>Panel de Gerencia</h1>
                <p class="welcome-text">
                    Bienvenido, <span style="font-weight: 600; color: #3b82f6;">{{ auth()->user()->persona->nombre }}</span>. 
                    Tienes tareas pendientes hoy.
                </p>
            </div>

            <div class="panel">
                <div class="box-header">
                    <div>
                        <h2>Pre-solicitudes de Distribuidoras</h2>
                        <p style="font-size: 0.8rem; color: #64748b; font-weight: 400;">Listado de registros en espera de activación.</p>
                    </div>
                    <span class="badge" style="background: #eff6ff; color: #1e40af;">
                        {{ $distribuidoras->count() }} Pendientes
                    </span>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Distribuidora</th>
                                <th>Contacto</th>
                                <th>Línea de Crédito</th>
                                <th>Puntos</th>
                                <th>Estado</th>
                                <th>Documentos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($distribuidoras as $dist)
                            <tr>
                                <td style="color: #94a3b8; font-weight: 500;">#{{ $dist->id }}</td>
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">{{ $dist->usuario->persona->nombre }} {{ $dist->usuario->persona->apellido }}</span>
                                        <span class="user-sub">{{ $dist->usuario->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <span><i data-lucide="phone" style="width: 12px; display: inline; margin-right: 4px;"></i>{{ $dist->usuario->persona->celular }}</span>
                                    </div>
                                </td>
                                <td style="font-weight: 600; color: #0f172a;">
                                    ${{ number_format($dist->linea_credito, 2) }}
                                </td>
                                <td>
                                    <span style="display: flex; align-items: center; gap: 4px;">
                                        <i data-lucide="star" style="width: 14px; color: #eab308; fill: #eab308;"></i>
                                        {{ $dist->puntos }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-red">
                                        {{ $dist->estado }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        @php
                                            $ine = $dist->documentos->where('tipo', 'INE')->first();
                                            $comprobante = $dist->documentos->where('tipo', 'Comprobante Domicilio')->first();
                                        @endphp
                                        @if($ine)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('spaces')->temporaryUrl($ine->archivo_path, now()->addMinutes(10)) }}" target="_blank" class="btn-doc" title="Ver INE">
                                                <i data-lucide="contact-2" style="width: 14px;"></i>
                                            </a>
                                        @endif
                                        @if($comprobante)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('spaces')->temporaryUrl($comprobante->archivo_path, now()->addMinutes(10)) }}" target="_blank" class="btn-doc" title="Ver Comprobante">
                                                <i data-lucide="home" style="width: 14px;"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button class="btn-action" onclick="activarDistribuidora({{ $dist->id }}, this)" 
                                            style="background: #d1fae5; color: #065f46; border-color: #6ee7b7;">
                                        <i data-lucide="check-circle" style="width: 14px;"></i>
                                        Activar
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:60px;">
                                    <i data-lucide="inbox" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 10px;"></i>
                                    <p style="color:#94a3b8; font-size: 1rem;">No hay distribuidoras inactivas en este momento.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function activarDistribuidora(id, boton) {

            const originalHTML = boton.innerHTML;
            boton.disabled = true;
            boton.innerHTML = "Activando...";

            fetch(`/api/activar/distribuidora/${id}`, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.res) {
                    const fila = boton.closest('tr');
                    fila.style.transition = 'all 0.5s';
                    fila.style.opacity = '0';
                    fila.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        fila.remove();
                    }, 500);

                    if(typeof mostrarToast === "function") {
                        mostrarToast('✅ Distribuidora activada con éxito');
                    }
                }
            })
            .catch(err => {
                boton.disabled = false;
                boton.innerHTML = originalHTML;
                alert('Error al activar');
            });
        }
        lucide.createIcons();
    </script>
</body>
</html>