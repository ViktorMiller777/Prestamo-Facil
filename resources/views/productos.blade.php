<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Monto</th>
            <th>% Comisión</th>
            <th>Seguro</th>
            <th>Quincenas</th>
            <th>Interés Quincenal</th>
            <th>Activo</th>
        </tr>
    </thead>
    <tbody id="tabla-productos">
        <tr>
            <td colspan="7">Cargando...</td>
        </tr>
    </tbody>
</table>

<script>
    fetch('/api/lista/productos', {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('tabla-productos');
        tbody.innerHTML = data.productos.map(p => `
            <tr>
                <td>${p.id}</td>
                <td>$${parseFloat(p.monto).toFixed(2)}</td>
                <td>${p.porcentaje_comision}%</td>
                <td>$${parseFloat(p.seguro).toFixed(2)}</td>
                <td>${p.quincenas}</td>
                <td>${p.interes_quincenal}%</td>
                <td>${p.activo ? 'Activo' : 'Inactivo'}</td>
            </tr>
        `).join('');
    });
</script>