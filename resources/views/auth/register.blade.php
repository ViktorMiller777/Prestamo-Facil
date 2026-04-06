<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presolicitud - Prevale</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
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
    .barra {
        padding: 15px 30px;
        display: flex;
        align-items: center;
        background: #111827;
        gap: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .barra img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
    }
    .barra h1 {
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
    }

    /* Contenedor del Formulario */
    .container {
        max-width: 90%;
        margin: 40px auto;
        background: white;
        padding: 40px;
        border-radius: 16px;
        shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f3f4f6;
    }

    /* Grid del Formulario */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    /* En móviles, una sola columna */
    @media (max-width: 600px) {
        .form-grid { grid-template-columns: 1fr; }
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #4b5563;
    }

    input, select {
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        transition: all 0.2s;
        font-size: 0.95rem;
    }

    input:focus, select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .btn-submit {
        background: #2563eb;
        color: white;
        padding: 15px 30px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background: #1d4ed8;
    }
    .pf-header {
        background: #111827;
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--pf-border);
    }

    .pf-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        font-size: 1.4rem;
        color: white;
    }

    .pf-logo-box {
        background: #305be3;
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
    }

    /* Estilo para campos ocultos */
    .hidden { display: none; }
</style>
<body>
   <div class="barra">
        <header class="pf-header">
            <div class="pf-logo">
                <div class="pf-logo-box">PF</div>
                Préstamo Fácil
            </div>
        </header>
        </a>

        <a href="{{ route('dashboard') }}" style="margin-left: auto; color: white; text-decoration: none; font-size: 0.95rem; padding: 5px 12px; border-radius: 6px; transition: all 0.2s;" onmouseover="this.style.color='white'; this.style.borderColor='white'" onmouseout="this.style.color='#9ca3af'; this.style.borderColor='#374151'">
            ← Volver al Dashboard
        </a>
    </div>
    <div class="container">
        <form action="{{ route('distribuidoras.store') }}" method="POST">
            @csrf

            <div class="form-section">
                <h3 class="section-title">Información Personal</h3>
                <div class="form-grid">
                    <div class="field-group">
                        <label>Nombre</label>
                        <input type="text" name="nombre" placeholder="Juan Carlos" required>
                    </div>
                    <div class="field-group">
                        <label>Apellido</label>
                        <input type="text" name="apellido" placeholder="Rodríguez" required>
                    </div>
                    <div class="field-group">
                        <label>Sexo</label>
                        <select name="sexo">
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" required>
                    </div>
                    <div class="field-group">
                        <label>CURP</label>
                        <input type="text" name="CURP" placeholder="CURP de 18 dígitos" maxlength="18" style="text-transform:uppercase">
                    </div>
                    <div class="field-group">
                        <label>RFC</label>
                        <input type="text" name="RFC" placeholder="RFC de 13 dígitos" maxlength="13" style="text-transform:uppercase">
                    </div>
                </div>
            </div>
            <div class="form-section">
                <h3 class="section-title">Contacto y Acceso</h3>
                <div class="form-grid">
                    <div class="field-group">
                        <label>Email (Usuario)</label>
                        <input type="email" name="email" placeholder="correo@ejemplo.com" required>
                    </div>
                    <div class="field-group">
                        <label>Contraseña</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="field-group">
                        <label>Teléfono Personal</label>
                        <input type="text" name="telefono_personal">
                    </div>
                    <div class="field-group">
                        <label>Celular</label>
                        <input type="text" name="celular">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3 class="section-title">Datos de Sistema y Crédito</h3>
                <div class="form-grid">
                    <div class="field-group">
                        <label>Línea de Crédito ($)</label>
                        <input type="number" name="linea_credito" step="0.01" placeholder="15000.00">
                    </div>
                    <div class="field-group">
                        <label>Sucursal ID</label>
                        <input type="number" name="sucursal_id" value="1">
                    </div>
                    <div class="field-group">
                        <label>Categoría ID</label>
                        <input type="number" name="categoria_id" value="1">
                    </div>
                    <div class="field-group">
                        <label>Estado</label>
                        <select name="estado">
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>

            <input type="hidden" name="role_id" value=4>
            <input type="hidden" name="puntos" value=0>
            <input type="hidden" name="geolocalizacion_lat" value="25.54389">
            <input type="hidden" name="geolocalizacion_lng" value="-103.41898">

            <button type="submit" class="btn-submit">
                Guardar Presolicitud
            </button>
        </form>
    </div>
</body>
</html>