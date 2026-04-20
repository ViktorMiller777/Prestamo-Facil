<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Préstamo Fácil</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --bg-color: #f8fafc;
            --white: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --accent: #3b82f6;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            padding: 20px;
            padding-top: 8rem !important; /* Espacio para el header bar */
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 1200px;
        }

        .box-2 {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .header-section {
            padding: 25px 30px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-section h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th {
            background-color: #f1f5f9;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 15px 30px;
            text-align: left;
        }

        .custom-table td {
            padding: 20px 30px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .client-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-main);
            display: block;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 800;
            display: inline-block;
            margin-top: 4px;
        }
        .badge-m { background: #dcfce7; color: #15803d; }
        .badge-f { background: #fce7f3; color: #9d174d; }

        .id-text {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: var(--text-muted);
            display: block;
        }

        .btn-doc {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #f1f5f9;
            color: var(--text-muted);
            text-decoration: none;
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        .btn-doc:hover {
            background: var(--accent);
            color: white;
            transform: translateY(-2px);
        }

        .empty-state {
            padding: 60px;
            text-align: center;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <x-header-bar />

    <div class="container">
        <div class="box-2">
            <div class="header-section">
                <h2><i data-lucide="users"></i> Cartera de Clientes</h2>
                <span class="badge" style="background: #f1f5f9; color: var(--text-muted);">
                    {{ count($clientes) }} Registrados
                </span>
            </div>

            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Identificación</th>
                        <th>Contacto</th>
                        <th>Documentos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $cliente)
                    <tr>
                        <td>
                            <span class="client-name">{{ $cliente->persona->nombre }} {{ $cliente->persona->apellido }}</span>
                            <span class="badge {{ $cliente->persona->sexo == 'M' ? 'badge-m' : 'badge-f' }}">
                                {{ $cliente->persona->sexo == 'M' ? 'MASCULINO' : 'FEMENINO' }}
                            </span>
                        </td>
                        <td>
                            <span class="id-text"><strong>CURP:</strong> {{ $cliente->persona->CURP }}</span>
                            <span class="id-text"><strong>RFC:</strong> {{ $cliente->persona->RFC }}</span>
                        </td>
                        <td>
                            <div style="font-size: 0.9rem; color: var(--text-main); font-weight: 500; display: flex; align-items: center; gap: 8px;">
                                <i data-lucide="phone" style="width: 14px; color: var(--accent);"></i>
                                {{ $cliente->persona->telefono_personal }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                @php
                                    $ine = $cliente->documentos->where('tipo', 'INE')->first();
                                    $comprobante = $cliente->documentos->where('tipo', 'Comprobante Domicilio')->first();
                                @endphp

                                @if($ine)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('spaces')->temporaryUrl($ine->archivo_path, now()->addMinutes(10)) }}" 
                                       target="_blank" class="btn-doc" title="Ver INE">
                                        <i data-lucide="contact-2"></i>
                                    </a>
                                @else
                                    <div class="btn-doc" title="INE No disponible" style="opacity: 0.4; cursor: not-allowed;">
                                        <i data-lucide="contact-2"></i>
                                    </div>
                                @endif

                                @if($comprobante)
                                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('spaces')->temporaryUrl($comprobante->archivo_path, now()->addMinutes(10)) }}" 
                                       target="_blank" class="btn-doc" title="Ver Comprobante">
                                        <i data-lucide="home"></i>
                                    </a>
                                @else
                                    <div class="btn-doc" title="Comprobante No disponible" style="opacity: 0.4; cursor: not-allowed;">
                                        <i data-lucide="home"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($clientes) == 0)
                <div class="empty-state">
                    <i data-lucide="search-x" style="width: 48px; height: 48px; margin-bottom: 10px;"></i>
                    <p>No hay clientes registrados en esta distribuidora.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>