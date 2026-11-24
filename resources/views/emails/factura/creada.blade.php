<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Tu Nueva Factura de KuroCute!</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f0fc; /* Un lila muy claro, inspirado en Kuromi */
            color: #333333;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px; /* Bordes más redondeados */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); /* Sombra más pronunciada */
            overflow: hidden;
            border: 1px solid #e0cffc; /* Borde sutil a juego */
        }
        .header {
            background-color: #7b4397; /* Un morado oscuro, color característico de Kuromi */
            padding: 30px 20px;
            color: #ffffff;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2); /* Sombra de texto sutil */
        }
        .content {
            padding: 25px 35px;
            line-height: 1.6;
            font-size: 15px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .section-title {
            font-size: 19px;
            font-weight: bold;
            color: #7b4397; /* Color principal de la marca */
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 2px solid #e0cffc; /* Borde más grueso y a juego */
            padding-bottom: 8px;
        }
        .table-factura {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fefefe;
        }
        .table-factura th,
        .table-factura td {
            padding: 14px 18px; /* Más padding */
            border-bottom: 1px solid #f0e6fa; /* Bordes más suaves */
            text-align: left;
        }
        .table-factura th {
            background-color: #f3ecfb; /* Fondo lila muy claro para encabezados */
            font-weight: bold;
            color: #5a3270; /* Texto más oscuro */
            font-size: 14px;
        }
        .table-factura tr:last-child td {
            border-bottom: none;
        }
        .footer {
            background-color: #f3ecfb;
            padding: 20px;
            font-size: 12px;
            color: #888888;
            text-align: center;
            border-top: 1px solid #e0cffc;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        .accent-text {
            color: #7b4397; /* Texto de acento para la marca */
            font-weight: bold;
        }
        .button {
            display: inline-block;
            background-color: #7b4397; /* Botón a juego con el color principal */
            color: #ffffff;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s ease; /* Transición sutil para el botón */
        }
        .button:hover {
            background-color: #5a3270; /* Morado más oscuro al pasar el ratón */
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🖤 KuroCute 🖤</h1>
            <p style="font-size: 16px; margin-top: 10px; font-weight: normal;">¡Tu universo de Kuromi!</p>
        </div>

        <div class="content">
            <p>Hola,</p>

            <p>¡Aquí está tu nueva factura de <span class="accent-text">KuroCute</span>! Esperamos que disfrutes de tus nuevos artículos de Kuromi.</p>
            <p>Puedes encontrar los detalles de tu compra a continuación:</p>

            <div class="section-title">Detalles de tu Pedido</div>

            <table class="table-factura">
                <tr>
                    <th>Número de Factura:</th>
                    <td>{{ $factura->numero }}</td>
                </tr>
                <tr>
                    <th>Cliente:</th>
                    <td>{{ $factura->cliente->nombre ?? 'No disponible' }}</td>
                </tr>
                <tr>
                    <th>Descripción:</th>
                    <td>{!! $factura->detalles !!}</td>
                </tr>
                <tr>
                    <th>Valor Total:</th>
                    <td>${{ number_format($factura->valor, 2) }}</td>
                </tr>
                <tr>
                    <th>Estado del Pago:</th>
                    <td>{{ $factura->estado->nombre ?? 'No disponible' }}</td>
                </tr>
                <tr>
                    <th>Método de Pago:</th>
                    <td>{{ $factura->formaPago->nombre ?? 'No disponible' }}</td>
                </tr>
                <tr>
                    <th>Fecha del Pedido:</th>
                    <td>{{ $factura->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            <p style="margin-top: 30px;">Si tienes alguna pregunta sobre tu pedido o necesitas asistencia, no dudes en contactarnos. ¡Estamos aquí para ayudarte!</p>

            {{-- Opcional: Botón para ver la factura en línea (descomentar si tienes una ruta para esto) --}}
            {{-- <p style="text-align: center;">
                <a href="{{ route('facturas.show', $factura->id) }}" class="button">Ver Mi Factura Online</a>
            </p> --}}
            
            <p>¡Gracias por ser parte de la familia <span class="accent-text">KuroCute</span>!</p>
            <p>Con cariño,<br>El equipo de **KuroCute**</p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} KuroCute. Todos los derechos reservados.
            <br>¡La tienda de Kuromi para gente cute!
        </div>
    </div>
</body>
</html>