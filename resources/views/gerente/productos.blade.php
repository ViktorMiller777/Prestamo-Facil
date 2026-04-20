<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerente - Productos</title>
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

    /* Panel Principal */
    .panel { 
        background:white; 
        border-radius:16px; 
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); 
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .box-header { 
        display:flex; 
        justify-content:space-between; 
        align-items:center; 
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
    }
    .box-header h2 { font-size:1.1rem; font-weight:700; color:#334155; }

    /* Botones */
    .btn-nuevo {
        padding:10px 18px; 
        background:#2563eb; 
        color:white; 
        border:none; 
        border-radius:10px; 
        cursor:pointer; 
        font-weight:600;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-nuevo:hover { background: #1d4ed8; transform: translateY(-1px); }

    /* Tabla */
    .table-container { overflow-x: auto; }
    table { width:100%; border-collapse:collapse; font-size:0.875rem; }
    thead tr { background:#f8fafc; }
    th { text-align:left; padding:16px; color:#475569; font-weight:600; text-transform:uppercase; font-size:0.7rem; letter-spacing: 0.05em; }
    td { padding:16px; border-bottom:1px solid #f1f5f9; color:#334155; }
    tbody tr:hover { background:#f8fafc; }

    /* Badges y montos */
    .monto-resaltado { font-weight: 700; color: #0f172a; }
    .badge-info { background: #f1f5f9; color: #475569; padding: 4px 8px; border-radius: 6px; font-weight: 600; font-size: 0.75rem; }

    /* Modales */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-box {
        background: white;
        border-radius: 20px;
        padding: 32px;
        width: 100%;
        max-width: 550px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .modal-header h3 { font-size: 1.25rem; font-weight: 700; color: #0f172a; }
    
    .form-group { margin-bottom: 20px; display: flex; flex-direction: column; gap: 8px; }
    .form-group label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.025em; }
    .form-group input { 
        padding: 12px; 
        border: 1px solid #e2e8f0; 
        border-radius: 10px; 
        font-size: 0.95rem; 
        transition: all 0.2s;
    }
    .form-group input:focus { border-color: #3b82f6; outline: none; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
    
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; }

    /* Toasts */
    #toast {
        position: fixed; top: 30px; right: 30px;
        padding: 16px 24px; border-radius: 12px;
        font-size: 0.9rem; font-weight: 600; color: white;
        z-index: 100000; opacity: 0; transform: translateY(-20px);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

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
</style>
<body>
    <div class="dashboard-container">
        <x-aside-bar/>
        <main class="contenido">
            <div class="header-section">
                <h1>Gestión de Productos</h1>
                <p class="welcome-text">Configura los montos, intereses y plazos disponibles para los vales.</p>
            </div>

            <div class="panel">
                <div class="box-header">
                    <div>
                        <h2>Catálogo de Préstamos</h2>
                        <p style="font-size: 0.8rem; color: #64748b;">{{ $productos->total() }} productos configurados</p>
                    </div>
                    <button class="btn-nuevo" onclick="abrirModal('modalCrear')">
                        <i data-lucide="plus-circle" style="width: 18px;"></i>
                        Nuevo Producto
                    </button>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Monto Principal</th>
                                <th>Plazo</th>
                                <th>Interés</th>
                                <th>Seguro ($)</th> <th>Comisión (%)</th> <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos as $producto)
                            <tr>
                                <td style="color: #94a3b8; font-weight: 500;">#{{ $producto->id }}</td>
                                <td class="monto-resaltado">${{ number_format($producto->monto, 2) }}</td>
                                <td>
                                    <span class="badge-info">{{ $producto->quincenas }} Quincenas</span>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #059669;">
                                        {{ $producto->interes_quincenal }}% <small>qnl.</small>
                                    </div>
                                </td>
                                
                                <td style="font-weight: 500; color: #64748b;">
                                    ${{ number_format($producto->seguro, 2) }}
                                </td>

                                <td>
                                    <div style="display: flex; align-items: center; gap: 4px; color: #0f172a; font-weight: 600;">
                                        <i data-lucide="percent" style="width: 12px; color: #3b82f6;"></i>
                                        {{ $producto->porcentaje_comision }}%
                                    </div>
                                </td>

                                <td>
                                    <div style="display:flex; gap:10px;">
                                        <button onclick="editarProducto({{ $producto->id }}, {{ $producto->monto }}, {{ $producto->quincenas }}, {{ $producto->interes_quincenal }}, {{ $producto->seguro }}, {{ $producto->porcentaje_comision }})" 
                                                style="color: #2563eb; background: #eff6ff; border: none; padding: 8px; border-radius: 8px; cursor: pointer;"
                                                title="Editar producto">
                                            <i data-lucide="pencil" style="width: 16px;"></i>
                                        </button>
                                        <button onclick="eliminarProducto({{ $producto->id }})" 
                                                style="color: #dc2626; background: #fef2f2; border: none; padding: 8px; border-radius: 8px; cursor: pointer;"
                                                title="Eliminar producto">
                                            <i data-lucide="trash-2" style="width: 16px;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:80px; color:#94a3b8;">
                                    No hay productos configurados actualmente.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-container">
                    {{ $productos->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL CREAR --}}
    <div id="modalCrear" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Crear Nuevo Producto</h3>
                <button onclick="cerrarModal('modalCrear')" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Monto del Préstamo ($)</label>
                    <input type="number" step="0.01" id="crear-monto" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label>Plazo (Quincenas)</label>
                    <input type="number" id="crear-quincenas" placeholder="Ej: 12">
                </div>
                <div class="form-group">
                    <label>Interés (%)</label>
                    <input type="number" step="0.01" id="crear-interes" placeholder="3.5">
                </div>
                <div class="form-group">
                    <label>Seguro ($)</label>
                    <input type="number" step="0.01" id="crear-seguro" placeholder="150.00">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Comisión de Apertura (%)</label>
                    <input type="number" step="0.01" id="crear-comision" placeholder="5.0">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" style="padding: 12px 24px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; cursor: pointer;" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button class="btn-nuevo" onclick="crearProducto()">Guardar Producto</button>
            </div>
        </div>
    </div>

    {{-- MODAL EDITAR --}}
    <div id="modalEditar" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Ajustar Parámetros</h3>
                <button onclick="cerrarModal('modalEditar')" style="background:none; border:none; font-size:1.5rem; cursor:pointer;">&times;</button>
            </div>
            <input type="hidden" id="editar-id">
            <div class="grid-2">
                <div class="form-group">
                    <label>Monto ($)</label>
                    <input type="number" step="0.01" id="editar-monto">
                </div>
                <div class="form-group">
                    <label>Quincenas</label>
                    <input type="number" id="editar-quincenas">
                </div>
                <div class="form-group">
                    <label>Interés (%)</label>
                    <input type="text" id="editar-interes">
                </div>
                <div class="form-group">
                    <label>Seguro ($)</label>
                    <input type="number" step="0.01" id="editar-seguro">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Comisión (%)</label>
                    <input type="text" id="editar-comision">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancelar" style="padding: 12px 24px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; cursor: pointer;" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button class="btn-nuevo" onclick="guardarEdicion()">Actualizar Datos</button>
            </div>
        </div>
    </div>

    <div id="toast"></div>

    <script>

        lucide.createIcons();

        function abrirModal(id) {
            document.getElementById(id).style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function cerrarModal(id) {
            document.getElementById(id).style.display = 'none';
            document.body.style.overflow = 'auto';
        }

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

        function crearProducto() {
            const body = {
                monto:               parseFloat(document.getElementById('crear-monto').value),
                quincenas:           parseInt(document.getElementById('crear-quincenas').value),
                interes_quincenal:   document.getElementById('crear-interes').value,
                seguro:              parseFloat(document.getElementById('crear-seguro').value),
                porcentaje_comision: document.getElementById('crear-comision').value,
            };

            fetch('/api/crear/producto', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(body)
            })
            .then(res => res.json())
            .then(data => {
                if (data.errors) {
                    mostrarToast('❌ Error de validación', 'error');
                    return;
                }
                cerrarModal('modalCrear');
                mostrarToast('✅ Producto creado exitosamente', 'success');
                setTimeout(() => location.reload(), 1500);
            })
        }
        
        function editarProducto(id, monto, quincenas, interes, seguro, comision) {
            document.getElementById('editar-id').value        = id;
            document.getElementById('editar-monto').value     = monto;
            document.getElementById('editar-quincenas').value = quincenas;
            document.getElementById('editar-interes').value   = interes;
            document.getElementById('editar-seguro').value    = seguro;
            document.getElementById('editar-comision').value  = comision;
            abrirModal('modalEditar');
        }

        function guardarEdicion() {
            const id = document.getElementById('editar-id').value;
            const body = {
                monto:               parseFloat(document.getElementById('editar-monto').value),
                quincenas:           parseInt(document.getElementById('editar-quincenas').value),
                interes_quincenal:   parseFloat(document.getElementById('editar-interes').value),
                seguro:              parseFloat(document.getElementById('editar-seguro').value),
                porcentaje_comision: parseFloat(document.getElementById('editar-comision').value),
            };

            fetch(`/api/editar/producto/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(body)
            })
            .then(res => res.json())
            .then(data => {
                cerrarModal('modalEditar');
                mostrarToast('✅ Producto actualizado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            })
            .catch(() => mostrarToast('❌ Error al actualizar', 'error'));
        }

        function eliminarProducto(id) {
            if (!confirm('¿Estás seguro de eliminar este producto?')) return;
            fetch(`/api/eliminar/producto/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                mostrarToast('✅ Producto eliminado correctamente', 'success');
                setTimeout(() => location.reload(), 1500);
            })
            .catch(() => mostrarToast('❌ Error al eliminar', 'error'));
        }
    </script>
</body>
</html>