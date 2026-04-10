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
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body, html {
        width: 100%;
        height: 100%;
        background-color: #f4f7f6;
    }

    .dashboard-container {
        display: flex;
        width: 100%;
        height: 100vh;
    }

    .barra {
        width: 280px;
        background: #111827;
        color: white;
        display: flex;
        flex-direction: column;
        padding: 20px;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }

    .logo {
        font-weight: 800;
        font-size: 1.4rem;
        color: white;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #374151;
        text-align: center;
    }

    .btn-nueva {
        background-color: #2563eb;
        color: white;
        border: none;
        padding: 12px 15px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: background 0.3s ease;
        margin-bottom: 25px;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-nueva:hover { background-color: #1d4ed8; }

    .menu-nav { list-style: none; }
    .menu-nav li { margin-bottom: 10px; }
    .menu-nav a {
        color: #9ca3af;
        text-decoration: none;
        display: block;
        padding: 10px;
        border-radius: 6px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .menu-nav a:hover, .menu-nav a.active {
        background: #1f2937;
        color: white;
    }

    .contenido {
        flex: 1;
        width: 100%;
        padding: 40px;
        overflow-y: auto;
    }

    h1 {
        color: #111827;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }

    .role {
        font-size: 15px;
        color: #5d6169;
    }

    /* Panel de contenido dinámico */
    .panel {
        background: white;
        border-radius: 12px;
        padding: 24px;
        height: 90%;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        overflow-y: auto;
    }

    /* Tabla */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    thead tr {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    th {
        text-align: left;
        padding: 12px 16px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    td {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }

    tbody tr:hover { background: #f9fafb; }

    .badge {
        padding: 3px 10px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-green { background: #d1fae5; color: #065f46; }
    .badge-red   { background: #fee2e2; color: #991b1b; }

    .loading {
        text-align: center;
        padding: 40px;
        color: #9ca3af;
    }
    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 999;
        justify-content: center;
        align-items: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: white;
        border-radius: 12px;
        padding: 32px;
        width: 480px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-group input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #111827;
        outline: none;
        transition: border 0.2s;
    }

    .form-group input:focus {
        border-color: #2563eb;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-cancelar {
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-cancelar:hover { background: #f9fafb; }

    .btn-guardar {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-guardar:hover { background: #1d4ed8; }
    .toast {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: white;
        z-index: 9999;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }
    .toast.success { background: #16a34a; }
    .toast.error   { background: #dc2626; }
    .toast.warning { background: #d97706; }

</style>
<body>
    <div class="dashboard-container">
        <aside class="barra">
            <div class="logo">
                Prestamo Fácil
                <p class="role">{{ auth()->user()->role->role }}</p>
            </div>

            @if(auth()->check() && auth()->user()->role_id == 1)
                <a href="{{ route('distribuidoras.create') }}" class="btn-nueva">
                    <span>+</span> Nueva Distribuidora
                </a>
            @endif

            <nav>
                <ul class="menu-nav">
                    <li><a href="#" onclick="cargarVista('productos', this)">Productos</a></li>
                    <li><a href="#" onclick="cargarVista('distribuidoras', this)">Distribuidoras</a></li>
                    <li><a href="#" onclick="cargarVista('distribuidoras', this)">Relaciones</a></li>
                    <li><a href="#" onclick="cargarVista('distribuidoras', this)">Vales</a></li>
                    <li><a href="#" onclick="cargarVista('distribuidoras', this)">Configuracion</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display:none;">
                            @csrf
                        </form>
                        <a href="#" onclick="document.getElementById('logout-form').submit();"
                           style="color: #ef4444; font-weight: bold;">
                            Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="contenido">
            <h1>
                Bienvenido al Panel
                <span style="text-transform: capitalize;">
                    {{ auth()->user()->persona->nombre }}!!
                </span>
            </h1>

            <div class="panel" id="panel-contenido">
                <p class="loading">Selecciona una opción del menú.</p>
            </div>
        </main>
    </div>
<script>
    // Vistas en HTML puro que se inyectan en el panel
    const vistas = {
        distribuidoras: `<p style="color:#9ca3af">Cargando distribuidoras...</p>`,
        prestamos: `<p style="color:#9ca3af">Cargando prestamos...</p>`,
        configuracion: `<p style="color:#9ca3af">Cargando configruaciones...</p>`,
        productos: `<p class="loading">Cargando productos...</p>`,
    };

    function cargarVista(nombre, el) {
        // Marcar activo en el menú
        document.querySelectorAll('.menu-nav a').forEach(a => a.classList.remove('active'));
        el.classList.add('active');

        const panel = document.getElementById('panel-contenido');
        panel.innerHTML = vistas[nombre];

        if (nombre === 'productos') {
            cargarProductos();
        }
        if(nombre === 'distribuidoras'){
            cargarDistribuidoras();
        }
    }
    function cargarProductos() {
        fetch('/api/lista/productos', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            const productos = data.productos;
            const panel = document.getElementById('panel-contenido');

            if (!productos || productos.length === 0) {
                panel.innerHTML = `<p class="loading">No hay productos registrados.</p>`;
                return;
            }

            panel.innerHTML = `
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Monto</th>
                            <th>% Comisión</th>
                            <th>Seguro</th>
                            <th>Quincenas</th>
                            <th>Interés Quincenal</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${productos.map(p => `
                            <tr>
                                <td>${p.id}</td>
                                <td>$${parseFloat(p.monto).toFixed(2)}</td>
                                <td>${p.porcentaje_comision}%</td>
                                <td>$${parseFloat(p.seguro).toFixed(2)}</td>
                                <td>${p.quincenas}</td>
                                <td>${p.interes_quincenal}%</td>
                                <td>
                                    <a href="#" onclick="abrirModal(${p.id}, ${p.monto}, ${p.porcentaje_comision}, ${p.seguro}, ${p.quincenas}, ${p.interes_quincenal})"
                                    style="color:#2563eb; font-weight:600; text-decoration:none;">
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
                <div style="margin-top: 16px;">
                    <button onclick="abrirModalCrear()" class="btn-nueva" style="width: auto; margin: 0;">
                        <span>+</span> Crear Producto
                    </button>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('panel-contenido').innerHTML =
                `<p style="color:#ef4444; text-align:center; padding:40px;">Error al cargar productos.</p>`;
        });
    }

    function cargarDistribuidoras(){
        
    }

    function abrirModal(id, monto, comision, seguro, quincenas, interes) {
        document.getElementById('edit-id').value        = id;
        document.getElementById('edit-monto').value     = monto;
        document.getElementById('edit-comision').value  = comision;
        document.getElementById('edit-seguro').value    = seguro;
        document.getElementById('edit-quincenas').value = quincenas;
        document.getElementById('edit-interes').value   = interes;

        document.getElementById('modal-overlay').classList.add('active');
    }

    function cerrarModal() {
        document.getElementById('modal-overlay').classList.remove('active');
    }

    function guardarProducto() {
        const id = document.getElementById('edit-id').value;

        const body = {
            monto:               document.getElementById('edit-monto').value,
            porcentaje_comision: document.getElementById('edit-comision').value,
            seguro:              document.getElementById('edit-seguro').value,
            quincenas:           document.getElementById('edit-quincenas').value,
            interes_quincenal:   document.getElementById('edit-interes').value,
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
            cerrarModal();
            cargarProductos();
            mostrarToast('¡Producto actualizado correctamente!', 'success');
        })
        .catch(() => {
            mostrarToast('Error al guardar los cambios.', 'error'); 
        });
    }

    // Cerrar modal clickeando fuera
    document.addEventListener('click', function(e) {
        if (e.target.id === 'modal-overlay') cerrarModal();
    });

    function abrirModalCrear() {
        // Limpiar campos
        document.getElementById('crear-monto').value     = '';
        document.getElementById('crear-comision').value  = '';
        document.getElementById('crear-seguro').value    = '';
        document.getElementById('crear-quincenas').value = '';
        document.getElementById('crear-interes').value   = '';

        document.getElementById('modal-crear-overlay').classList.add('active');
    }

    function cerrarModalCrear() {
        document.getElementById('modal-crear-overlay').classList.remove('active');
    }

    function crearProducto() {
        const body = {
            monto:               parseFloat(document.getElementById('crear-monto').value),
            porcentaje_comision: parseFloat(document.getElementById('crear-comision').value),
            seguro:              parseFloat(document.getElementById('crear-seguro').value),
            quincenas:           parseInt(document.getElementById('crear-quincenas').value),
            interes_quincenal:   parseFloat(document.getElementById('crear-interes').value),
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
            cerrarModalCrear();
            cargarProductos();
            mostrarToast('Producto creado exitosamente!', 'success');
        })
    
        
        .catch(() => {
            mostrarToast('Error al guardar los cambios.', 'error');
        });
    }

    // Cerrar clickeando fuera del modal crear
    document.addEventListener('click', function(e) {
        if (e.target.id === 'modal-crear-overlay') cerrarModalCrear();
    });

    function mostrarToast(mensaje, tipo = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = mensaje;
        toast.className = `toast ${tipo} show`;

        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }

</script>
<!-- Codigo para los toast -->
<div class="toast" id="toast"></div>
<!-- Codigo modal para editar un producto  -->
<div class="modal-overlay" id="modal-overlay">
    <div class="modal">
        <h2>Editar Producto</h2>

        <input type="hidden" id="edit-id">

        <div class="form-group">
            <label>Monto</label>
            <input type="number" id="edit-monto" step="0.01">
        </div>
        <div class="form-group">
            <label>% Comisión</label>
            <input type="number" id="edit-comision" step="0.01">
        </div>
        <div class="form-group">
            <label>Seguro</label>
            <input type="number" id="edit-seguro" step="0.01">
        </div>
        <div class="form-group">
            <label>Quincenas</label>
            <input type="number" id="edit-quincenas">
        </div>
        <div class="form-group">
            <label>Interés Quincenal</label>
            <input type="number" id="edit-interes" step="0.01">
        </div>

        <div class="modal-actions">
            <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
            <button class="btn-guardar" onclick="guardarProducto()">Guardar cambios</button>
        </div>
    </div>
</div>
<!-- Codigo modal para crear un producto -->
<div class="modal-overlay" id="modal-crear-overlay">
    <div class="modal">
        <h2>Crear Producto</h2>

        <div class="form-group">
            <label>Monto</label>
            <input type="number" id="crear-monto" step="0.01" placeholder="0.00">
        </div>
        <div class="form-group">
            <label>% Comisión</label>
            <input type="number" id="crear-comision" step="0.01" placeholder="0.00">
        </div>
        <div class="form-group">
            <label>Seguro</label>
            <input type="number" id="crear-seguro" step="0.01" placeholder="0.00">
        </div>
        <div class="form-group">
            <label>Quincenas</label>
            <input type="number" id="crear-quincenas" placeholder="0">
        </div>
        <div class="form-group">
            <label>Interés Quincenal</label>
            <input type="number" id="crear-interes" step="0.01" placeholder="0.00">
        </div>

        <div class="modal-actions">
            <button class="btn-cancelar" onclick="cerrarModalCrear()">Cancelar</button>
            <button class="btn-guardar" onclick="crearProducto()">Crear Producto</button>
        </div>
    </div>
</div>
</body>
</html>