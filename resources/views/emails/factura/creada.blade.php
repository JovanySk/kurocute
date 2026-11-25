@php
    $m = fn ($v) => '$'.number_format((float) $v, 2);
    $nombre = strtok($factura->cliente->nombre, ' ');
    $pagada = $factura->estado?->clave === 'pagada';
    [$fondoEstado, $textoEstado] = match ($factura->estado?->clave) {
        'pagada' => ['#e3f5ec', '#1f8a5e'],
        'vencida' => ['#fde6ea', '#c8384f'],
        'cancelada' => ['#efedf2', '#6b6478'],
        default => ['#fdf1dc', '#9a5d06'],
    };
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Tu factura {{ $factura->numero }}</title>
</head>
<body style="margin:0; padding:0; background:#f3ecfb; font-family:'Plus Jakarta Sans','Segoe UI',Helvetica,Arial,sans-serif; color:#1b1124; -webkit-text-size-adjust:100%;">
    <div style="display:none; max-height:0; overflow:hidden;">Tu factura {{ $factura->numero }} por {{ $m($factura->total) }} ya está lista. Adjuntamos el PDF.</div>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3ecfb;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;">
                    {{-- Marca --}}
                    <tr>
                        <td style="padding:0 8px 18px;">
                            <table role="presentation" cellpadding="0" cellspacing="0"><tr>
                                <td style="width:44px;"><img src="{{ isset($message) ? $message->embed(public_path('images/ima6.jpg')) : asset('images/ima6.jpg') }}" width="44" height="44" alt="KuroCute" style="display:block; width:44px; height:44px; border-radius:50%; border:3px solid #2a1838; object-fit:cover;"></td>
                                <td style="padding-left:10px; font-size:22px; font-weight:800; letter-spacing:-0.3px; color:#2a1838;">Kuro<span style="color:#ff4fa3;">Cute</span></td>
                            </tr></table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background:#ffffff; border-radius:24px; overflow:hidden; box-shadow:0 18px 40px -20px rgba(76,42,120,0.35);">
                            {{-- Encabezado oscuro --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background:#7b4397; background-image:radial-gradient(circle at 90% 0%, rgba(255,79,163,0.55), rgba(123,67,151,0) 60%); padding:36px 36px 32px; border-radius:24px 24px 0 0; color:#ffffff;">
                                        <p style="margin:0; font-size:11px; letter-spacing:2.5px; text-transform:uppercase; color:#ffe3f1; font-weight:700;">Factura {{ $factura->numero }}</p>
                                        <h1 style="margin:12px 0 0; font-size:26px; line-height:1.2; font-weight:700;">Hola {{ $nombre }}, tu factura ya está lista.</h1>
                                        <p style="margin:12px 0 0; font-size:15px; line-height:1.6; color:#f3e6ff;">Gracias por comprar en KuroCute. Adjuntamos tu factura en PDF{{ $factura->archivo_xml ? ' y el XML timbrado' : '' }}.</p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Total destacado --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding:28px 36px 8px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#faf7fd; border:1px solid #ece6f3; border-radius:18px;">
                                            <tr>
                                                <td style="padding:20px 22px;">
                                                    <p style="margin:0; font-size:12px; color:#8a7f98;">{{ $pagada ? 'Total pagado' : 'Total a pagar' }}</p>
                                                    <p style="margin:4px 0 0; font-size:30px; font-weight:700; color:#7b4397;">{{ $m($factura->total) }} <span style="font-size:13px; font-weight:500; color:#8a7f98;">MXN</span></p>
                                                </td>
                                                <td align="right" style="padding:20px 22px;">
                                                    <p style="margin:0; font-size:12px; color:#8a7f98;">Vence</p>
                                                    <p style="margin:4px 0 0; font-size:15px; font-weight:700;">{{ $factura->fecha_vencimiento?->translatedFormat('j M Y') ?? 'N/A' }}</p>
                                                    <p style="margin:8px 0 0;"><span style="display:inline-block; padding:4px 10px; border-radius:999px; background:{{ $fondoEstado }}; color:{{ $textoEstado }}; font-size:12px; font-weight:700;">{{ $factura->estado?->nombre }}</span></p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Conceptos --}}
                                <tr>
                                    <td style="padding:20px 36px 0;">
                                        <p style="margin:0 0 10px; font-size:11px; letter-spacing:2px; text-transform:uppercase; color:#8a7f98; font-weight:700;">Detalle</p>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
                                            @foreach ($conceptos as $c)
                                                <tr>
                                                    <td style="padding:10px 0; border-bottom:1px solid #ece6f3; color:#1b1124;">{{ $c['descripcion'] }} <span style="color:#8a7f98;">x{{ $c['cantidad'] }}</span></td>
                                                    <td align="right" style="padding:10px 0; border-bottom:1px solid #ece6f3; white-space:nowrap;">{{ $m($c['importe']) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr><td style="padding:10px 0 2px; color:#5b4d6b;">Subtotal</td><td align="right" style="padding:10px 0 2px;">{{ $m($factura->subtotal) }}</td></tr>
                                            <tr><td style="padding:2px 0; color:#5b4d6b;">IVA 16%</td><td align="right" style="padding:2px 0;">{{ $m($factura->iva) }}</td></tr>
                                            <tr><td style="padding:8px 0 0; font-weight:700;">Total</td><td align="right" style="padding:8px 0 0; font-weight:700;">{{ $m($factura->total) }}</td></tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Datos --}}
                                <tr>
                                    <td style="padding:24px 36px 0;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#5b4d6b;">
                                            <tr>
                                                <td style="padding:4px 0;">Fecha de emisión</td><td align="right" style="padding:4px 0; color:#1b1124;">{{ $factura->fecha_emision->translatedFormat('j M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0;">Forma de pago</td><td align="right" style="padding:4px 0; color:#1b1124;">{{ $factura->formaPago?->nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0;">RFC receptor</td><td align="right" style="padding:4px 0; color:#1b1124;">{{ $factura->cliente->rfc ?? 'XAXX010101000' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:28px 36px 36px;">
                                        <a href="{{ route('tienda.catalogo') }}" style="display:inline-block; background:#ff4fa3; color:#ffffff; text-decoration:none; font-weight:700; font-size:14px; padding:13px 24px; border-radius:999px;">Seguir comprando</a>
                                        <p style="margin:20px 0 0; font-size:13px; line-height:1.6; color:#5b4d6b;">Si algo no cuadra, responde a este correo y lo resolvemos.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:22px 16px 0; font-size:12px; line-height:1.6; color:#8a7f98;">
                            KuroCute &middot; Tienda de demostración con datos ficticios<br>
                            Recibes este correo porque se emitió una factura a tu nombre.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
