<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
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
        padding: 20px; /* Espacio interno */
    }

    /* --- Estilos para la tabla dentro de box-2 --- */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .custom-table th {
        background-color: #f8f9fa;
        color: #6b7280;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 15px;
        text-align: left;
        border-bottom: 2px solid #edf2f7;
    }

    .custom-table td {
        padding: 15px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: bold;
        display: inline-block;
    }
    .badge-m { background: #e0f2fe; color: #0369a1; }
    .badge-f { background: #fce7f3; color: #9d174d; }

    .btn-action {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.8rem;
        color: white;
        background: #305be3;
        font-weight: 600;
    }

    #loader { text-align: center; color: #6b7280; }
</style>
<body>
    <x-header-bar />

    <div class="box-2">
        <div id="loader">Cargando clientes...</div>
        
        <table class="custom-table" id="tabla-clientes" style="display: none;">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>CURP / RFC</th>
                    <th>Celular</th>
                    <th>Docs</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>

    <script>
        // Cambia el ID por el de tu distribuidora
        const distribuidoraId = 1; 
        const url = `http://127.0.0.1:8000/api/clientes/distribuidora/${distribuidoraId}`;

        async function cargarClientes() {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Error al conectar con la API');
                
                const data = await response.json();
                const tbody = document.querySelector('#tabla-clientes tbody');
                
                tbody.innerHTML = '';

                data.clientes.forEach(c => {
                    const fila = `
                        <tr>
                            <td>
                                <div style="font-weight: 800; font-height:1000">${c.persona.nombre} ${c.persona.apellido}</div>
                                <span class="badge ${c.persona.sexo === 'M' ? 'badge-m' : 'badge-f'}">
                                    ${c.persona.sexo === 'M' ? 'M' : 'F'}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 1rem;">${c.persona.CURP}</div>
                                <div style="color: gray; font-size: 1rem;">${c.persona.RFC}</div>
                            </td>
                            <td style="font-size:1rem;">${c.persona.celular}</td>
                            <td>
                                <a href="/storage/docs/${c.INE}" target="_blank" style="text-decoration:none">🪪</a>
                                <a href="/storage/docs/${c.comprobante_domicilio}" target="_blank" style="text-decoration:none; margin-left:5px">🏠</a>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += fila;
                });

                document.getElementById('loader').style.display = 'none';
                document.getElementById('tabla-clientes').style.display = 'table';

            } catch (error) {
                document.getElementById('loader').innerText = "Error: " + error.message;
            }
        }

        document.addEventListener('DOMContentLoaded', cargarClientes);
    </script>
</body>
</html>