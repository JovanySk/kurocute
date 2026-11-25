<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Mensaje de contacto</title>
</head>
<body style="margin:0; padding:0; background:#f3ecfb; font-family:'Segoe UI',Helvetica,Arial,sans-serif; color:#2a1838;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3ecfb;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;">
                    <tr>
                        <td style="padding:0 8px 18px;">
                            <table role="presentation" cellpadding="0" cellspacing="0"><tr>
                                <td style="width:44px;"><img src="{{ isset($message) ? $message->embed(public_path('images/ima6.jpg')) : asset('images/ima6.jpg') }}" width="44" height="44" alt="KuroCute" style="display:block; width:44px; height:44px; border-radius:50%; border:3px solid #2a1838; object-fit:cover;"></td>
                                <td style="padding-left:10px; font-size:22px; font-weight:800; color:#2a1838;">Kuro<span style="color:#ff4fa3;">Cute</span></td>
                            </tr></table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background:#ffffff; border:3px solid #2a1838; border-radius:24px; box-shadow:5px 5px 0 #2a1838; padding:28px;">
                            <p style="margin:0; display:inline-block; padding:4px 12px; border-radius:999px; background:#efe2fb; color:#6a0dad; font-size:13px; font-weight:700;">{{ $datos['asunto_nombre'] }}</p>
                            <h1 style="margin:14px 0 4px; font-size:24px; line-height:1.25;">Nuevo mensaje de {{ $datos['nombre'] }}</h1>
                            <p style="margin:0; color:#5d4a70; font-size:14px;">Responde a este correo para contestarle a <a href="mailto:{{ $datos['email'] }}" style="color:#7b4397; font-weight:700;">{{ $datos['email'] }}</a>.</p>
                            @if (! empty($datos['folio']))
                                <p style="margin:14px 0 0; font-size:14px;"><strong>Folio de pedido:</strong> {{ $datos['folio'] }}</p>
                            @endif
                            <div style="margin-top:18px; padding:16px 18px; border-radius:16px; background:#faf5ff; border:2px solid #e3d3f5; font-size:15px; line-height:1.6; white-space:pre-line;">{{ $datos['mensaje'] }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 8px 0; font-size:12px; color:#8b7a9c;">Enviado desde el formulario de contacto de KuroCute.</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
