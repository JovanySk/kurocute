<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $factura->numero }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #f7f7f7;
            color: #333;
            padding: 30px;
        }
        .factura-box {
            background: #fff;
            padding: 24px 32px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            max-width: 700px;
            margin: auto;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 0;
        }
        .datos {
            margin-bottom: 18px;
        }
        .label {
            font-weight: bold;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }
        th, td {
            border: 1px solid #e1e1e1;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .total {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            color: #2c3e50;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="factura-box">
        <h1>Factura #{{ $factura->numero }}</h1>
        <div class="datos">
            <p><span class="label">Cliente:</span> {{ $cliente->nombre }}</p>
            <p><span class="label">Estado:</span> {{ $estado }}</p>
            <p><span class="label">Forma de Pago:</span> {{ $formaPago }}</p>
            <p><span class="label">Fecha:</span> {{ \Carbon\Carbon::parse($factura->fecha)->format('d/m/Y') }}</p>
        </div>

        @if(isset($factura->detalles) && count($factura->detalles))
        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($factura->detalles as $detalle)
                <tr>
                    <td>{{ $detalle->descripcion }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                    <td>${{ number_format($detalle->cantidad * $detalle->precio_unitario, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <p class="total">Total: ${{ number_format($factura->total, 2) }}</p>

        <div class="footer">
            <p>Gracias por su compra - VYHZ</p>
        </div>
    </div>
</body>
</html>
