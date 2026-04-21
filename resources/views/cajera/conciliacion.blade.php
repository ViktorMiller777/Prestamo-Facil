<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Conciliación - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary: #2563eb;
            --primary-soft: #eff6ff;
            --success: #10b981;
            --bg: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        body, html { width: 100%; height: 100%; background-color: var(--bg); color: var(--text-main); }

        .dashboard-container { display: flex; width: 100%; height: 100vh; }
        
        .contenido { 
            flex: 1; 
            width: 100%; 
            padding: 40px; 
            overflow-y: auto; 
        }

        .header-section { margin-bottom: 30px; }
        .header-section h1 { font-size: 1.875rem; font-weight: 700; color: var(--text-main); }
        .welcome-text { color: var(--text-muted); margin-top: 4px; }

        /* Panel Principal */
        .panel { 
            background: var(--white); 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); 
            border: 1px solid var(--border);
            padding: 30px;
            margin-bottom: 30px;
        }

        .search-group {
            display: flex;
            gap: 15px;
            max-width: 700px;
        }

        .search-input-wrapper {
            flex: 1;
            position: relative;
        }

        .search-input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .search-input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 1rem;
            font-weight: 500;
            outline: none;
            transition: all 0.2s;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-search {
            padding: 0 24px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-search:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        /* Resultados */
        .result-panel {
            display: none;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .info-card {
            background: var(--white);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid var(--border);
        }

        .section-title {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .grid-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-item label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .info-item span {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .divider {
            height: 1px;
            background: #f1f5f9;
            margin: 25px 0;
        }

        .status-badge {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Toast */
        #toast {
            position: fixed;
            top: 30px;
            right: 30px;
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            z-index: 10000;
            display: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <x-aside-bar />

        <main class="contenido">
            <div class="header-section">
                <h1>Conciliación de Vales</h1>
                <p class="welcome-text">Busca un vale activo por su folio para verificar sus detalles y liquidación.</p>
            </div>

            <div class="panel">
                <div class="search-group">
                    <div class="search-input-wrapper">
                        <i data-lucide="search" style="width: 20px;"></i>
                        <input type="text" id="folioInput" class="search-input" placeholder="Ingresa el folio (ej: VALE-123)..." onkeypress="if(event.key === 'Enter') buscarVale()">
                    </div>
                    <button class="btn-search" onclick="buscarVale()">
                        <i data-lucide="search" style="width: 18px;"></i>
                        Buscar Vale
                    </button>
                </div>
            </div>

            <div id="resultPanel" class="result-panel">
                <div class="info-card">
                    <div class="section-title">
                        <i data-lucide="file-text" style="width: 16px;"></i> Información del Vale
                    </div>
                    
                    <div class="grid-info">
                        <div class="info-item">
                            <label>Folio</label>
                            <span id="resFolio" style="color: var(--primary); font-family: monospace; font-size: 1.1rem;">---</span>
                        </div>
                        <div class="info-item">
                            <label>Estado</label>
                            <div><span class="status-badge" id="resEstado">Activo</span></div>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="section-title">
                        <i data-lucide="user" style="width: 16px;"></i> Cliente
                    </div>
                    <div class="grid-info">
                        <div class="info-item">
                            <label>Nombre Completo</label>
                            <span id="resCliente">---</span>
                        </div>
                        <div class="info-item">
                            <label>CURP</label>
                            <span id="resCurp" style="font-family: monospace;">---</span>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="section-title">
                        <i data-lucide="briefcase" style="width: 16px;"></i> Distribuidora
                    </div>
                    <div class="grid-info">
                        <div class="info-item">
                            <label>Titular</label>
                            <span id="resDistribuidora">---</span>
                        </div>
                        <div class="info-item">
                            <label>Línea de Crédito</label>
                            <span id="resCredito">$0.00</span>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="section-title">
                        <i data-lucide="shopping-bag" style="width: 16px;"></i> Detalle Económico
                    </div>
                    <div class="grid-info">
                        <div class="info-item">
                            <label>Monto Solicitado</label>
                            <span id="resMontoBase">$0.00</span>
                        </div>
                        <div class="info-item">
                            <label>Plazo</label>
                            <span id="resProducto">---</span>
                        </div>
                        <div class="info-item">
                            <label>Seguro</label>
                            <span id="resSeguro">$0.00</span>
                        </div>
                        <div class="info-item">
                            <label>Interés Quincenal</label>
                            <span id="resInteres">0%</span>
                        </div>
                        <div class="info-item">
                            <label>Total a Pagar</label>
                            <span id="resTotal" style="font-weight: 700;">$0.00</span>
                        </div>
                        <div class="info-item">
                            <label>Pago Quincenal</label>
                            <span id="resPago" style="color: var(--success); font-size: 1.25rem; font-weight: 800;">$0.00</span>
                        </div>
                    </div>

                    <div id="relacionSection" style="display: none;">
                        <div class="divider"></div>
                        <div class="section-title">
                            <i data-lucide="credit-card" style="width: 16px;"></i> Datos de Relación / Corte
                        </div>
                        <div class="grid-info">
                            <div class="info-item">
                                <label>Referencia de Pago</label>
                                <span id="resReferencia" style="font-family: monospace; font-weight: 700;">---</span>
                            </div>
                            <div class="info-item">
                                <label>Fecha Límite de Pago</label>
                                <span id="resFechaLimite">---</span>
                            </div>
                            <div class="info-item">
                                <label>Recargos (en este corte)</label>
                                <span id="resRecargos" style="color: #ef4444; font-weight: 700;">$0.00</span>
                            </div>
                            <div class="info-item">
                                <label>Total a Pagar Corte</label>
                                <span id="resTotalCorte" style="font-weight: 700;">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="toast"></div>

    <script>
        lucide.createIcons();

        function buscarVale() {
            const folio = document.getElementById('folioInput').value.trim();
            const resultPanel = document.getElementById('resultPanel');
            
            if (!folio) {
                mostrarToast('Por favor ingresa un folio', 'error');
                return;
            }

            resultPanel.style.display = 'none';

            fetch(`/cajera/conciliacion/buscar/${folio}`)
                .then(res => {
                    if (!res.ok) return res.json().then(err => { throw err });
                    return res.json();
                })
                .then(data => {
                    const v = data.vale;
                    
                    document.getElementById('resFolio').textContent = v.folio;
                    document.getElementById('resEstado').textContent = v.estado;
                    document.getElementById('resCliente').textContent = `${v.cliente.persona.nombre} ${v.cliente.persona.apellido}`;
                    document.getElementById('resCurp').textContent = v.cliente.persona.CURP;
                    document.getElementById('resDistribuidora').textContent = `${v.distribuidora.usuario.persona.nombre} ${v.distribuidora.usuario.persona.apellido}`;
                    document.getElementById('resCredito').textContent = '$' + parseFloat(v.distribuidora.linea_credito).toLocaleString(undefined, {minimumFractionDigits: 2});
                    document.getElementById('resProducto').textContent = `${v.producto.quincenas} quincenas`;
                    // Datos de DetalleVale y Relación
                    const dv = v.detalle_vale || v.detalleVale; // Eloquent relation name
                    if (dv) {
                        document.getElementById('resMontoBase').textContent = '$' + parseFloat(dv.monto).toLocaleString(undefined, {minimumFractionDigits: 2});
                        document.getElementById('resSeguro').textContent = '$' + parseFloat(dv.seguro).toLocaleString(undefined, {minimumFractionDigits: 2});
                        document.getElementById('resInteres').textContent = dv.interes_quincenal + '%';
                        document.getElementById('resTotal').textContent = '$' + parseFloat(dv.monto_comision_calculada).toLocaleString(undefined, {minimumFractionDigits: 2});
                        document.getElementById('resPago').textContent = '$' + parseFloat(dv.pago).toLocaleString(undefined, {minimumFractionDigits: 2});
                        
                        if (dv.relacion) {
                            document.getElementById('relacionSection').style.display = 'block';
                            document.getElementById('resReferencia').textContent = dv.relacion.referencia_de_pago || 'N/A';
                            document.getElementById('resFechaLimite').textContent = dv.relacion.fecha_limite_pago || 'N/A';
                            document.getElementById('resRecargos').textContent = '$' + parseFloat(dv.relacion.recargos).toLocaleString(undefined, {minimumFractionDigits: 2});
                            document.getElementById('resTotalCorte').textContent = '$' + parseFloat(dv.relacion.total).toLocaleString(undefined, {minimumFractionDigits: 2});
                        } else {
                            document.getElementById('relacionSection').style.display = 'none';
                        }
                    } else {
                        document.getElementById('resMontoBase').textContent = '$' + parseFloat(v.producto.monto).toLocaleString(undefined, {minimumFractionDigits: 2});
                        document.getElementById('resPago').textContent = 'N/A';
                        document.getElementById('relacionSection').style.display = 'none';
                    }

                    resultPanel.style.display = 'block';
                    mostrarToast('✅ Vale localizado');
                })
                .catch(err => {
                    mostrarToast('❌ ' + (err.mensaje || 'Error al buscar vale'), 'error');
                });
        }

        function mostrarToast(mensaje, tipo = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = mensaje;
            toast.style.display = 'block';
            toast.style.background = tipo === 'success' ? '#10b981' : '#ef4444';
            toast.style.opacity = '1';
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => { toast.style.display = 'none'; }, 400);
            }, 3000);
        }
    </script>
</body>
</html>
