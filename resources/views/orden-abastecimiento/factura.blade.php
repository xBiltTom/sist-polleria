<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - Orden #{{ $orden->idOrdenAbastecimiento }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .container {
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #f97316;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #f97316;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-section h2 {
            color: #f97316;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 2px solid #fed7aa;
            padding-bottom: 5px;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 5px 10px 5px 0;
            width: 30%;
        }

        .info-value {
            display: table-cell;
            padding: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f97316;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        tr:hover {
            background-color: #fef3c7;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totales {
            margin-top: 30px;
            float: right;
            width: 300px;
        }

        .total-row {
            display: table;
            width: 100%;
            padding: 8px 0;
        }

        .total-label {
            display: table-cell;
            font-weight: bold;
            text-align: right;
            padding-right: 20px;
        }

        .total-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
        }

        .total-final {
            border-top: 3px solid #f97316;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
            color: #f97316;
        }

        .footer {
            clear: both;
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #10b981;
            color: white;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Don Pollón</h1>
            <p>Sabor Tradicional Peruano</p>
            <p style="margin-top: 10px; font-size: 18px; font-weight: bold;">FACTURA DE ORDEN DE COMPRA</p>
            <p>Orden N° {{ str_pad($orden->idOrdenAbastecimiento, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        <!-- Información de la Orden -->
        <div class="info-section">
            <h2>Información de la Orden</h2>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Fecha:</div>
                    <div class="info-value">{{ $orden->fechaOrdenAbastecimiento->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Estado:</div>
                    <div class="info-value">
                        <span class="badge">{{ $orden->estadoOrdenAbastecimiento }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jefe de Abastecimiento:</div>
                    <div class="info-value">
                        {{ $orden->jefeAbastecimiento->nombreEmpleado ?? 'N/A' }}
                        {{ $orden->jefeAbastecimiento->apellidoEmpleado ?? '' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Proveedor -->
        <div class="info-section">
            <h2>Información del Proveedor</h2>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Razón Social:</div>
                    <div class="info-value">{{ $orden->proveedor->razonSocialProveedor }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">RUC:</div>
                    <div class="info-value">{{ $orden->proveedor->rucProveedor }}</div>
                </div>
                @if($orden->proveedor->contacto)
                    <div class="info-row">
                        <div class="info-label">Teléfono:</div>
                        <div class="info-value">{{ $orden->proveedor->contacto->celularContactoProveedor }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">{{ $orden->proveedor->contacto->emailContactoProveedor }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detalle de Insumos -->
        <div class="info-section">
            <h2>Detalle de Insumos</h2>
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">#</th>
                        <th style="width: 42%;">Insumo</th>
                        <th style="width: 15%;" class="text-center">Cantidad</th>
                        <th style="width: 15%;" class="text-right">Precio Unit.</th>
                        <th style="width: 20%;" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orden->detalles as $detalle)
                        <tr>
                            <td class="text-center">{{ $detalle->nroDetalleAbastecimiento }}</td>
                            <td>{{ $detalle->insumo->nombreInsumo }}</td>
                            <td class="text-center">{{ $detalle->cantidadInsumo }}</td>
                            <td class="text-right">S/ {{ number_format($detalle->precioInsumo, 2) }}</td>
                            <td class="text-right">S/ {{ number_format($detalle->cantidadInsumo * $detalle->precioInsumo, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totales -->
        <div class="totales">
            <div class="total-row total-final" style="border-bottom: 3px solid #f97316; padding-bottom: 10px; margin-bottom: 10px;">
                <div class="total-label">TOTAL (con IGV):</div>
                <div class="total-value">S/ {{ number_format($total, 2) }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-value">S/ {{ number_format($subtotal, 2) }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">IGV (18%):</div>
                <div class="total-value">S/ {{ number_format($igv, 2) }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Este documento es una representación impresa de la Orden de Compra #{{ $orden->idOrdenAbastecimiento }}</p>
            <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
            <p style="margin-top: 10px;">🇵🇪 Don Pollón - Sabor Tradicional Peruano 🇵🇪</p>
        </div>
    </div>
</body>
</html>
