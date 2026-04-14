<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
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

    .box-products {
        display: flex;
        flex-wrap: wrap; /* Permite que las tarjetas bajen a la siguiente línea */
        gap: 15px;       /* Espacio entre tarjetas */
        width: 100%;
    }

    /* Ajuste para que sean exactamente 5 por fila */
    .product-card-flex {
        /* Calculamos el 20% menos el espacio del gap para que quepan 5 */
        flex: 0 0 calc(20% - 12px); 
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: transform 0.2s;
        cursor: pointer;
        box-shadow: 5px 5px 10px #d9d9d9, -5px -5px 10px #ffffff;
    }

    .product-amount { font-size: 1.5rem; font-weight: 800; color: #111827; margin-bottom: 5px; }
    .product-badge { background: #eff6ff; color: #1e40af; font-size: 0.75rem; font-weight: 700; padding: 4px 8px; border-radius: 6px; margin-bottom: 15px; }
    .product-details { width: 100%; border-top: 1px solid #f3f4f6; padding-top: 10px; display: grid; grid-template-columns: 1fr 1fr; gap: 5px; font-size: 0.7rem; color: #6b7280; }
    .detail-item b { color: #374151; display: block; font-size: 0.85rem; }
    /* --- ESTILOS PARA EL MODAL --- */
    .modal {
        display: none; /* Esto lo mantiene oculto hasta que llames a abrirModal() */
        position: fixed; /* Lo fija a la pantalla */
        z-index: 9999; /* Lo pone por encima de TODO */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5); /* Fondo oscuro semitransparente */
        backdrop-filter: blur(5px); /* Efecto borroso de fondo profesional */
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        width: 90%;
        max-width: 800px; /* El tamaño que definiste en el HTML */
        max-height: 90vh; /* Evita que el modal sea más alto que la pantalla */
        overflow-y: auto; /* Permite scroll dentro del modal si es muy largo */
    }

    /* Estilos extra para los grupos de formulario */
    .form-group {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-size: 1rem;
        font-weight: 600;
        color: #4b5563;
    }

    .form-group input, .form-group select {
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
    }

    .btn-submit {
        cursor: pointer;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: bold;
        background: #1e40af;
        color: white;
        transition: opacity 0.2s;
    }

    .btn-submit:hover {
        opacity: 0.9;
    }
</style>
<body>
    <x-header-bar />
    <div class="box-2">
        <div class="box-products">
            @foreach ($productos as $producto)
                <div class="product-card-flex" onclick="abrirModalProducto('modalPrevale',{{ $producto->id }})">
                    <div class="product-amount">${{ number_format($producto->monto, 2) }}</div>
                    <div class="product-badge">{{ $producto->quincenas }} Quincenas</div>
                    
                    <div class="product-details">
                        <div class="detail-item">
                            <b>{{ $producto->interes_quincenal }}%</b> Interés
                        </div>
                        <div class="detail-item">
                            <b>${{ $producto->seguro }}</b> Seguro
                        </div>
                        <div class="detail-item" style="grid-column: span 2; margin-top:5px">
                            <b>{{ $producto->porcentaje_comision }}%</b> Comisión Apertura
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
<div id="modalPrevale" class="modal">
    <div class="modal-content" style="max-width: 800px;"> <h2 style="margin-bottom: 20px;">Registro de Vale</h2>
        
        <form id="formPrevale" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="producto_id" id="modal_producto_id">
            <input type="hidden" name="distribuidor_id" value="1"> <input type="hidden" name="estado" value="prevale">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Nombre(s)</label>
                    <input type="text" name="nombre" required placeholder="Juan Carlos">
                </div>
                <div class="form-group">
                    <label>Apellido(s)</label>
                    <input type="text" name="apellido" required placeholder="Pérez Ramos">
                </div>
                <div class="form-group">
                    <label>Sexo</label>
                    <select name="sexo" class="w-full border p-2 rounded-lg">
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" required>
                </div>

                <div class="form-group">
                    <label>CURP</label>
                    <input type="text" name="CURP" required maxlength="18" style="text-transform: uppercase">
                </div>
                <div class="form-group">
                    <label>RFC</label>
                    <input type="text" name="RFC" required maxlength="13" style="text-transform: uppercase">
                </div>

                <div class="form-group">
                    <label>Teléfono Personal</label>
                    <input type="tel" name="telefono_personal" placeholder="871...">
                </div>
                <div class="form-group">
                    <label>Celular</label>
                    <input type="tel" name="celular" required placeholder="871...">
                </div>

                <div class="form-group">
                    <label>Comprobante Domicilio (PDF)</label>
                    <input type="file" name="comprobante_domicilio" accept="application/pdf">
                </div>
                <div class="form-group">
                    <label>INE (PDF)</label>
                    <input type="file" name="INE" accept="application/pdf">
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="cerrarModal('modalPrevale')" style="flex:1; background:#9ca3af; color:white;" class="btn-submit">Cancelar</button>
                <button type="button" onclick="enviarVale()" style="flex:2;" class="btn-submit">Emitir Vale</button>
            </div>
        </form>
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
z-index: 9999;
opacity: 0;
transform: translateY(-20px);
transition: all 0.3s ease;
pointer-events: none;">
</div>
</body>
<script>
    function abrirModalProducto(id, producto_id) {
        document.getElementById('modal_producto_id').value = producto_id;
        document.getElementById(id).style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function cerrarModal(id) {
        document.getElementById(id).style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    async function enviarVale() {
        const form = document.getElementById('formPrevale');
        const formData = new FormData(form);

        formData.append('folio', 'VALE-' + Date.now());

        try {
            const response = await fetch('/api/crear/vale', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const res = await response.json();

            if (response.ok) {
                cerrarModal('modalPrevale');
                form.reset();
                mostrarToast('✅ Vale creado exitosamente', 'success');
            } else {
                mostrarToast('❌ ' + (res.message || 'Error al crear el vale'), 'error');
            }
        } catch (error) {
            console.error('Error en la conexión:', error);
            mostrarToast('❌ Error de conexión', 'error');
        }
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
</script>
</html>
