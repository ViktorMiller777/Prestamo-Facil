<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Distribuidora - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #305be3;
            --primary-hover: #2548b8;
            --dark: #111827;
            --bg: #f4f7f6;
            --border: #e5e7eb;
            --text-main: #374151;
            --text-muted: #6b7280;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); }

        .barra {
            padding: 10px 30px;
            display: flex;
            align-items: center;
            background: var(--dark);
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .pf-logo { display: flex; align-items: center; gap: 12px; font-weight: 800; font-size: 1.2rem; color: white; }
        .pf-logo-box { background: var(--primary); color: white; padding: 6px 12px; border-radius: 8px; }

        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }

        /* Estilos del Card y Stepper */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .stepper {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        .step-item {
            flex: 1;
            padding: 15px;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            border-right: 1px solid var(--border);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .step-item.active { color: var(--primary); background: white; border-bottom: 3px solid var(--primary); }
        .step-item:last-child { border-right: none; }

        /* Formulario Layout */
        .form-body { padding: 40px; }
        .form-page { display: none; }
        .form-page.active { display: block; animation: fadeIn 0.3s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        .section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 25px;
            display: block;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            margin-bottom: 20px;
        }

        .form-group { display: flex; flex-direction: column; gap: 8px; }
        label { font-size: 0.8rem; font-weight: 600; color: #4b5563; text-transform: uppercase; }
        
        input, select {
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(48, 91, 227, 0.1); }

        /* Footer navegación */
        .form-footer {
            padding: 20px 40px;
            background: #f8fafc;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        input:invalid:focus {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        .btn {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .btn-prev { background: #e5e7eb; color: #374151; }
        .btn-prev:hover { background: #d1d5db; }
        .btn-next { background: var(--primary); color: white; }
        .btn-next:hover { background: var(--primary-hover); }
        .btn-submit { background: #10b981; color: white; }
        .btn-submit:hover { background: #059669; }

    </style>
</head>
<body>

    <div class="barra">
        <div class="pf-logo">
            <div class="pf-logo-box">PF</div>
            Préstamo Fácil
        </div>
        <a href="{{ route('gerente.dashboard') }}" style="color: #9ca3af; text-decoration: none; font-size: 0.95rem;">
            ← Volver al Dashboard
        </a>
    </div>

    <div class="container">
        <div class="card">
            <div class="stepper">
                <div class="step-item active" id="s1">1. Titular</div>
                <div class="step-item" id="s2">2. Cuenta y Crédito</div>
                <div class="step-item" id="s3">3. Familiar</div>
                <div class="step-item" id="s4">4. Asociados</div>
            </div>

            <form action="/api/crear/distribuidora" method="POST" id="multiStepForm">
                @csrf
                <div class="form-body">
                    <div class="form-page active" id="page1">
                        <span class="section-title">Información Personal del Titular</span>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Nombre(s)</label>
                                <input type="text" name="persona[nombre]"required placeholder="Ej: Nombre">
                            </div>
                            <div class="form-group">
                                <label>Apellido(s)</label>
                                <input type="text" name="persona[apellido]"required placeholder="Ej: Apellido">
                            </div>
                            <div class="form-group">
                                <label>Sexo</label>
                                <select name="persona[sexo]"required>
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>CURP</label>
                                <input type="text" name="persona[CURP]"required maxlength="18">
                            </div>
                            <div class="form-group">
                                <label>RFC</label>
                                <input type="text" name="persona[RFC]"required maxlength="13">
                            </div>
                            <div class="form-group">
                                <label>Fecha Nacimiento</label>
                                <input type="date" name="persona[fecha_nacimiento]"required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono Personal</label>
                                <input type="number" name="persona[telefono_personal]"required maxlength="10"> <!--CHECAR QUE SI ES TEXT -->
                            </div>
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="number" name="persona[celular]"required maxlength="10"> <!--CHECAR QUE SI ES TEXT --> 
                            </div>
                            <div class="form-group" style="grid-column: span 3;">
                                <label>Domicilio Completo</label>
                                <input type="text" name="distribuidora[domicilio]" id="domicilio_input" class="form-control" placeholder="Se llenará solo al usar el buscador del mapa" required>
                            </div>

                            <div class="form-group" style="grid-column: span 3; margin-top: 10px;">
                                <label style="font-weight: 600;">📍 Ubicación en el Mapa (Busca o arrastra el pin)</label>
                                <div id="map" style="height: 400px; width: 100%; border-radius: 12px; border: 1px solid #e5e7eb;"></div>
                            </div>

                            <input type="hidden" name="distribuidora[geolocalizacion_lat]" id="lat_input">
                            <input type="hidden" name="distribuidora[geolocalizacion_lng]" id="lng_input">
                        </div>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                        <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
                        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
                    </div>

                    <div class="form-page" id="page2">
                        <span class="section-title">Configuración de la Distribuidora</span>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Email de Acceso</label>
                                <input type="email" name="usuario[email]" required placeholder="tu@ejemplo.com">
                            </div>
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="password" name="usuario[password]" required>
                            </div>
                            <div class="form-group">
                                <label>Línea de Crédito ($)</label>
                                <input type="number" step="0.01" name="distribuidora[linea_credito]" required>
                            </div>
                            <input type="hidden" name="usuario[sucursal_id]" value="1">
                            <input type="hidden" name="usuario[role_id]" value="4">
                            <input type="hidden" name="distribuidora[categoria_id]" value="1">
                            <input type="hidden" name="distribuidora[estado]" value="presolicitud">
                            <input type="hidden" name="distribuidora[puntos]" value="0">
                        </div>
                    </div>
                    <div class="form-page" id="page3">
                        <span class="section-title">Datos del Familiar</span>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Parentesco</label>
                                <select name="familiar[parentesco]" required placeholder="Ej: Esposo">
                                    <option value="Hijo">Hijo</option>
                                    <option value="Hija">Hija</option>
                                    <option value="Esposo">Esposo</option>
                                    <option value="Esposa">Esposa</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nombre Familiar</label>
                                <input type="text" name="familiar[nombre]" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido Familiar</label>
                                <input type="text" name="familiar[apellido]" required>
                            </div>
                            <div class="form-group">
                                <label>Sexo</label>
                                <select name="familiar[sexo]" required>
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>CURP</label>
                                <input type="text" name="familiar[CURP]" required maxlength="18">
                            </div>
                            <div class="form-group">
                                <label>RFC</label>
                                <input type="text" name="familiar[RFC]" required maxlength="13">
                            </div>
                            <div class="form-group">
                                <label>Fecha Nacimiento</label>
                                <input type="date" name="familiar[fecha_nacimiento]" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono Personal</label>
                                <input type="text" name="familiar[telefono_personal]" required> <!--CHECAR QUE SI ES TEXT -->
                            </div>
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" name="familiar[celular]" required> <!--CHECAR QUE SI ES TEXT --> 
                            </div>
                        </div>
                    </div>
                    <div class="form-page" id="page4">
                        <span class="section-title">Datos de la Filial Asociada y Vehiculo</span>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Nombre Filial</label>
                                <input type="text" name="afilial[nombre]" required>
                            </div>
                            <div class="form-group">
                                <label>Antigüedad (Fecha Inicio)</label>
                                <input type="date" name="afilial[antiguedad]" required>
                            </div>
                            <div class="form-group">
                                <label>Línea Filial ($)</label>
                                <input type="number" step="0.01" name="afilial[linea_credito]" required>
                            </div>
                            <div class="form-group">
                                <label>Marca Vehículo</label>
                                <input type="text" name="vehiculo[marca]" required >
                            </div>
                            <div class="form-group">
                                <label>Modelo</label>
                                <input type="text" name="vehiculo[modelo]"required>
                            </div>
                            <div class="form-group">
                                <label>Placas</label>
                                <input type="text" name="vehiculo[numero_placas]" required>
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text" name="vehiculo[color]" required>
                            </div>
                        </div>
                    </div>
                    

                </div>

                <div class="form-footer">
                    <button type="button" class="btn btn-prev" id="btnPrev" onclick="movePage(-1)" style="visibility: hidden;">Anterior</button>
                    <div>
                        <button type="button" class="btn btn-next" id="btnNext" onclick="movePage(1)">Siguiente Paso</button>
                        <button type="submit" class="btn btn-submit" id="btnSave" style="display: none;" onclick="enviarForm()">Finalizar y Guardar</button>
                    </div>
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
    let current = 1;

    // 1. LÓGICA DE NAVEGACIÓN ENTRE PÁGINAS
    function movePage(step) {
        if (step === 1) {
            const currentPage = document.getElementById(`page${current}`);
            const inputs = currentPage.querySelectorAll('input, select');
            let isValid = true;

            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                }
            });

            if (!isValid) return; 
        }

        if (current + step < 1 || current + step > 4) return;

        document.getElementById(`page${current}`).classList.remove('active');
        document.getElementById(`s${current}`).classList.remove('active');

        current += step;

        document.getElementById(`page${current}`).classList.add('active');
        document.getElementById(`s${current}`).classList.add('active');

        // Fix visual para el mapa cuando cambias de página
        if (current === 1 || current === 2) { // Ajusta al número de página donde esté tu mapa
            setTimeout(() => {
                if (window.leafletMap) window.leafletMap.invalidateSize();
            }, 300);
        }

        document.getElementById('btnPrev').style.visibility = current === 1 ? 'hidden' : 'visible';
        
        if (current === 4) {
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnSave').style.display = 'inline-block';
        } else {
            document.getElementById('btnNext').style.display = 'inline-block';
            document.getElementById('btnSave').style.display = 'none';
        }
    }

    // 2. FUNCIÓN DE ENVÍO DE FORMULARIO (CORREGIDA)
    function enviarForm(e) {
        if (e) e.preventDefault(); // Detiene el envío normal para usar el Fetch

        const form = document.getElementById('multiStepForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);

        // Verificación en consola antes de enviar
        console.log('=== Enviando estos datos ===');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}:`, value);
        }

        fetch('/api/crear/distribuidora', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.res) {
                mostrarToast('✅ Distribuidora activada correctamente', 'success');
                setTimeout(() => {
                    window.location.href = '/verificador/notificaciones';
                }, 1400);
            } else {
                mostrarToast('❌ ' + data.mensaje, 'error');
            }
        })
        .catch(err => {
            console.error('Error:', err);
            mostrarToast('❌ Error al conectar con el servidor', 'error');
        });
    }

    function mostrarToast(mensaje, tipo = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = mensaje;
        toast.style.background = tipo === 'success' ? '#16a34a' : '#dc2626';
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
        }, 3000);
    }

    // 3. INICIALIZACIÓN DEL MAPA
    document.addEventListener('DOMContentLoaded', function() {
        const latIni = 25.5438;
        const lngIni = -103.4189;

        // Aseguramos que los inputs tengan valor desde el inicio
        const latInput = document.getElementById('lat_input');
        const lngInput = document.getElementById('lng_input');
        
        if (latInput) latInput.value = latIni;
        if (lngInput) lngInput.value = lngIni;

        // Guardamos el mapa en window para poder refrescarlo en movePage
        window.leafletMap = L.map('map', { attributionControl: false }).setView([latIni, lngIni], 13);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(window.leafletMap);
        
        let marker = L.marker([latIni, lngIni], { draggable: true }).addTo(window.leafletMap);

        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
            placeholder: "Buscar dirección...",
        })
        .on('markgeocode', function(e) {
            const center = e.geocode.center;
            const nombreStr = e.geocode.name;

            window.leafletMap.setView(center, 18);
            marker.setLatLng(center);

            // Actualización de campos de texto y coordenadas reales
            document.getElementById('domicilio_input').value = nombreStr;
            latInput.value = center.lat.toFixed(8);
            lngInput.value = center.lng.toFixed(8);
        })
        .addTo(window.leafletMap);

        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            latInput.value = pos.lat.toFixed(8);
            lngInput.value = pos.lng.toFixed(8);
        });

        setTimeout(() => { window.leafletMap.invalidateSize(); }, 600);
    });
</script>
</html>