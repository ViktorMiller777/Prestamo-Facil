<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Distribuidora - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-soft: #eff6ff;
            --primary-hover: #1d4ed8;
            --dark: #0f172a;
            --bg: #f8fafc;
            --border: #e2e8f0;
            --text-main: #334155;
            --text-muted: #64748b;
            --success: #10b981;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); line-height: 1.5; }

        /* Barra superior más elegante */
        .barra {
            padding: 0.75rem 2rem;
            display: flex;
            align-items: center;
            background: var(--dark);
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .pf-logo { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 1.1rem; color: white; }
        .pf-logo-box { background: var(--primary); color: white; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 0.9rem; }

        .container { max-width: 1000px; margin: 20px auto; padding: 0 20px; }

        /* Card con diseño moderno */
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.04), 0 8px 10px -6px rgba(0,0,0,0.04);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        /* Stepper estilizado tipo progreso */
        .stepper {
            display: flex;
            background: #fff;
            border-bottom: 1px solid var(--border);
            padding: 10px;
        }

        .step-item {
            flex: 1;
            padding: 12px;
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: relative;
            transition: all 0.3s;
            border-radius: 10px;
        }

        .step-item.active { color: var(--primary); background: var(--primary-soft); }

        /* Formulario Compacto */
        .form-body { padding: 25px 35px; }
        .form-page { display: none; }
        .form-page.active { display: block; animation: slideIn 0.4s ease-out; }

        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .section-header { margin-bottom: 20px; border-left: 4px solid var(--primary); padding-left: 15px; }
        .section-title { font-size: 1.1rem; font-weight: 800; color: var(--dark); display: block; }
        .section-subtitle { font-size: 0.8rem; color: var(--text-muted); }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px 20px;
            margin-bottom: 15px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .grid-3 {
                grid-template-columns: 1fr;
            }
        }

        .form-group { display: flex; flex-direction: column; gap: 6px; }
        label { font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
        
        input, select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 0.9rem;
            color: var(--text-main);
            background: #fcfdfe;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        input:focus, select:focus { 
            outline: none; 
            border-color: var(--primary); 
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); 
        }

        /* Mapa más integrado */
        #map { 
            height: 300px; /* Reducido para laptops */
            width: 100%; 
            border-radius: 15px; 
            border: 1px solid var(--border);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Botonera */
        .form-footer {
            padding: 20px 35px;
            background: #f8fafc;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-prev { background: white; color: var(--text-main); border: 1px solid var(--border); }
        .btn-prev:hover { background: #f1f5f9; }
        .btn-next { background: var(--dark); color: white; }
        .btn-next:hover { background: #000; transform: translateY(-1px); }
        .btn-submit { background: var(--success); color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Estilos para errores */
        .error-msg {
            color: #ef4444;
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 4px;
            display: block;
        }
        input.is-invalid, select.is-invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2;
        }

        /* Toast moderno */
        #toast {
            position: fixed; top: 20px; right: 20px;
            padding: 12px 24px; border-radius: 12px;
            font-size: 0.85rem; font-weight: 700; color: white;
            z-index: 10000; opacity: 0; transform: translateX(20px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <div class="barra">
        <div class="pf-logo">
            <div class="pf-logo-box">PF</div>
            Préstamo Fácil
        </div>
        <a href="{{ route('gerente.dashboard') }}" style="color: #94a3b8; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 5px;">
            <i data-lucide="layout-dashboard" style="width: 16px;"></i> Dashboard
        </a>
    </div>

    <div class="container">
        <div class="card">
            <div class="stepper">
                <div class="step-item active" id="s1">1. Titular</div>
                <div class="step-item" id="s2">2. Cuenta</div>
                <div class="step-item" id="s3">3. Familiar</div>
                <div class="step-item" id="s4">4. Filial</div>
            </div>

            <form action="/api/crear/distribuidora" method="POST" id="multiStepForm" enctype="multipart/form-data">
                @csrf
                <div class="form-body">
                    <div class="form-page active" id="page1">
                        <div class="section-header">
                            <span class="section-title">Información del Titular</span>
                            <p class="section-subtitle">Datos oficiales de identificación y contacto.</p>
                        </div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Nombre(s)</label>
                                <input type="text" name="persona[nombre]" required placeholder="Ej. Juan">
                            </div>
                            <div class="form-group">
                                <label>Apellido(s)</label>
                                <input type="text" name="persona[apellido]" required placeholder="Ej. Pérez">
                            </div>
                            <div class="form-group">
                                <label>Sexo</label>
                                <select name="persona[sexo]" required>
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>CURP</label>
                                <input type="text" name="persona[CURP]" required maxlength="18" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label>RFC</label>
                                <input type="text" name="persona[RFC]" required maxlength="13" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group">
                                <label>Fecha Nacimiento</label>
                                <input type="date" name="persona[fecha_nacimiento]" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono Fijo</label>
                                <input type="number" name="persona[telefono_personal]" required>
                            </div>
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="number" name="persona[celular]" required>
                            </div>
                            <div class="form-group" style="grid-column: span 3;">
                                <label>Domicilio Confirmado</label>
                                <input type="text" name="distribuidora[domicilio]" id="domicilio_input" readonly style="background: #f1f5f9; cursor: not-allowed;" placeholder="Se llenará al buscar en el mapa ↓">
                            </div>

                            <div class="form-group" style="grid-column: span 3; margin-top: 5px;">
                                <div id="map"></div>
                            </div>

                            <input type="hidden" name="distribuidora[geolocalizacion_lat]" id="lat_input">
                            <input type="hidden" name="distribuidora[geolocalizacion_lng]" id="lng_input">
                        </div>
                    </div>

                    <div class="form-page" id="page2">
                        <div class="section-header">
                            <span class="section-title">Configuración de Cuenta</span>
                            <p class="section-subtitle">Credenciales de acceso y límites financieros.</p>
                        </div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Email Institucional</label>
                                <input type="email" name="usuario[email]" required placeholder="correo@ejemplo.com">
                            </div>
                            <div class="form-group">
                                <label>Contraseña de Acceso</label>
                                <input type="password" name="usuario[password]" required placeholder="••••••••">
                            </div>
                            <div class="form-group">
                                <label>Línea de Crédito Autorizada</label>
                                <input type="number" step="0.01" name="distribuidora[linea_credito]" required placeholder="0.00">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label>Comprobante Domicilio (PDF/Imagen)</label>
                                <input type="file" name="distribuidora[comprobante_domicilio]" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <div class="form-group" style="grid-column: span 1;">
                                <label>Identificación INE (PDF/Imagen)</label>
                                <input type="file" name="distribuidora[ine]" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                            <input type="hidden" name="usuario[sucursal_id]" value="1">
                            <input type="hidden" name="usuario[role_id]" value="4">
                            <input type="hidden" name="distribuidora[categoria_id]" value="1">
                            <input type="hidden" name="distribuidora[estado]" value="presolicitud">
                            <input type="hidden" name="distribuidora[puntos]" value="0">
                        </div>
                    </div>

                    <div class="form-page" id="page3">
                        <div class="section-header">
                            <span class="section-title">Referencia Familiar</span>
                            <p class="section-subtitle">Contacto de emergencia o referencia directa.</p>
                        </div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Parentesco</label>
                                <select name="familiar[parentesco]" required>
                                    <option value="Hijo">Hijo/a</option>
                                    <option value="Esposo">Esposo/a</option>
                                    <option value="Padre">Padre/Madre</option>
                                    <option value="Hermano">Hermano/a</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nombre del Familiar</label>
                                <input type="text" name="familiar[nombre]" required>
                            </div>
                            <div class="form-group">
                                <label>Apellido del Familiar</label>
                                <input type="text" name="familiar[apellido]" required>
                            </div>
                            <div class="form-group">
                                <label>CURP Familiar</label>
                                <input type="text" name="familiar[CURP]" required maxlength="18">
                            </div>
                            <div class="form-group">
                                <label>RFC Familiar</label>
                                <input type="text" name="familiar[RFC]" required maxlength="13">
                            </div>
                             <div class="form-group">
                                <label>Teléfono Personal</label>
                                <input type="number" name="familiar[telefono_personal]" required>
                            </div>
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="number" name="familiar[celular]" required>
                            </div>
                            <div class="form-group">
                                <label>Sexo Familiar</label>
                                <select name="familiar[sexo]" required>
                                    <option value="F">Femenino</option>
                                    <option value="M">Masculino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Fecha Nac. Familiar</label>
                                <input type="date" name="familiar[fecha_nacimiento]" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-page" id="page4">
                        <div class="section-header">
                            <span class="section-title">Filial y Activos</span>
                            <p class="section-subtitle">Detalles de la sucursal asociada y transportación.</p>
                        </div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Nombre Filial</label>
                                <input type="text" name="afilial[nombre]" required>
                            </div>
                            <div class="form-group">
                                <label>Fecha de Inicio</label>
                                <input type="date" name="afilial[antiguedad]" required>
                            </div>
                            <div class="form-group">
                                <label>Línea Filial ($)</label>
                                <input type="number" step="0.01" name="afilial[linea_credito]" required>
                            </div>
                            <div class="form-group">
                                <label>Marca Vehículo</label>
                                <input type="text" name="vehiculo[marca]" required placeholder="Ej. Nissan">
                            </div>
                            <div class="form-group">
                                <label>Modelo / Año</label>
                                <input type="text" name="vehiculo[modelo]" required placeholder="Ej. Versa 2022">
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text" name="vehiculo[color]" required placeholder="Ej. Rojo">
                            </div>
                            <div class="form-group">
                                <label>Placas</label>
                                <input type="text" name="vehiculo[numero_placas]" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="button" class="btn btn-prev" id="btnPrev" onclick="movePage(-1)" style="visibility: hidden;">
                        <i data-lucide="arrow-left" style="width: 16px;"></i> Anterior
                    </button>
                    <div>
                        <button type="button" class="btn btn-next" id="btnNext" onclick="movePage(1)">
                            Siguiente <i data-lucide="arrow-right" style="width: 16px;"></i>
                        </button>
                        <button type="submit" class="btn btn-submit" id="btnSave" style="display: none;" onclick="enviarForm()">
                            <i data-lucide="check-circle" style="width: 16px;"></i> Finalizar Registro
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="toast"></div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        // TODA TU LÓGICA ORIGINAL SE MANTIENE AQUÍ ABAJO
        let current = 1;

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

            if (current === 1) {
                setTimeout(() => {
                    if (window.leafletMap) window.leafletMap.invalidateSize();
                }, 300);
            }

            document.getElementById('btnPrev').style.visibility = current === 1 ? 'hidden' : 'visible';
            
            if (current === 4) {
                document.getElementById('btnNext').style.display = 'none';
                document.getElementById('btnSave').style.display = 'flex';
            } else {
                document.getElementById('btnNext').style.display = 'flex';
                document.getElementById('btnSave').style.display = 'none';
            }
        }

        function enviarForm(e) {
            if (e) e.preventDefault();
            const form = document.getElementById('multiStepForm');
            const btnSave = document.getElementById('btnSave');
            
            // Limpiar errores previos
            document.querySelectorAll('.error-msg').forEach(el => el.remove());
            document.querySelectorAll('input, select').forEach(el => el.classList.remove('is-invalid'));

            const formData = new FormData(form);
            btnSave.disabled = true;
            btnSave.innerHTML = '<i class="animate-spin" data-lucide="loader-2"></i> Procesando...';
            lucide.createIcons();

            fetch('/api/crear/distribuidora', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok) {
                    mostrarToast('✅ Registro completado con éxito', 'success');
                    setTimeout(() => {
                        window.location.href = "{{ route('coordinador.notificaciones') }}";
                    }, 2000);
                } else if (res.status === 422) {
                    // Errores de validación de Laravel
                    mostrarToast('❌ Revisa los campos marcados en rojo', 'error');
                    highlightErrors(data.errors);
                } else {
                    mostrarToast('❌ ' + (data.mensaje || 'Error en el servidor'), 'error');
                }
            })
            .catch(err => {
                mostrarToast('❌ Error de conexión', 'error');
            })
            .finally(() => {
                btnSave.disabled = false;
                btnSave.innerHTML = '<i data-lucide="check-circle" style="width: 16px;"></i> Finalizar Registro';
                lucide.createIcons();
            });
        }

        function highlightErrors(errors) {
            let firstErrorPage = null;
            let errorList = [];

            for (const [key, messages] of Object.entries(errors)) {
                let nameParts = key.split('.');
                let selector = nameParts[0];
                for(let i=1; i<nameParts.length; i++) selector += `[${nameParts[i]}]`;
                
                const input = document.querySelector(`[name="${selector}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errorLabel = document.createElement('span');
                    errorLabel.className = 'error-msg';
                    errorLabel.textContent = messages[0];
                    input.parentNode.appendChild(errorLabel);
                    
                    const page = input.closest('.form-page');
                    if (page && !firstErrorPage) {
                        firstErrorPage = parseInt(page.id.replace('page', ''));
                    }
                } else {
                    // Si el campo no existe en el HTML, lo guardamos para mostrarlo en el toast
                    errorList.push(`${key}: ${messages[0]}`);
                }
            }

            if (errorList.length > 0) {
                alert("Errores detectados:\n" + errorList.join("\n"));
            }

            if (firstErrorPage) {
                goToPage(firstErrorPage);
            }
        }

        function goToPage(pageNum) {
            document.getElementById(`page${current}`).classList.remove('active');
            document.getElementById(`s${current}`).classList.remove('active');
            current = pageNum;
            document.getElementById(`page${current}`).classList.add('active');
            document.getElementById(`s${current}`).classList.add('active');
            
            document.getElementById('btnPrev').style.visibility = current === 1 ? 'hidden' : 'visible';
            if (current === 4) {
                document.getElementById('btnNext').style.display = 'none';
                document.getElementById('btnSave').style.display = 'flex';
            } else {
                document.getElementById('btnNext').style.display = 'flex';
                document.getElementById('btnSave').style.display = 'none';
            }
            
            if (current === 1 && window.leafletMap) {
                setTimeout(() => window.leafletMap.invalidateSize(), 300);
            }
        }

        function mostrarToast(mensaje, tipo = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = mensaje;
            toast.style.background = tipo === 'success' ? '#10b981' : '#ef4444';
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(0)';
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(20px)';
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            const latIni = 25.5438;
            const lngIni = -103.4189;
            const latInput = document.getElementById('lat_input');
            const lngInput = document.getElementById('lng_input');
            
            latInput.value = latIni;
            lngInput.value = lngIni;

            window.leafletMap = L.map('map', { attributionControl: false }).setView([latIni, lngIni], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(window.leafletMap);
            let marker = L.marker([latIni, lngIni], { draggable: true }).addTo(window.leafletMap);

            const geocoder = L.Control.geocoder({
                defaultMarkGeocode: false,
                placeholder: "Buscar calle y número...",
            })
            .on('markgeocode', function(e) {
                const center = e.geocode.center;
                window.leafletMap.setView(center, 18);
                marker.setLatLng(center);
                document.getElementById('domicilio_input').value = e.geocode.name;
                latInput.value = center.lat.toFixed(8);
                lngInput.value = center.lng.toFixed(8);
            })
            .addTo(window.leafletMap);

            marker.on('dragend', function() {
                const pos = marker.getLatLng();
                latInput.value = pos.lat.toFixed(8);
                lngInput.value = pos.lng.toFixed(8);
            });
        });
    </script>
</body>
</html>