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
                                <input type="text" name="persona[nombre]" required placeholder="Ej: Kory">
                            </div>
                            <div class="form-group">
                                <label>Apellido(s)</label>
                                <input type="text" name="persona[apellido]" required placeholder="Ej: Martinez">
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
                                <input type="text" name="persona[CURP]" required maxlength="18">
                            </div>
                            <div class="form-group">
                                <label>RFC</label>
                                <input type="text" name="persona[RFC]" maxlength="13">
                            </div>
                            <div class="form-group">
                                <label>Fecha Nacimiento</label>
                                <input type="date" name="persona[fecha_nacimiento]" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono Personal</label>
                                <input type="text" name="persona[telefono_personal]"> <!--CHECAR QUE SI ES TEXT -->
                            </div>
                            <div class="form-group">
                                <label>Celular</label>
                                <input type="text" name="persona[celular]" required> <!--CHECAR QUE SI ES TEXT --> 
                            </div>
                        </div>
                    </div>

                    <div class="form-page" id="page2">
                        <span class="section-title">Configuración de la Distribuidora</span>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Email de Acceso</label>
                                <input type="email" name="usuario[email]" required placeholder="kory@ejemplo.com">
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
                            <input type="hidden" name="distribuidora[estado]" value="inactivo">
                            <input type="hidden" name="distribuidora[puntos]" value="0">
                            <input type="hidden" name="distribuidora[geolocalizacion_lat]" value="25.5438">
                            <input type="hidden" name="distribuidora[geolocalizacion_lng]" value="-103.4189">
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
                                <input type="text" name="familiar[RFC]" maxlength="13">
                            </div>
                            <div class="form-group">
                                <label>Fecha Nacimiento</label>
                                <input type="date" name="familiar[fecha_nacimiento]" required>
                            </div>
                            <div class="form-group">
                                <label>Teléfono Personal</label>
                                <input type="text" name="familiar[telefono_personal]"> <!--CHECAR QUE SI ES TEXT -->
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
                                <input type="text" name="vehiculo[marca]">
                            </div>
                            <div class="form-group">
                                <label>Modelo</label>
                                <input type="text" name="vehiculo[modelo]">
                            </div>
                            <div class="form-group">
                                <label>Placas</label>
                                <input type="text" name="vehiculo[numero_placas]">
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <input type="text" name="vehiculo[color]">
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

    function movePage(step) {
        // Validar que no se pase de los límites
        if (current + step < 1 || current + step > 4) return;

        // Ocultar actual
        document.getElementById(`page${current}`).classList.remove('active');
        document.getElementById(`s${current}`).classList.remove('active');

        current += step;

        // Mostrar siguiente
        document.getElementById(`page${current}`).classList.add('active');
        document.getElementById(`s${current}`).classList.add('active');

        // Actualizar botones
        document.getElementById('btnPrev').style.visibility = current === 1 ? 'hidden' : 'visible';
        
        if (current === 4) {
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnSave').style.display = 'inline-block';
        } else {
            document.getElementById('btnNext').style.display = 'inline-block';
            document.getElementById('btnSave').style.display = 'none';
        }
    }
    
    function enviarForm() {
        const form = document.getElementById('multiStepForm');
        const formData = new FormData(form);

        // Ver qué se está enviando
        console.log('=== Datos del form ===');
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
            console.log('Respuesta:', data);
            if (data.res) {
                mostrarToast('✅ Distribuidora activada correctamente', 'success');
                setTimeout(() => {
                    window.location.href = '/verificador/notificaciones';
                }, 1400);
            } else {
                mostrarToast('❌ ' + data.mensaje, 'error');
            }
        })
        .catch(() => mostrarToast('❌ Error al activar', 'error'));
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
</script>
</html>