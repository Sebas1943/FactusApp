<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $factura['bill']['number'] ?? 'Desconocida' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-inline-size: 1200px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .invoice-header {
            text-align: center;
            margin-block-end: 20px;
        }
        .company-logo {
            max-inline-size: 120px;
            margin-block-end: 10px;
        }
        .invoice-title {
            font-size: 1.8rem;
            font-weight: bold;
        }
        .invoice-section {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-block-end: 15px;
        }
        .table thead {
            background-color: #007bff;
            color: white;
        }
        .total-section {
            text-align: end;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .qr-section {
            text-align: center;
            margin-block-start: 20px;
        }
        .qr-image {
            max-inline-size: 200px;
            margin-block-end: 10px;
        }
        .btn-print {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Encabezado de la Factura -->
    <div class="invoice-header">
        <img src="{{ $factura['company']['url_logo'] ?? '' }}" class="company-logo" alt="Logo Empresa">
        <h2 class="invoice-title">Factura Electrónica</h2>
        <h4>#{{ $factura['bill']['number'] ?? 'Desconocida' }}</h4>
    </div>

    <!-- Información de la Empresa -->
    <div class="invoice-section">
        <h5>Datos de la Empresa</h5>
        <p><strong>Nombre:</strong> {{ $factura['company']['name'] ?? 'N/A' }}</p>
        <p><strong>NIT:</strong> {{ $factura['company']['nit'] ?? 'N/A' }}</p>
        <p><strong>Teléfono:</strong> {{ $factura['company']['phone'] ?? 'N/A' }}</p>
        <p><strong>Dirección:</strong> {{ $factura['company']['direction'] ?? 'N/A' }}</p>
    </div>

    <!-- Información del Cliente -->
    <div class="invoice-section">
        <h5>Datos del Cliente</h5>
        <p><strong>Nombre:</strong> {{ $factura['customer']['names'] ?? 'N/A' }}</p>
        <p><strong>Identificación:</strong> {{ $factura['customer']['identification'] ?? 'N/A' }}</p>
        <p><strong>Email:</strong> {{ $factura['customer']['email'] ?? 'N/A' }}</p>
        <p><strong>Dirección:</strong> {{ $factura['customer']['address'] ?? 'N/A' }}</p>
    </div>

    <!-- Tabla de Productos -->
    <div class="invoice-section">
        <h5>Detalles de la Compra</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($factura['items'] ?? [] as $producto)
                <tr>
                    <td>{{ $producto['name'] ?? 'N/A' }}</td>
                    <td>{{ $producto['quantity'] ?? '0' }}</td>
                    <td>${{ number_format($producto['price'] ?? 0, 2) }}</td>
                    <td>${{ number_format(($producto['quantity'] ?? 0) * ($producto['price'] ?? 0), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Resumen de la Factura -->
    <div class="invoice-section total-section">
        <p>Subtotal: ${{ number_format($factura['bill']['taxable_amount'] ?? 0, 2) }}</p>
        <p>IVA: ${{ number_format($factura['bill']['tax_amount'] ?? 0, 2) }}</p>
        <p>Total a Pagar: ${{ number_format($factura['bill']['total'] ?? 0, 2) }}</p>
        <p><strong>Estado:</strong> 
            <span class="badge {{ isset($factura['bill']['validated']) ? 'bg-success' : 'bg-warning' }}">
                {{ isset($factura['bill']['validated']) ? 'Validada' : 'Pendiente' }}
            </span>
        </p>
    </div>

    <!-- QR y CUFE -->
    <div class="invoice-section qr-section">
        <h5>Verificación de la Factura</h5>
        <img src="{{ $factura['bill']['qr_image'] ?? '' }}" class="qr-image" alt="QR Factura">
        <p><strong>CUFE:</strong> {{ $factura['bill']['cufe'] ?? 'No disponible' }}</p>
        <p><strong>Consulta en la DIAN:</strong> <a href="{{ $factura['bill']['qr'] ?? '#' }}" target="_blank">Ver en la DIAN</a></p>
    </div>

    <!-- Botón para Imprimir -->
    <div class="text-center mt-4">
        <a href="javascript:window.print()" class="btn-print">🖨️ Imprimir Factura</a>
    </div>
</div>

</body>
</html>
