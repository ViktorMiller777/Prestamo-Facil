<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Distribuidora</title>
</head>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
    body, html {
        padding-top: 4rem !important;
        display: flex;
        flex-direction: column;
        gap: 25px;
        padding: 10px;
        width: 100%;
        background-color: #f4f7f6;
    }
    .box-2 { border-radius:10px; width:100%; background:white; box-shadow:0 4px 6px rgba(0,0,0,0.1); padding:30px; }
    .section-title { font-size:1rem; font-weight:700; color:#111827; margin-bottom:15px; padding-bottom:8px; border-bottom:2px solid #e5e7eb; }
    .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:25px; }
    .dato-item label { font-size:0.75rem; font-weight:600; color:#6b7280; text-transform:uppercase; display:block; margin-bottom:4px; }
    .dato-item span { font-size:0.95rem; color:#111827; font-weight:500; }
    .header-detalle { display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; }
    .header-detalle h2 { font-size:1.3rem; font-weight:800; color:#111827; }
    .btn-back { padding:8px 16px; background:#e5e7eb; color:#374151; border:none; border-radius:8px; cursor:pointer; font-weight:600; font-size:0.9rem; }
    .btn-back:hover { background:#d1d5db; }
    .btn-activar { width:100%; padding:14px; background:#16a34a; color:white; border:none; border-radius:10px; cursor:pointer; font-weight:700; font-size:1rem; margin-top:10px; }
    .btn-activar:hover { background:#15803d; }
    .badge-red { background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:99px; font-size:0.8rem; font-weight:600; }
</style>
<body>
    <x-header-bar />

    <div class="box-2">
        <div class="header-detalle">
            <h2>
                {{ $distribuidora->usuario->persona->nombre }}
                {{ $distribuidora->usuario->persona->apellido }}
                <span class="badge-red" style="margin-left:10px;">Inactiva</span>
            </h2>
            <button class="btn-back" onclick="window.history.back()">← Volver</button>
        </div>

        {{-- Datos Personales --}}
        <p class="section-title">Datos Personales</p>
        <div class="grid-3">
            <div class="dato-item">
                <label>Nombre Completo</label>
                <span>{{ $distribuidora->usuario->persona->nombre }} {{ $distribuidora->usuario->persona->apellido }}</span>
            </div>
            <div class="dato-item">
                <label>Sexo</label>
                <span>{{ $distribuidora->usuario->persona->sexo == 'M' ? 'Masculino' : 'Femenino' }}</span>
            </div>
            <div class="dato-item">
                <label>Fecha de Nacimiento</label>
                <span>{{ \Carbon\Carbon::parse($distribuidora->usuario->persona->fecha_nacimiento)->format('d/m/Y') }}</span>
            </div>
            <div class="dato-item">
                <label>CURP</label>
                <span>{{ $distribuidora->usuario->persona->CURP }}</span>
            </div>
            <div class="dato-item">
                <label>RFC</label>
                <span>{{ $distribuidora->usuario->persona->RFC ?? 'N/A' }}</span>
            </div>
            <div class="dato-item">
                <label>Teléfono Personal</label>
                <span>{{ $distribuidora->usuario->persona->telefono_personal ?? 'N/A' }}</span>
            </div>
            <div class="dato-item">
                <label>Celular</label>
                <span>{{ $distribuidora->usuario->persona->celular }}</span>
            </div>
            <div class="dato-item">
                <label>Email</label>
                <span>{{ $distribuidora->usuario->email }}</span>
            </div>
        </div>

        {{-- Datos Distribuidora --}}
        <p class="section-title">Datos de la Distribuidora</p>
        <div class="grid-3">
            <div class="dato-item">
                <label>ID Distribuidora</label>
                <span>#{{ $distribuidora->id }}</span>
            </div>
            <div class="dato-item">
                <label>Línea de Crédito</label>
                <span>${{ number_format($distribuidora->linea_credito, 2) }}</span>
            </div>
            <div class="dato-item">
                <label>Puntos</label>
                <span>{{ $distribuidora->puntos }}</span>
            </div>
            <div class="dato-item">
                <label>Fecha de Registro</label>
                <span>{{ \Carbon\Carbon::parse($distribuidora->created_at)->format('d/m/Y') }}</span>
            </div>
        </div>

        {{-- Botón Activar --}}
        <button class="btn-activar" onclick="activarDistribuidora({{ $distribuidora->id }})">
            ✅ Activar Distribuidora
        </button>
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
    function activarDistribuidora(id) {
        fetch(`/api/activar/distribuidora/${id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
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