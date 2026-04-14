<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
</head>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
    body, html { width:100%; height:100%; background-color:#f4f7f6; }
    .dashboard-container { display:flex; width:100%; height:100vh; }
    .contenido { flex:1; width:100%; padding:40px; overflow-y:auto; }
    h1 { color:#111827; font-size:1.8rem; margin-bottom:20px; }
    .panel { background:white; border-radius:12px; padding:24px; height:90%; box-shadow:0 1px 4px rgba(0,0,0,0.08); overflow-y:auto; }
    .loading { text-align:center; padding:40px; color:#9ca3af; }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-box {
        background: white;
        border-radius: 12px;
        padding: 30px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .modal-header h3 { font-size:1.1rem; font-weight:700; color:#111827; }
    .modal-header button { background:none; border:none; font-size:1.4rem; cursor:pointer; color:#6b7280; }
    .form-group { margin-bottom:16px; display:flex; flex-direction:column; gap:6px; }
    .form-group label { font-size:0.78rem; font-weight:600; color:#6b7280; text-transform:uppercase; }
    .form-group input { padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:0.9rem; outline:none; }
    .form-group input:focus { border-color:#2563eb; }
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
    .modal-footer { display:flex; justify-content:flex-end; gap:10px; margin-top:24px; }
    .btn-cancelar { padding:10px 20px; border:1px solid #e5e7eb; border-radius:8px; background:white; color:#6b7280; font-weight:600; cursor:pointer; }
    .btn-guardar { padding:10px 20px; border:none; border-radius:8px; background:#2563eb; color:white; font-weight:600; cursor:pointer; }
    .btn-guardar:hover { background:#1d4ed8; }

    /* Toast */
    #toast {
        position:fixed; top:30px; right:30px;
        padding:14px 20px; border-radius:10px;
        font-size:0.9rem; font-weight:600; color:white;
        z-index:99999; opacity:0; transform:translateY(-20px);
        transition:all 0.3s ease; pointer-events:none;
    }
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

            <div class="panel" id="panel-contenido">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2 style="font-size:1.2rem; font-weight:700; color:#111827;">Productos</h2>
                    <button onclick="abrirModal('modalCrear')"
                        style="padding:8px 16px; background:#2563eb; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600;">
                        + Nuevo Producto
                    </button>
                </div>

                <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                            <th style="text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem;">#</th>
                            <th style="text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem;">Monto</th>
                            <th style="text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem;">Quincenas</th>
                            <th style="text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem;">Interés Quincenal</th>
                            <th style="text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem;">Seguro</th>
                            <th style="text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem;">Comisión Apertura</th>
                            <th style="text-align:left; padding:12px 16px; color:#6b7280; font-weight:600; text-transform:uppercase; font-size:0.75rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-productos">
                        @forelse($productos as $producto)
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            <td style="padding:12px 16px; color:#374151;">{{ $producto->id }}</td>
                            <td style="padding:12px 16px; color:#374151; font-weight:700;">${{ number_format($producto->monto, 2) }}</td>
                            <td style="padding:12px 16px; color:#374151;">{{ $producto->quincenas }}</td>
                            <td style="padding:12px 16px; color:#374151;">{{ $producto->interes_quincenal }}%</td>
                            <td style="padding:12px 16px; color:#374151;">${{ number_format($producto->seguro, 2) }}</td>
                            <td style="padding:12px 16px; color:#374151;">{{ $producto->porcentaje_comision }}%</td>
                            <td style="padding:12px 16px;">
                                <div style="display:flex; gap:8px;">
                                    <button onclick="editarProducto(
                                        {{ $producto->id }},
                                        {{ $producto->monto }},
                                        {{ $producto->quincenas }},
                                        {{ $producto->interes_quincenal }},
                                        {{ $producto->seguro }},
                                        {{ $producto->porcentaje_comision }}
                                        )" style="padding:6px 12px; background:#2563eb; color:white; border:none; border-radius:6px; cursor:pointer; font-size:0.8rem; font-weight:600;">
                                        Editar
                                    </button>
                                    <button onclick="eliminarProducto({{ $producto->id }})"
                                        style="padding:6px 12px; background:#dc2626; color:white; border:none; border-radius:6px; cursor:pointer; font-size:0.8rem; font-weight:600;">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                No hay productos registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    {{-- MODAL CREAR PRODUCTO --}}
    <div id="modalCrear" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Nuevo Producto</h3>
                <button onclick="cerrarModal('modalCrear')">&times;</button>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Monto ($)</label>
                    <input type="number" step="0.01" id="crear-monto" placeholder="Ej: 5000">
                </div>
                <div class="form-group">
                    <label>Quincenas</label>
                    <input type="number" id="crear-quincenas" placeholder="Ej: 12">
                </div>
                <div class="form-group">
                    <label>Interés Quincenal (%)</label>
                    <input type="number" step="0.01" id="crear-interes" placeholder="Ej: 3.5">
                </div>
                <div class="form-group">
                    <label>Seguro ($)</label>
                    <input type="number" step="0.01" id="crear-seguro" placeholder="Ej: 150">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Comisión Apertura (%)</label>
                    <input type="number" step="0.01" id="crear-comision" placeholder="Ej: 5">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cancelar" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button class="btn-guardar" onclick="crearProducto()">Guardar Producto</button>
            </div>
        </div>
    </div>
    {{-- MODAL EDITAR PRODUCTO --}}
    <div id="modalEditar" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Editar Producto</h3>
                <button onclick="cerrarModal('modalEditar')">&times;</button>
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
                    <label>Interés Quincenal (%)</label>
                    <input type="text" step="0.01" id="editar-interes">
                </div>
                <div class="form-group">
                    <label>Seguro ($)</label>
                    <input type="number" step="0.01" id="editar-seguro">
                </div>
                <div class="form-group" style="grid-column: span 2;">
                    <label>Comisión Apertura (%)</label>
                    <input type="text" step="0.01" id="editar-comision">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cancelar" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button class="btn-guardar" onclick="guardarEdicion()">Guardar Cambios</button>
            </div>
        </div>
    </div>
    <div id="toast" style="
    position: fixed;
    top: 30px;
    right: 30px;
    padding: 14px 20px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    z-index: 99999;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
    pointer-events: none;
    "></div>
</body>
<script>
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
        toast.style.background = tipo === 'success' ? '#16a34a' : '#dc2626';
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
            console.log('Respuesta:', data); // ✅ ver qué dice Laravel
            if (data.errors) {
                console.log('Errores:', data.errors);
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
</html>