<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cajera - Prevales</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
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
            padding: 30px;
        }

        /* Buscador */
        .search-container {
            display: flex;
            gap: 15px;
            max-width: 600px;
            margin-bottom: 30px;
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
            color: #94a3b8;
        }
        .search-input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 1rem;
            outline: none;
            transition: all 0.2s;
        }
        .search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .btn-buscar {
            background: #2563eb;
            color: white;
            border: none;
            padding: 0 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-buscar:hover { background: #1d4ed8; }

        .text-monto { font-weight: 700; color: #0f172a; font-size: 1.1rem; }
        .text-secundario { color: #64748b; font-size: 0.8rem; }
        .text-primario { font-weight: 600; color: #1e293b; }

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
            max-width: 600px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .modal-header h3 { font-size: 1.25rem; font-weight: 700; color: #0f172a; }
        
        /* Detalles en modal */
        .detalle-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .detalle-item { display: flex; flex-direction: column; gap: 4px; }
        .detalle-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .detalle-valor { font-size: 0.95rem; font-weight: 600; color: #0f172a; }
        
        .modal-footer { display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; }
        .btn-cancelar {
            padding: 12px 24px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; cursor: pointer; font-weight: 600; color: #475569;
        }

        /* Confirmar (Botón verde) */
        .btn-confirmar {
            background: #10b981;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }
        .btn-confirmar:hover { background: #059669; transform: translateY(-2px); }

        /* Toasts */
        #toast {
            position: fixed; top: 30px; right: 30px;
            padding: 16px 24px; border-radius: 12px;
            font-size: 0.9rem; font-weight: 600; color: white;
            z-index: 100000; opacity: 0; transform: translateY(-20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <x-aside-bar/>
        <main class="contenido">
            <div class="header-section">
                <h1>Validación de Prevales</h1>
                <p class="welcome-text">Escribe el folio del prevale para revisar la información y confirmarlo.</p>
            </div>

            <div class="panel">
                <div class="search-container">
                    <div class="search-input-wrapper">
                        <i data-lucide="search" style="width: 20px;"></i>
                        <input type="text" id="inputFolio" class="search-input" placeholder="Ej. VALE-12345678" onkeypress="if(event.key === 'Enter') buscarPrevale()">
                    </div>
                    <button class="btn-buscar" onclick="buscarPrevale()">
                        Buscar
                    </button>
                </div>

            </div>
        </main>
    </div>

    {{-- MODAL RESULTADOS --}}
    <div id="modalVale" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Detalles del Prevale</h3>
                <button onclick="cerrarModal()" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#64748b;">&times;</button>
            </div>
            
            <div class="detalle-grid">
                <div class="detalle-item" style="grid-column: span 2;">
                    <span class="detalle-label">Folio</span>
                    <span class="detalle-valor" id="modalFolio" style="color: #3b82f6; font-family: monospace; font-size: 1.1rem;"></span>
                </div>
                
                <div class="detalle-item" style="grid-column: span 2;">
                    <span class="detalle-label">Cliente</span>
                    <span class="detalle-valor" id="modalClienteNombre"></span>
                    <span style="font-size: 0.85rem; color: #64748b;" id="modalClienteDocs"></span>
                </div>

                <div class="detalle-item">
                    <span class="detalle-label">Nacimiento</span>
                    <span class="detalle-valor" id="modalClienteNac"></span>
                </div>

                <div class="detalle-item">
                    <span class="detalle-label">Contacto</span>
                    <span class="detalle-valor" id="modalClienteContacto" style="font-size: 0.85rem; line-height: 1.4; white-space: pre-line;"></span>
                </div>

                <div class="detalle-item">
                    <span class="detalle-label">Monto</span>
                    <span class="detalle-valor text-monto" id="modalMonto" style="font-size: 1.25rem;"></span>
                </div>

                <div class="detalle-item">
                    <span class="detalle-label">Plazo y Distribuidora</span>
                    <span class="detalle-valor" id="modalPlazo"></span>
                    <span style="font-size: 0.85rem; color: #64748b;" id="modalDistribuidora"></span>
                </div>
                
                <div class="detalle-item" style="grid-column: span 2;">
                    <span class="detalle-label">Documentos</span>
                    <div style="display: flex; gap: 10px; margin-top: 4px;">
                        <a id="btnDocINE" href="#" target="_blank" class="btn-cancelar" style="padding: 8px 16px; font-size: 0.85rem; display: none; align-items: center; gap: 6px; text-decoration: none;">
                            <i data-lucide="file-text" style="width: 16px;"></i> Ver INE
                        </a>
                        <a id="btnDocComprobante" href="#" target="_blank" class="btn-cancelar" style="padding: 8px 16px; font-size: 0.85rem; display: none; align-items: center; gap: 6px; text-decoration: none;">
                            <i data-lucide="file-text" style="width: 16px;"></i> Ver Comprobante
                        </a>
                        <span id="txtSinDocumentos" style="color: #94a3b8; font-size: 0.85rem; display: none; margin-top: 5px;">Sin documentos adjuntos</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button class="btn-confirmar" id="btnConfirmarVale" onclick="confirmarVale()">
                    <i data-lucide="check-circle" style="width: 20px;"></i> Confirmar Vale
                </button>
            </div>
        </div>
    </div>

    <div id="toast"></div>

    <script>
        lucide.createIcons();
        let valeActualId = null;

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

        async function buscarPrevale() {
            const folio = document.getElementById('inputFolio').value.trim();
            if (!folio) {
                mostrarToast('Por favor, ingresa un folio.', 'error');
                return;
            }

            try {
                const res = await fetch(`/cajera/prevale/buscar/${folio}`);
                const data = await res.json();

                if (!res.ok) {
                    mostrarToast(data.mensaje || 'Error al buscar el vale', 'error');
                    cerrarModal();
                    return;
                }

                mostrarResultados(data.vale);
            } catch (error) {
                console.error(error);
                mostrarToast('Error de conexión', 'error');
                cerrarModal();
            }
        }

        function mostrarResultados(vale) {
            valeActualId = vale.id;
            const p = vale.cliente.persona;
            
            document.getElementById('modalFolio').textContent = vale.folio;
            document.getElementById('modalClienteNombre').textContent = `${p.nombre} ${p.apellido}`;
            document.getElementById('modalClienteDocs').textContent = `CURP: ${p.CURP} • RFC: ${p.RFC}`;
            
            // Format date to remove time part
            const fechaSolo = p.fecha_nacimiento ? p.fecha_nacimiento.substring(0, 10) : 'N/A';
            document.getElementById('modalClienteNac').textContent = `${fechaSolo} (${p.sexo})`;
            
            let contacto = `Cel: ${p.celular}`;
            if(p.telefono_personal) contacto += `\nTel: ${p.telefono_personal}`;
            document.getElementById('modalClienteContacto').innerText = contacto;

            document.getElementById('modalMonto').textContent = `$${parseFloat(vale.producto.monto).toFixed(2)}`;
            document.getElementById('modalPlazo').textContent = `${vale.producto.quincenas} Quincenas`;

            // Documentos
            const btnINE = document.getElementById('btnDocINE');
            const btnComp = document.getElementById('btnDocComprobante');
            const txtSin = document.getElementById('txtSinDocumentos');
            
            btnINE.style.display = 'none';
            btnComp.style.display = 'none';
            txtSin.style.display = 'none';

            let hasDocs = false;
            
            const parseDocUrl = (url) => url.startsWith('http') || url.startsWith('/') ? url : '/storage/' + url;

            if (vale.cliente.INE) {
                btnINE.href = parseDocUrl(vale.cliente.INE);
                btnINE.style.display = 'inline-flex';
                hasDocs = true;
            }
            if (vale.cliente.comprobante_domicilio) {
                btnComp.href = parseDocUrl(vale.cliente.comprobante_domicilio);
                btnComp.style.display = 'inline-flex';
                hasDocs = true;
            }
            if (!hasDocs) {
                txtSin.style.display = 'block';
            }

            document.getElementById('modalVale').style.display = 'flex';
            lucide.createIcons();
        }

        function cerrarModal() {
            valeActualId = null;
            document.getElementById('modalVale').style.display = 'none';
        }

        async function confirmarVale() {
            if (!valeActualId) return;

            try {
                const res = await fetch(`/cajera/prevale/confirmar/${valeActualId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await res.json();

                if (res.ok) {
                    mostrarToast('✅ ' + data.mensaje, 'success');
                    document.getElementById('inputFolio').value = '';
                    cerrarModal();
                } else {
                    mostrarToast('❌ ' + (data.mensaje || 'Error al confirmar'), 'error');
                }
            } catch (error) {
                console.error(error);
                mostrarToast('❌ Error de conexión', 'error');
            }
        }
    </script>
</body>
</html>