<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $esBoleta ? 'Boleta' : 'Factura' }} - {{ $numeroComprobante }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
        }
        .container {
            width: 100%;
            padding: 10px;
        }
        .header {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 15px;
        }
        .header-content {
            display: table;
            width: 100%;
        }
        .logo-section {
            display: table-cell;
            width: 28%;
            vertical-align: middle;
            text-align: center;
        }
        .logo {
            width: 100px;
            height: auto;
        }
        .company-info {
            display: table-cell;
            width: 44%;
            vertical-align: middle;
            padding: 0 10px;
            text-align: center;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #d32f2f;
            margin-bottom: 4px;
        }
        .company-details {
            font-size: 9px;
            line-height: 1.4;
        }
        .comprobante-box {
            display: table-cell;
            width: 28%;
            vertical-align: middle;
            text-align: center;
        }
        .comprobante-border {
            border: 2px solid #000;
            padding: 8px;
            background-color: #f5f5f5;
        }
        .comprobante-type {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .comprobante-number {
            font-size: 11px;
            font-weight: bold;
            color: #d32f2f;
        }
        .client-info {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 12px;
            background-color: #fafafa;
        }
        .info-row {
            margin-bottom: 4px;
            line-height: 1.5;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .products-table th {
            background-color: #333;
            color: #fff;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            font-size: 9px;
        }
        .products-table td {
            padding: 5px 4px;
            border: 1px solid #000;
            text-align: center;
            font-size: 9px;
        }
        .products-table .text-left {
            text-align: left;
        }
        .products-table .text-right {
            text-align: right;
        }
        .totals-section {
            float: right;
            width: 250px;
            margin-top: 8px;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        .total-label {
            display: table-cell;
            font-weight: bold;
            text-align: right;
            padding-right: 12px;
            font-size: 10px;
        }
        .total-value {
            display: table-cell;
            text-align: right;
            font-weight: bold;
            width: 80px;
            font-size: 10px;
        }
        .grand-total {
            border-top: 2px solid #000;
            padding-top: 4px;
            margin-top: 4px;
            font-size: 11px;
        }
        .footer {
            clear: both;
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .payment-info {
            margin-top: 12px;
            border: 1px solid #000;
            padding: 8px;
            background-color: #f9f9f9;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <div class="header-content">
                <div class="logo-section">
                    <!-- Logo (puedes agregar la ruta del logo aquí) -->
                    <div style="width: 100px; height: 100px; border: 2px solid #d32f2f; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                        <div style="text-align: center;">
                            <div style="font-size: 20px; font-weight: bold; color: #d32f2f;">DON</div>
                            <div style="font-size: 16px; font-weight: bold; color: #d32f2f;">POLLÓN</div>
                        </div>
                    </div>
                </div>
                <div class="company-info">
                    <div class="company-name">POLLERÍA DON POLLÓN</div>
                    <div class="company-details">
                        <strong>RUC:</strong> 20123456789<br>
                        <strong>Dirección:</strong> Av. Principal 123, Lima - Perú<br>
                        <strong>Teléfono:</strong> (01) 234-5678<br>
                        <strong>Email:</strong> contacto@donpollon.com
                    </div>
                </div>
                <div class="comprobante-box">
                    <div class="comprobante-border">
                        <div class="comprobante-type">
                            {{ $esBoleta ? 'BOLETA DE VENTA' : 'FACTURA ELECTRÓNICA' }}
                        </div>
                        <div style="font-size: 11px; margin: 3px 0;">ELECTRÓNICA</div>
                        <div class="comprobante-number">{{ $numeroComprobante }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="client-info">
            <div style="font-weight: bold; margin-bottom: 8px; font-size: 12px; text-transform: uppercase;">
                Datos del Cliente
            </div>
            @if($esFactura)
                <div class="info-row">
                    <span class="info-label">Razón Social:</span>
                    {{ $cliente->razonSocial ?? 'N/A' }}
                </div>
                <div class="info-row">
                    <span class="info-label">RUC:</span>
                    {{ $cliente->RUC ?? 'N/A' }}
                </div>
            @else
                <div class="info-row">
                    <span class="info-label">Nombre:</span>
                    {{ $cliente->nombreCliente ?? '' }} {{ $cliente->apellidoCliente ?? '' }}
                </div>
                <div class="info-row">
                    <span class="info-label">DNI:</span>
                    {{ $cliente->dniCliente ?? 'N/A' }}
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                {{ $cliente->direccion ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                {{ $cliente->celularCliente ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Fecha de Emisión:</span>
                {{ \Carbon\Carbon::parse($pedido->fechaPedido)->format('d/m/Y H:i') }}
            </div>
            @if($pedido->mesa)
                <div class="info-row">
                    <span class="info-label">Mesa:</span>
                    Mesa N° {{ $pedido->mesa->nroMesa }}
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">Tipo de Pago:</span>
                {{ $pago->tipoPago->nombreTipoPagoPedido ?? 'N/A' }}
            </div>
            <div class="info-row">
                <span class="info-label">Nro. Operación:</span>
                {{ $pago->nroOperacion }}
            </div>
        </div>

        <!-- Tabla de Productos -->
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width: 7%;">CANT.</th>
                    <th style="width: 10%;">CÓDIGO</th>
                    <th style="width: 50%;">DESCRIPCIÓN</th>
                    <th style="width: 15%;">P. UNIT.</th>
                    <th style="width: 18%;">IMPORTE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $detalle)
                    <tr>
                        <td>{{ $detalle->cantidadProductoPedido }}</td>
                        <td>{{ str_pad($detalle->producto->idProducto, 6, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-left">{{ $detalle->producto->nombreProducto }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->precioUnitarioProductoPedido, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($detalle->cantidadProductoPedido * $detalle->precioUnitarioProductoPedido, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totals-section">
            @if($esFactura)
                @php
                    $subtotal = $pago->monto - $pago->IGV;
                @endphp
                <div class="total-row">
                    <div class="total-label">SUBTOTAL:</div>
                    <div class="total-value">S/ {{ number_format($subtotal, 2) }}</div>
                </div>
                <div class="total-row">
                    <div class="total-label">IGV (18%):</div>
                    <div class="total-value">S/ {{ number_format($pago->IGV, 2) }}</div>
                </div>
            @endif
            <div class="total-row grand-total">
                <div class="total-label">TOTAL A PAGAR:</div>
                <div class="total-value">S/ {{ number_format($pago->monto, 2) }}</div>
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Información de Pago -->
        <div class="payment-info">
            <div style="font-weight: bold; margin-bottom: 5px;">INFORMACIÓN DEL PAGO</div>
            <div class="info-row">
                <span class="info-label">Monto Total:</span>
                S/ {{ number_format($pago->monto, 2) }}
            </div>
            <div class="info-row">
                <span class="info-label">Monto Recibido:</span>
                S/ {{ number_format($pago->recibido, 2) }}
            </div>
            <div class="info-row">
                <span class="info-label">Vuelto:</span>
                S/ {{ number_format($pago->vuelto, 2) }}
            </div>
        </div>

        <!-- Pie de Página -->
        <div class="footer">
            <p><strong>¡Gracias por su preferencia!</strong></p>
            <p>Este documento es una representación impresa de un comprobante electrónico</p>
            <p>Consulte su documento en: www.donpollon.com/consulta</p>
            <p style="margin-top: 10px; font-size: 9px;">
                Documento generado el {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>
