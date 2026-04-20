<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora | Detalle de Relación</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Base y Layout */
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
        body, html {
            background-color: #f4f7f6;
            padding: 20px;
            min-height: 100vh;
            /* Espacio para el header fijo en pantalla */
            padding-top: 4rem !important;
        }

        @media print {
            body, html { 
                padding-top: 0 !important; 
                background: white;
                padding: 0;
            }
            .btn-print, .box { display: none !important; }
            .container-pdf { 
                box-shadow: none !important; 
                margin: 0 !important; 
                width: 100% !important;
                max-width: none !important;
                padding: 0 !important;
            }
        }

        .container-pdf { 
            max-width: 1000px;
            margin: 0 auto;
            background: white; 
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* Encabezado estilo PDF */
        .header-pdf {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .logo-placeholder { font-weight: 800; font-size: 24px; color: #1e40af; }
        
        .distributor-info { line-height: 1.6; color: #374151; font-size: 0.95rem; }
        .credit-info { text-align: right; line-height: 1.6; }

        /* Bloque de Pago Destacado */
        .payment-summary {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, min-minmax(200px, 1fr));
            gap: 20px;
            border-left: 5px solid #1e40af;
        }

        .payment-summary div span { display: block; font-size: 0.75rem; color: #6b7280; text-transform: uppercase; }
        .payment-summary div strong { font-size: 1.1rem; color: #111827; }
        .text-total { color: #1e40af !important; font-size: 1.5rem !important; }

        /* Tabla de Vales */
        .table-container { margin-bottom: 30px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f1f5f9; padding: 12px; text-align: left; font-size: 0.75rem; color: #475569; text-transform: uppercase; border: 1px solid #e2e8f0; }
        td { padding: 12px; border: 1px solid #e2e8f0; font-size: 0.85rem; color: #334155; }
        .row-total { background: #f8fafc; font-weight: bold; }

        /* Pie de página con datos bancarios */
        .footer-pdf {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #cbd5e1;
        }

        .bank-data { font-size: 0.85rem; color: #475569; }
        .bank-logo { font-weight: bold; color: #004481; font-size: 1.2rem; margin-bottom: 5px; }

        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #111827;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <x-header-bar />
    <div class="container-pdf">
        <div class="header-pdf">
            <div class="distributor-info">
                <div class="logo-placeholder">PF PRÉSTAMO FÁCIL SA</div>
                <p><strong>Número Distribuidora:</strong> {{ $relacion['num_distribuidora'] }}</p>
                <p><strong>Nombre:</strong> {{ $relacion['nombre_distribuidora'] }}</p>
                <p><strong>Domicilio:</strong> {{ $relacion['domicilio'] ?? 'No registrado' }}</p>
            </div>
            <div class="credit-info">
                <p>Límite de crédito: <strong>${{ number_format($relacion['limite_de_credito'], 2) }}</strong></p>
                <p>Crédito disponible: <strong>${{ number_format($relacion['credito_disponible'], 2) }}</strong></p>
                <p>Puntos: <strong>{{ $relacion['puntos'] }}</strong></p>
            </div>
        </div>

        <div class="payment-summary">
            <div>
                <span>Referencia de Pago</span>
                <strong>{{ $relacion['referencia_de_pago'] }}</strong>
            </div>
            <div>
                <span>Fecha Límite de Pago</span>
                <strong>{{ \Carbon\Carbon::parse($relacion['fecha_limite_pago'])->locale('es')->translatedFormat('d \d\e F Y') }}</strong>
            </div>
            <div>
                <span>Pago Anticipado</span>
                @php
                    $limite = \Carbon\Carbon::parse($relacion['fecha_limite_pago'])->locale('es');
                    $d1 = $limite->copy()->subDays(3)->format('d');
                    $d2 = $limite->copy()->subDays(2)->format('d');
                    $d3 = $limite->copy()->subDays(1)->format('d');
                    $mesAnio = $limite->copy()->subDays(1)->translatedFormat('F Y');
                @endphp
                <strong>{{ $d1 }},{{ $d2 }},{{ $d3 }} de {{ strtolower($mesAnio) }}</strong>
            </div>
            <div>
                <span>Total a Pagar</span>
                <strong class="text-total">${{ number_format($relacion['total_pagar'], 2) }}</strong>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Cliente</th>
                        <th>Pagos Realizados</th>
                        <th>Comisión</th>
                        <th>Pago</th>
                        <th>Recargos</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $totalComision = 0;
                        $totalPago = 0;
                        $totalRecargos = 0;
                        $totalGeneral = 0;
                    @endphp
                    @foreach($detalles as $index => $det)
                    @php
                        $comision = $det['comision'] ?? 0;
                        $pago = $det['pago'] ?? 0;
                        // Calculamos el recargo individual dividiendo el total de recargos entre la cantidad de vales
                        $recargos = (count($detalles) > 0) ? ($relacion['recargos'] / count($detalles)) : 0;
                        $totalFila = $pago + $recargos;

                        $totalComision += $comision;
                        $totalPago += $pago;
                        $totalRecargos += $recargos;
                        $totalGeneral += $totalFila;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $det['producto_folio'] }}</td>
                        <td>{{ $det['nombre_cliente'] }}</td>
                        <td>
                            @php
                                $pagoActual = explode('/', $relacion['pagos_realizados'])[0] ?? 1;
                            @endphp
                            {{ $pagoActual }}/{{ $det['quincenas'] }}
                        </td>
                        <td>${{ number_format($comision, 2) }}</td>
                        <td>${{ number_format($pago, 2) }}</td>
                        <td>${{ number_format($recargos, 2) }}</td>
                        <td><strong>${{ number_format($totalFila, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="row-total">
                        <td colspan="4" style="text-align: right;">Totales</td>
                        <td>${{ number_format($totalComision, 2) }}</td>
                        <td>${{ number_format($totalPago, 2) }}</td>
                        <td>${{ number_format($totalRecargos, 2) }}</td>
                        <td><strong>${{ number_format($totalGeneral, 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="footer-pdf">
            <div class="bank-data">
                <div class="bank-logo">BBVA</div>
                <p>Convenio: <strong>{{ $relacion['convenio'] }}</strong></p>
                <p>Clabe: <strong>{{ $relacion['cable'] }}</strong></p>
            </div>
            <div style="text-align: right; font-size: 0.75rem; color: #94a3b8;">
                Generado el: {{ now()->format('d/m/Y H:i') }}<br>
                ID Relación: #{{ $relacion['id'] }}
            </div>
        </div>
    </div>

    <a href="javascript:window.print()" class="btn-print">🖨️ Imprimir Detalle</a>

</body>
</html>