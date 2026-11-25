<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura {{ $factura->numero }}</title>
    @php
        $logo = 'data:image/svg+xml;base64,'.base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><rect width="40" height="40" rx="12" fill="#2a1740"/><path d="M24.6 10.2a10 10 0 1 0 6.1 15.6A8.2 8.2 0 0 1 24.6 10.2z" fill="#c9b2f5"/><path d="M27.5 9.5c.4 2.6 1.4 3.6 4 4-2.6.4-3.6 1.4-4 4-.4-2.6-1.4-3.6-4-4 2.6-.4 3.6-1.4 4-4z" fill="#f06aa8"/></svg>');
        $colores = [
            'pagada' => ['#e3f5ec', '#1f8a5e'],
            'pendiente' => ['#fdf1dc', '#9a5d06'],
            'vencida' => ['#fde6ea', '#c8384f'],
            'cancelada' => ['#efedf2', '#6b6478'],
        ][$factura->estado?->clave] ?? ['#efedf2', '#6b6478'];
        $m = fn ($v) => '$'.number_format((float) $v, 2);
    @endphp
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'DejaVu Sans', sans-serif; font-size: 9.5pt; color: #1b1124; line-height: 1.45; }
        .banda { background: #140d1c; color: #ffffff; padding: 34px 48px 30px; }
        .banda td { vertical-align: middle; }
        .marca { font-size: 17pt; font-weight: bold; letter-spacing: -0.3px; }
        .marca span { color: #f06aa8; }
        .tenue { color: #a99bb9; font-size: 8pt; }
        .etiqueta { font-size: 7pt; letter-spacing: 2px; text-transform: uppercase; color: #8a7f98; font-weight: bold; }
        .banda .etiqueta { color: #a99bb9; }
        .folio { font-size: 19pt; font-weight: bold; margin-top: 2px; }
        .pastilla { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 7.5pt; font-weight: bold; background: {{ $colores[0] }}; color: {{ $colores[1] }}; margin-top: 6px; }
        .cuerpo { padding: 30px 48px 0; }
        table { width: 100%; border-collapse: collapse; }
        .datos td { vertical-align: top; width: 33.33%; padding-right: 18px; }
        .datos p { margin: 4px 0 0; }
        .fuerte { font-weight: bold; font-size: 10pt; }
        .gris { color: #5b4d6b; }
        .conceptos { margin-top: 26px; }
        .conceptos th { background: #efe7fa; color: #4c2a78; font-size: 7pt; letter-spacing: 1.5px; text-transform: uppercase; text-align: left; padding: 9px 12px; }
        .conceptos td { padding: 11px 12px; border-bottom: 1px solid #ece6f3; }
        .der { text-align: right; }
        .totales { margin-top: 18px; }
        .totales td { padding: 5px 12px; }
        .caja-total { background: #140d1c; color: #ffffff; border-radius: 12px; }
        .caja-total td { padding: 12px 14px; }
        .monto-total { font-size: 16pt; font-weight: bold; color: #f06aa8; }
        .notas { margin-top: 26px; padding: 14px 16px; background: #faf7fd; border-radius: 10px; border: 1px solid #ece6f3; }
        .pie { position: fixed; bottom: 0; left: 0; right: 0; padding: 18px 48px 24px; border-top: 1px solid #ece6f3; font-size: 7.5pt; color: #8a7f98; }
        .franja { position: fixed; bottom: 0; left: 0; right: 0; height: 6px; background: #e64c95; }
    </style>
</head>
<body>
    <div class="banda">
        <table>
            <tr>
                <td style="width: 55%">
                    <table style="width: auto"><tr>
                        <td style="width: 44px"><img src="{{ $logo }}" width="38" height="38" alt=""></td>
                        <td style="padding-left: 10px">
                            <div class="marca">kuro<span>cute</span></div>
                            <div class="tenue">KUROCUTE TIENDA DEMO &middot; RFC KCU250101AB1</div>
                        </td>
                    </tr></table>
                </td>
                <td class="der">
                    <div class="etiqueta">Factura</div>
                    <div class="folio">{{ $factura->numero }}</div>
                    <div class="pastilla">{{ mb_strtoupper($factura->estado?->nombre ?? 'Sin estado') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="cuerpo">
        <table class="datos">
            <tr>
                <td>
                    <div class="etiqueta">Facturar a</div>
                    <p class="fuerte">{{ $factura->cliente->nombre }}</p>
                    <p class="gris">RFC {{ $factura->cliente->rfc ?? 'XAXX010101000' }}</p>
                    <p class="gris">{{ $factura->cliente->direccion }}</p>
                    <p class="gris">{{ $factura->cliente->email }}</p>
                </td>
                <td>
                    <div class="etiqueta">Fechas</div>
                    <p><span class="gris">Emisión:</span> {{ $factura->fecha_emision->format('d/m/Y') }}</p>
                    <p><span class="gris">Vencimiento:</span> {{ $factura->fecha_vencimiento?->format('d/m/Y') ?? 'N/A' }}</p>
                    @if ($factura->pedido)
                        <p><span class="gris">Pedido:</span> {{ $factura->pedido->folio }}</p>
                    @endif
                </td>
                <td>
                    <div class="etiqueta">Pago</div>
                    <p><span class="gris">Forma:</span> {{ $factura->formaPago?->nombre }}</p>
                    <p><span class="gris">Moneda:</span> MXN</p>
                    <p><span class="gris">Uso CFDI:</span> G03 Gastos en general</p>
                </td>
            </tr>
        </table>

        <table class="conceptos">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th class="der" style="width: 60px">Cant.</th>
                    <th class="der" style="width: 110px">P. unitario</th>
                    <th class="der" style="width: 110px">Importe</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($conceptos as $c)
                    <tr>
                        <td>{{ $c['descripcion'] }}</td>
                        <td class="der">{{ $c['cantidad'] }}</td>
                        <td class="der">{{ $m($c['unitario']) }}</td>
                        <td class="der">{{ $m($c['importe']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totales">
            <tr>
                <td style="width: 58%; vertical-align: top; padding-left: 0">
                    <div class="etiqueta">Condiciones</div>
                    <p class="gris" style="margin: 4px 0 0">Pago a {{ $factura->fecha_vencimiento ? (int) $factura->fecha_emision->diffInDays($factura->fecha_vencimiento) : 0 }} días. Importes en pesos mexicanos.<br>Transferencias a la cuenta indicada en tu pedido.</p>
                </td>
                <td style="padding: 0">
                    <table>
                        <tr><td class="gris">Subtotal</td><td class="der">{{ $m($factura->subtotal) }}</td></tr>
                        <tr><td class="gris">IVA 16%</td><td class="der">{{ $m($factura->iva) }}</td></tr>
                    </table>
                    <table class="caja-total" style="margin-top: 8px">
                        <tr><td style="font-weight: bold">Total MXN</td><td class="der monto-total">{{ $m($factura->total) }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        @if ($factura->detalles && ! $factura->pedido)
            <div class="notas">
                <div class="etiqueta">Notas</div>
                <p class="gris" style="margin: 4px 0 0">{{ strip_tags($factura->detalles) }}</p>
            </div>
        @endif
    </div>

    <div class="pie">
        <table>
            <tr>
                <td>Gracias por tu compra. Dudas sobre esta factura: hola@example.com</td>
                <td class="der">Representación impresa de demostración, sin validez fiscal.</td>
            </tr>
        </table>
    </div>
    <div class="franja"></div>
</body>
</html>
