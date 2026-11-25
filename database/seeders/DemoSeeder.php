<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\EstadoFactura;
use App\Models\Factura;
use App\Models\FormaPago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Support\FacturaPdf;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Datos ficticios para demostrar la tienda y el back office.
 * Todos los nombres son inventados y los correos usan @example.com.
 * Los archivos (avatares, PDF y XML) se generan aquí mismo.
 */
class DemoSeeder extends Seeder
{
    private const CATEGORIAS = [
        'Peluches' => 'Suavecitos, abrazables y un poquito rebeldes.',
        'Papelería' => 'Libretas, plumas y stickers para tus noches creativas.',
        'Accesorios' => 'Detalles para llevar contigo a todas partes.',
        'Hogar' => 'Tu cuarto, pero con más estrellas.',
        'Ropa' => 'Comodidad oversize con actitud.',
        'Coleccionables' => 'Piezas de edición limitada para tu repisa.',
    ];

    private const PRODUCTOS = [
        ['Peluche Luna Traviesa', 'Peluches', 459, 'Peluche de felpa extra suave con forma de media luna y mejillas bordadas. 30 cm.', true],
        ['Peluche Murciélago Bombón', 'Peluches', 389, 'Murcielaguito regordete con alas de terciopelo lila. Ideal para regalar.', true],
        ['Cojín Nube Gótica', 'Peluches', 529, 'Cojín en forma de nube con bordado de estrellas y relleno hipoalergénico.', false],
        ['Llavero Estrellita Rebelde', 'Peluches', 129, 'Mini peluche con argolla metálica para mochila o llaves.', false],
        ['Peluche Gatito Medianoche', 'Peluches', 499, 'Gatito negro de ojos rosados con cascabel. 28 cm.', true],
        ['Libreta Noche Estrellada', 'Papelería', 149, 'Libreta A5 de 160 hojas punteadas con tapa dura y esquinas redondeadas.', true],
        ['Set de Plumas Gel Lavanda', 'Papelería', 119, 'Seis plumas de gel en tonos lavanda, rosa y negro. Punta 0.5 mm.', false],
        ['Agenda 2027 Media Luna', 'Papelería', 289, 'Agenda semanal con calendario lunar, stickers y separador de listón.', false],
        ['Stickers Holográficos Constelación', 'Papelería', 79, 'Plancha de 40 stickers holográficos resistentes al agua.', false],
        ['Washi Tape Corazones Oscuros', 'Papelería', 69, 'Rollo de cinta decorativa de 10 m con corazones y lunas.', false],
        ['Diadema Orejitas de Terciopelo', 'Accesorios', 219, 'Diadema acolchada con orejitas puntiagudas y moño rosa.', true],
        ['Mochila Mini Luna', 'Accesorios', 749, 'Mochila de vinil acolchado con bolsillo frontal y herrajes plateados.', true],
        ['Cartera Corazón Acolchado', 'Accesorios', 339, 'Cartera compacta con cierre y forma de corazón.', false],
        ['Pinzas para Cabello Estrella', 'Accesorios', 99, 'Juego de cuatro pinzas metálicas con esmalte rosa y negro.', false],
        ['Funda para Celular Lazo Negro', 'Accesorios', 259, 'Funda de silicón suave con lazo en relieve. Varios modelos.', false],
        ['Taza Cerámica Luna Rosa', 'Hogar', 189, 'Taza de 350 ml con asa en forma de media luna. Apta para microondas.', true],
        ['Lámpara de Noche Planeta', 'Hogar', 649, 'Lámpara LED recargable con luz cálida y tres niveles de brillo.', true],
        ['Vela Aromática Lavanda Nocturna', 'Hogar', 239, 'Vela de soya en frasco de vidrio ahumado. 40 horas de duración.', false],
        ['Manta Polar Cielo Violeta', 'Hogar', 699, 'Manta de 150 x 200 cm estampada con constelaciones.', false],
        ['Sudadera Oversize Estrellas', 'Ropa', 899, 'Sudadera de algodón perchado con capucha y bordado de estrellas.', true],
        ['Pijama Satinado Media Noche', 'Ropa', 759, 'Conjunto de pijama de satín con ribete rosa. Tallas CH a G.', false],
        ['Calcetas Tobilleras Corazón', 'Ropa', 159, 'Paquete de tres pares de calcetas con corazones tejidos.', false],
        ['Gorro Tejido Orejitas', 'Ropa', 279, 'Gorro de punto con orejitas y forro polar interior.', false],
        ['Figura Vinilo Lunita Edición Noche', 'Coleccionables', 599, 'Figura de vinil de 12 cm numerada a mano. Edición de 500 piezas.', true],
        ['Caja Misteriosa Constelación', 'Coleccionables', 349, 'Caja sorpresa con una de ocho mini figuras. Una es secreta.', true],
        ['Pin Esmaltado Corazón Alado', 'Coleccionables', 89, 'Pin de esmalte duro con acabado brillante y doble poste.', false],
        ['Mini Figura Gatito Brujo', 'Coleccionables', 249, 'Mini figura de resina pintada a mano. 7 cm.', false],
        ['Globo de Nieve Galaxia', 'Coleccionables', 429, 'Globo de nieve con brillos violeta y base de madera.', false],
    ];

    private const CLIENTES = [
        ['Valeria Ortega Ríos', false], ['Mariana Castillo Luna', false], ['Diego Hernández Soto', false],
        ['Ximena Navarro Peña', false], ['Sofía Ramírez Cruz', false], ['Andrea Flores Medina', false],
        ['Fernanda Morales Vega', false], ['Camila Torres Aguilar', false], ['Renata Jiménez Ruiz', false],
        ['Paola Mendoza Salas', false], ['Luis Ángel Domínguez Paz', false], ['Regina Vargas Lozano', false],
        ['Daniela Guerrero Campos', false], ['Natalia Rojas Ibarra', false], ['Emilio Cortés Fuentes', false],
        ['Papelería La Constelación', true], ['Regalos Medianoche', true], ['Bazar Luna Violeta', true],
    ];

    private const PALETA = [[107, 63, 160], [236, 72, 153], [58, 36, 92], [190, 120, 220], [244, 114, 182], [76, 29, 149]];

    public function run(): void
    {
        mt_srand(2026);
        fake()->seed(2026);

        Storage::disk('public')->deleteDirectory('clientes');
        Storage::disk('public')->deleteDirectory('facturas');

        $categorias = collect(self::CATEGORIAS)->map(fn (string $descripcion, string $nombre) => Categoria::create([
            'nombre' => $nombre,
            'slug' => Str::slug($nombre),
            'descripcion' => $descripcion,
        ]));

        $productos = collect(self::PRODUCTOS)->map(fn (array $p) => Producto::create([
            'categoria_id' => $categorias[$p[1]]->id,
            'nombre' => $p[0],
            'slug' => Str::slug($p[0]),
            'descripcion' => $p[3],
            'precio' => $p[2],
            'stock' => mt_rand(0, 9) === 0 ? mt_rand(1, 4) : mt_rand(12, 60),
            'destacado' => $p[4],
            'activo' => true,
        ]));

        $clientes = collect(self::CLIENTES)->values()->map(function (array $c, int $i) {
            [$nombre, $empresa] = $c;
            $cliente = Cliente::create([
                'nombre' => $empresa ? "{$nombre} SA de CV" : $nombre,
                'rfc' => $this->rfc($nombre, $empresa, $i),
                'direccion' => $this->direccion($i),
                'telefono' => '55 01'.str_pad((string) (10 + $i), 2, '0', STR_PAD_LEFT).' '.mt_rand(1000, 9999),
                'email' => Str::slug($nombre, '.').'@example.com',
                'descripcion' => $empresa ? 'Cliente mayorista. Compra para reventa en su tienda física.' : null,
            ]);

            if ($i % 3 !== 2) {
                $cliente->update(['imagen' => $this->avatar($cliente, $i)]);
            }

            return $cliente;
        });

        $this->pedidosYFacturas($clientes, $productos);
    }

    private function pedidosYFacturas($clientes, $productos): void
    {
        $hoy = now();
        $formas = FormaPago::pluck('id')->all();
        $estado = fn (string $clave) => EstadoFactura::idDe($clave);
        $personas = $clientes->take(15);
        $empresas = $clientes->slice(15);
        $porCrear = [];

        // Pedidos en línea a lo largo del último año, con crecimiento en los meses recientes.
        $fechas = collect(range(1, 42))
            ->map(fn () => $hoy->copy()->subDays((int) floor(pow(mt_rand(0, 1000) / 1000, 1.35) * 360))->setTime(mt_rand(9, 21), mt_rand(0, 59)))
            ->push($hoy->copy()->subHours(5), $hoy->copy()->subDay()->setTime(18, 20), $hoy->copy()->subDays(2)->setTime(11, 5))
            ->sort()
            ->values();

        foreach ($fechas as $n => $fecha) {
            $cliente = $personas->random();
            $dias = $fecha->diffInDays($hoy);
            $estadoPedido = match (true) {
                $dias > 3 && mt_rand(1, 14) === 1 => 'cancelado',
                $dias > 20 => 'entregado',
                $dias > 8 => 'enviado',
                $dias > 3 => 'en_proceso',
                default => 'nuevo',
            };

            $pedido = Pedido::create([
                'folio' => 'PED-'.str_pad((string) ($n + 1), 5, '0', STR_PAD_LEFT),
                'cliente_id' => $cliente->id,
                'estado' => $estadoPedido,
                'total' => 0,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);

            foreach ($productos->random(mt_rand(1, 4)) as $producto) {
                $cantidad = mt_rand(1, 3);
                $pedido->items()->create([
                    'producto_id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $producto->precio,
                    'subtotal' => round((float) $producto->precio * $cantidad, 2),
                ]);
            }

            $pedido->update(['total' => $pedido->items()->sum('subtotal')]);

            if ($estadoPedido === 'cancelado' || $estadoPedido === 'nuevo') {
                continue;
            }

            $emision = $fecha->copy()->addDays(mt_rand(0, 2))->min($hoy);
            $porCrear[] = ([
                'cliente_id' => $cliente->id,
                'pedido_id' => $pedido->id,
                'forma_pago_id' => $formas[array_rand($formas)],
                'fecha_emision' => $emision,
                'detalles' => "Pedido en línea {$pedido->folio}",
                'estado_id' => $estado($this->estadoPorAntiguedad($emision, $hoy)),
            ] + Factura::importesDesdeTotal((float) $pedido->total));
        }

        // Ventas de mayoreo facturadas directamente.
        foreach (range(1, 14) as $i) {
            $emision = $hoy->copy()->subDays($i <= 3 ? mt_rand(1, 12) : mt_rand(2, 330))->startOfDay();
            $clave = match (true) {
                $i <= 2 => EstadoFactura::PENDIENTE,
                mt_rand(1, 12) === 1 => EstadoFactura::CANCELADA,
                default => $this->estadoPorAntiguedad($emision, $hoy),
            };
            $porCrear[] = ([
                'cliente_id' => $empresas->random()->id,
                'forma_pago_id' => $formas[0],
                'fecha_emision' => $emision,
                'detalles' => 'Venta de mayoreo: surtido de '.collect(['peluches', 'papelería', 'accesorios', 'coleccionables'])->random(2)->implode(' y ').' para reventa.',
                'estado_id' => $estado($clave),
            ] + Factura::importesDesdeSubtotal(mt_rand(180, 1450) * 10));
        }

        // Mes en curso realista: cobros pagados este mes con una variacion creible frente al mes anterior.
        $pagada = $estado(EstadoFactura::PAGADA);
        $cobradoEn = fn (Carbon $mes) => collect($porCrear)
            ->filter(fn (array $f) => $f['estado_id'] === $pagada && $f['fecha_emision']->isSameMonth($mes))
            ->sum('total');
        $anterior = $cobradoEn($hoy->copy()->subMonthNoOverflow()) ?: 24000;
        $faltante = $anterior * mt_rand(108, 122) / 100 - $cobradoEn($hoy);

        for ($k = 0; $faltante > 500 && $k < 6; $k++) {
            $total = $k === 5 ? $faltante : min($faltante, mt_rand(25, 70) * 100);
            $faltante -= $total;
            $porCrear[] = [
                'cliente_id' => ($k % 2 ? $personas : $empresas)->random()->id,
                'forma_pago_id' => $formas[array_rand($formas)],
                'fecha_emision' => $hoy->copy()->startOfMonth()->addDays(mt_rand(0, max(0, $hoy->day - 1)))->startOfDay(),
                'detalles' => 'Venta de mostrador y mayoreo del mes.',
                'estado_id' => $pagada,
            ] + Factura::importesDesdeTotal(round($total, 2));
        }

        // Se crean en orden cronológico para que la numeracion KC-AAAA-0001 sea consecutiva.
        collect($porCrear)->sortBy(fn (array $f) => $f['fecha_emision']->timestamp)->each(fn (array $f) => $this->factura($f));
    }

    private function estadoPorAntiguedad(Carbon $emision, Carbon $hoy): string
    {
        $dias = $emision->diffInDays($hoy);

        return match (true) {
            $dias > 30 => mt_rand(1, 9) === 1 ? EstadoFactura::VENCIDA : EstadoFactura::PAGADA,
            $dias > 15 => mt_rand(1, 3) === 1 ? EstadoFactura::VENCIDA : EstadoFactura::PAGADA,
            default => mt_rand(1, 3) === 1 ? EstadoFactura::PAGADA : EstadoFactura::PENDIENTE,
        };
    }

    private function factura(array $datos): void
    {
        $emision = $datos['fecha_emision'];
        $factura = Factura::create($datos + [
            'numero' => Factura::siguienteNumero((int) $emision->format('Y')),
            'fecha_vencimiento' => $emision->copy()->addDays(15),
            'created_at' => $emision,
            'updated_at' => $emision,
        ]);

        // Comprobantes de ejemplo: PDF generado con dompdf y XML con estructura tipo CFDI 4.0.
        $base = 'facturas/'.$factura->numero;
        Storage::disk('public')->put("{$base}.pdf", FacturaPdf::crear($factura)->output());
        Storage::disk('public')->put("{$base}.xml", $this->xml($factura));
        $factura->update(['archivo_pdf' => "{$base}.pdf", 'archivo_xml' => "{$base}.xml"]);
    }

    private function xml(Factura $factura): string
    {
        $factura->loadMissing('cliente');
        $uuid = Str::uuid();
        $fecha = $factura->fecha_emision->format('Y-m-d\T12:00:00');
        $nombre = htmlspecialchars($factura->cliente->nombre, ENT_XML1);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<!-- Documento de demostración generado por el seeder. Sin validez fiscal. -->
<cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/4" Version="4.0" Serie="KC" Folio="{$factura->numero}" Fecha="{$fecha}" SubTotal="{$factura->subtotal}" Total="{$factura->total}" Moneda="MXN" TipoDeComprobante="I" Exportacion="01" LugarExpedicion="06000">
  <cfdi:Emisor Rfc="KCU250101AB1" Nombre="KUROCUTE TIENDA DEMO" RegimenFiscal="626"/>
  <cfdi:Receptor Rfc="{$factura->cliente->rfc}" Nombre="{$nombre}" UsoCFDI="G03" DomicilioFiscalReceptor="06000" RegimenFiscalReceptor="616"/>
  <cfdi:Impuestos TotalImpuestosTrasladados="{$factura->iva}"/>
  <cfdi:Complemento>
    <tfd:TimbreFiscalDigital xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" Version="1.1" UUID="{$uuid}" FechaTimbrado="{$fecha}"/>
  </cfdi:Complemento>
</cfdi:Comprobante>
XML;
    }

    /** Direcciones inventadas (calles con nombres tematicos). */
    private function direccion(int $i): string
    {
        $calles = ['Av. de las Estrellas', 'Calle Luna', 'Calle Cometa', 'Av. Constelación', 'Calle Aurora', 'Privada Eclipse', 'Calle Lucero', 'Av. Nebulosa', 'Calle Orión'];
        $ciudades = ['Ciudad de México', 'Guadalajara, Jal.', 'Puebla, Pue.', 'Tuxpan, Ver.', 'Monterrey, N.L.', 'Querétaro, Qro.', 'Tantoyuca, Ver.'];

        return $calles[$i % count($calles)].' '.mt_rand(12, 980).', Col. Centro, '.$ciudades[$i % count($ciudades)];
    }

    private function rfc(string $nombre, bool $empresa, int $i): string
    {
        $letras = strtoupper(Str::ascii(collect(explode(' ', $nombre))->map(fn ($p) => $p[0])->implode('')));
        $letras = str_pad(substr($letras, 0, $empresa ? 3 : 4), $empresa ? 3 : 4, 'X');
        $fecha = sprintf('%02d%02d%02d', 80 + ($i % 20), 1 + ($i % 12), 1 + ($i % 28));

        return $letras.$fecha.strtoupper(substr(md5($nombre), 0, 3));
    }

    /** Avatar PNG con iniciales, generado con GD (no son fotos reales). */
    private function avatar(Cliente $cliente, int $i): string
    {
        $tam = 256;
        $img = imagecreatetruecolor($tam, $tam);
        [$r, $g, $b] = self::PALETA[$i % count(self::PALETA)];
        imagefill($img, 0, 0, imagecolorallocate($img, $r, $g, $b));

        $brillo = imagecolorallocatealpha($img, 255, 255, 255, 105);
        imagefilledellipse($img, (int) ($tam * 0.78), (int) ($tam * 0.2), 190, 190, $brillo);
        imagefilledellipse($img, (int) ($tam * 0.15), (int) ($tam * 0.92), 120, 120, imagecolorallocatealpha($img, 15, 10, 25, 100));

        $fuente = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');
        $texto = $cliente->iniciales();
        $caja = imagettfbbox(78, 0, $fuente, $texto);
        $x = (int) (($tam - ($caja[2] - $caja[0])) / 2 - $caja[0]);
        $y = (int) (($tam - ($caja[1] - $caja[7])) / 2 - $caja[7]);
        imagettftext($img, 78, 0, $x, $y, imagecolorallocate($img, 255, 255, 255), $fuente, $texto);

        ob_start();
        imagepng($img);
        $png = ob_get_clean();

        $ruta = 'clientes/avatar-'.$cliente->id.'.png';
        Storage::disk('public')->put($ruta, $png);

        return $ruta;
    }
}
