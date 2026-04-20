<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerente - Distribuidoras</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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

    /* Panel con el diseño de la foto */
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
    }
    .box-header h2 { font-size:1.1rem; font-weight:700; color:#334155; }
    
    /* Tabla Estilizada */
    .table-container { overflow-x: auto; }
    table { width:100%; border-collapse:collapse; font-size:0.875rem; }
    thead tr { background:#f8fafc; border-bottom:1px solid #e2e8f0; }
    th { text-align:left; padding:16px; color:#475569; font-weight:600; text-transform:uppercase; font-size:0.7rem; letter-spacing: 0.05em; }
    td { padding:16px; border-bottom:1px solid #f1f5f9; color:#334155; vertical-align: middle; }
    tbody tr:hover { background:#f1f5f9; transition: background 0.2s; }

    /* Componentes de celda */
    .user-info { display: flex; flex-direction: column; }
    .user-name { font-weight: 600; color: #0f172a; }
    .user-sub { font-size: 0.75rem; color: #64748b; }

    .badge { padding:4px 12px; border-radius:9999px; font-size:0.7rem; font-weight:700; text-transform: uppercase; }
    .badge-green { background:#d1fae5; color:#065f46; border: 1px solid #a7f3d0; }
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

    /* Estilos para Paginación */
    .pagination-container { 
        padding: 24px 0; 
        margin: 0 24px;
        display: flex; 
        justify-content: center; 
        border-top: 1px solid #f1f5f9;
    }
    .pagination { display: flex; list-style: none; gap: 8px; padding: 0; align-items: center; }
    .page-item { display: inline-block; }
    .page-item .page-link { 
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 14px; 
        border-radius: 8px; 
        border: 1px solid #e2e8f0; 
        background: white; 
        color: #475569; 
        text-decoration: none; 
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.2s;
    }
    .page-item.active .page-link { 
        background: #3b82f6; 
        color: white; 
        border-color: #3b82f6; 
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.25);
    }
    .page-item:not(.active):not(.disabled) .page-link:hover { 
        background: #f8fafc; 
        color: #0f172a; 
        border-color: #cbd5e1;
    }
    .page-item.disabled .page-link { 
        color: #cbd5e1; 
        background: #f8fafc; 
        cursor: not-allowed; 
        border-color: #f1f5f9;
    }

    /* Modal */
    .modal-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(15, 23, 42, 0.5);
        display: flex; align-items: center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.3s;
        z-index: 50;
    }
    .modal-overlay.active { opacity: 1; pointer-events: auto; }
    .modal-box {
        background: white; border-radius: 12px; width: 100%; max-width: 400px;
        padding: 24px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        transform: translateY(-20px); transition: transform 0.3s;
    }
    .modal-overlay.active .modal-box { transform: translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .modal-header h3 { font-size: 1.25rem; color: #0f172a; font-weight: 700; }
    .close-btn { background: none; border: none; cursor: pointer; color: #64748b; transition: color 0.2s; }
    .close-btn:hover { color: #0f172a; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 0.875rem; font-weight: 600; color: #334155; margin-bottom: 8px; }
    .form-control { width: 100%; padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.95rem; color: #0f172a; outline: none; transition: border-color 0.2s; }
    .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
    .btn-cancel { padding: 10px 16px; background: white; border: 1px solid #cbd5e1; border-radius: 8px; color: #475569; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-cancel:hover { background: #f8fafc; color: #0f172a; border-color: #94a3b8; }
    .btn-save { padding: 10px 16px; background: #3b82f6; border: none; border-radius: 8px; color: white; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .btn-save:hover { background: #2563eb; }
    #toast {
        position: fixed; top: 30px; right: 30px;
        padding: 16px 24px; border-radius: 12px;
        font-size: 0.9rem; font-weight: 600; color: white;
        z-index: 100000; opacity: 0; transform: translateY(-20px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
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
                    Gestiona todas las distribuidoras activas.
                </p>
            </div>

            <div class="panel">
                <div class="box-header">
                    <div>
                        <h2>Listado de Distribuidoras</h2>
                        <p style="font-size: 0.8rem; color: #64748b; font-weight: 400;">Control total de cuentas y créditos.</p>
                    </div>
                    <span class="badge" style="background: #eff6ff; color: #1e40af; border: none;">
                        Total: {{ $distribuidoras->total() }}
                    </span>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Distribuidora</th>
                                <th>Categoría</th>
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
                                <td>
                                    <div class="user-info">
                                        <span class="user-name">{{ $dist->usuario->persona->nombre }} {{ $dist->usuario->persona->apellido }}</span>
                                        <span class="user-sub">{{ $dist->usuario->email }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 500; color: #475569;">
                                        {{ $dist->categoria->categoria ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <i data-lucide="phone" style="width: 14px; color: #64748b;"></i>
                                        {{ $dist->usuario->persona->celular }}
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
                                    <span class="badge {{ $dist->estado == 'activo' ? 'badge-green' : 'badge-red' }}">
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
                                    <button class="btn-action" onclick="abrirModalEditar({{ $dist->id }}, '{{ $dist->estado }}', {{ $dist->categoria_id ?? 1 }}, {{ $dist->linea_credito }})">
                                        <i data-lucide="edit" style="width: 14px;"></i>
                                        Editar
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:60px;">
                                    <div style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                                        <i data-lucide="search-x" style="width: 48px; height: 48px; color: #cbd5e1;"></i>
                                        <p style="color:#94a3b8; font-size: 1rem;">No se encontraron distribuidoras en el sistema.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $distribuidoras->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Editar Estado y Categoría -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Editar Distribuidora</h3>
                <button class="close-btn" onclick="cerrarModal()"><i data-lucide="x"></i></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                <div class="form-group">
                    <label for="estadoSelect">Estado de la Distribuidora</label>
                    <select id="estadoSelect" name="estado" class="form-control">
                        <option value="activo">Activo</option>
                        <option value="moroso">Moroso</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="categoriaSelect">Categoría</label>
                    <select id="categoriaSelect" name="categoria_id" class="form-control">
                        <option value="1">Bronce</option>
                        <option value="2">Plata</option>
                        <option value="3">Oro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="lineaCreditoInput">Línea de Crédito ($)</label>
                    <input type="number" id="lineaCreditoInput" name="linea_credito" class="form-control" step="500" min="0" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn-save">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast"></div>

    <script>
        lucide.createIcons();

        function mostrarToast(mensaje, tipo = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = mensaje;
            toast.style.background = tipo === 'success' ? '#10b981' : '#ef4444';
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
            }, 3000);
        }

        @if(session('success'))
            // Mostrar toast si hay un mensaje de éxito en la sesión
            mostrarToast("{{ session('success') }}", 'success');
        @endif

        function abrirModalEditar(id, estadoActual, categoriaActual, lineaActual) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const estadoSelect = document.getElementById('estadoSelect');
            const categoriaSelect = document.getElementById('categoriaSelect');
            const lineaCreditoInput = document.getElementById('lineaCreditoInput');
            
            form.action = `/gerente/distribuidora/${id}/estado`;
            
            // Asignar el estado actual en el select si coincide
            if (estadoActual === 'activo' || estadoActual === 'moroso') {
                estadoSelect.value = estadoActual;
            } else {
                estadoSelect.value = 'activo';
            }

            // Asignar la categoría actual
            if (categoriaActual && [1, 2, 3].includes(parseInt(categoriaActual))) {
                categoriaSelect.value = categoriaActual;
            } else {
                categoriaSelect.value = 1;
            }
            
            // Asignar la línea de crédito
            lineaCreditoInput.value = lineaActual || 0;
            
            modal.classList.add('active');
        }

        function cerrarModal() {
            document.getElementById('editModal').classList.remove('active');
        }
    </script>
</body>
</html>