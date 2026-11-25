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
 * Los clientes son inventados y los correos usan @example.com.
 * Las fotos de producto son las imágenes originales del proyecto (public/images y public/productos);
 * se copian al disco public al sembrar. Los PDF y XML de las facturas se generan aquí mismo.
 */
class DemoSeeder extends Seeder
{
    /** nombre => [descripción, portada] */
    private const CATEGORIAS = [
        'Peluches Kuromi' => ['Peluches suavecitos y figuras coleccionables de Kuromi para tu repisa o tu cama.', 'images/ima4.jpg'],
        'Maquillaje' => ['Labiales, gloss, polvos y brochas para un look tierno o atrevido.', 'images/imagen4.jpg'],
        'Motos' => ['Motocicletas deportivas y urbanas. Entrega con factura y asesoría de pago.', 'images/img1.jpg'],
    ];

    /** [nombre, categoría, precio, descripción, destacado, foto original] */
    private const PRODUCTOS = [
        ['Figura Kuromi Corazón Lila', 'Peluches Kuromi', 329, 'Figura de vinil de Kuromi abrazando un corazón rosa. 12 cm, ideal para escritorio.', true, 'productos/descarga.jpg'],
        ['Figura Kuromi con Tridente en Caja', 'Peluches Kuromi', 459, 'Figura coleccionable de Kuromi con su tridente dorado, en caja de exhibición.', true, 'productos/descarga2.jpg'],
        ['Peluche Kuromi Esponjoso con Moño', 'Peluches Kuromi', 549, 'Peluche de felpa extra suave con moño y pancita lila. 30 cm.', true, 'productos/descarga3.jpg'],
        ['Peluche Kuromi Vestido Lila Gigante', 'Peluches Kuromi', 689, 'Kuromi grande con vestido lila y orejitas rellenas. Perfecto para abrazar. 45 cm.', true, 'productos/descarga5.jpg'],
        ['Peluche Kuromi Clásico 25 cm', 'Peluches Kuromi', 399, 'El clásico: capucha negra, calavera rosa y sonrisa traviesa. 25 cm.', false, 'productos/descarga6.jpg'],
        ['Figura Kuromi Edición Coleccionista', 'Peluches Kuromi', 499, 'Figura en tonos lila con llave mágica, en caja ilustrada de edición especial.', true, 'productos/descarga7.jpg'],
        ['Labial Cremoso Rosa Malva', 'Maquillaje', 189, 'Labial de acabado satinado en tono rosa malva. Hidrata y dura hasta 8 horas.', true, 'images/2.jpg'],
        ['Labial Rojo Intenso Larga Duración', 'Maquillaje', 219, 'Rojo clásico de alta pigmentación que no se transfiere. Acabado mate.', false, 'images/imagen3.jpg'],
        ['Kit de Brochas y Paleta de Sombras', 'Maquillaje', 649, 'Paleta de 18 sombras en tonos nude y rosa con set de brochas suaves.', true, 'images/imagen4.jpg'],
        ['Gloss Efecto Espejo', 'Maquillaje', 159, 'Brillo labial transparente con efecto espejo y aroma a fresa.', false, 'images/imagen45.jpg'],
        ['Polvo Compacto Matificante', 'Maquillaje', 199, 'Polvo compacto que controla el brillo y difumina poros. Incluye espejo.', false, 'images/imagen5.jpg'],
        ['Aceite Labial Rosado', 'Maquillaje', 179, 'Aceite labial con color translúcido, hidratación y brillo natural.', true, 'productos/descarga4.jpg'],
        ['Moto Naked 200 cc Negro y Rojo', 'Motos', 45900, 'Motocicleta naked de 200 cc, frenos de disco y tablero digital. Ideal para ciudad.', false, 'productos/apache2.jpg'],
        ['Moto Sport Carenada 200 cc', 'Motos', 62900, 'Deportiva carenada de 200 cc con faro LED y posición de manejo sport.', true, 'productos/bajaj1.jpg'],
        ['Moto Urbana 300 cc Roja', 'Motos', 48500, 'Motocicleta urbana de 300 cc con suspensión invertida y luces LED.', false, 'productos/veloci4.jpg'],
        ['Moto Deportiva 400 cc Verde Pista', 'Motos', 139900, 'Deportiva de 400 cc en verde lima, lista para pista y carretera.', true, 'images/img1.jpg'],
        ['Moto Deportiva 650 cc Verde Ciudad', 'Motos', 189900, 'Bicilíndrica de 650 cc con carenado completo y control de tracción.', false, 'images/img2.jpg'],
        ['Moto Sport Touring 650 cc', 'Motos', 179900, 'Sport touring de 650 cc, cómoda para viajes largos y uso diario.', false, 'images/img3.jpg'],
    ];

    private const CLIENTES = [
        ['Valeria Ortega Ríos', false], ['Mariana Castillo Luna', false], ['Diego Hernández Soto', false],
        ['Ximena Navarro Peña', false], ['Sofía Ramírez Cruz', false], ['Andrea Flores Medina', false],
        ['Fernanda Morales Vega', false], ['Camila Torres Aguilar', false], ['Renata Jiménez Ruiz', false],
        ['Paola Mendoza Salas', false], ['Luis Ángel Domínguez Paz', false], ['Regina Vargas Lozano', false],
        ['Daniela Guerrero Campos', false], ['Natalia Rojas Ibarra', false], ['Emilio Cortés Fuentes', false],
        ['Papelería La Constelación', true], ['Regalos Medianoche', true], ['Bazar Luna Violeta', true],
    ];

    public function run(): void
    {
        mt_srand(2026);
        fake()->seed(2026);

        Storage::disk('public')->deleteDirectory('facturas');
        Storage::disk('public')->deleteDirectory('productos');

        $categorias = collect(self::CATEGORIAS)->map(fn (array $c, string $nombre) => Categoria::create([
            'nombre' => $nombre,
            'slug' => Str::slug($nombre === 'Peluches Kuromi' ? 'kuromi' : $nombre),
            'descripcion' => $c[0],
            'imagen' => $c[1],
        ]));

        $productos = collect(self::PRODUCTOS)->map(function (array $p) use ($categorias) {
            // Copia la foto original al disco public para que funcione igual que una subida desde el admin.
            $destino = 'productos/'.Str::slug($p[0]).'.'.pathinfo($p[5], PATHINFO_EXTENSION);
            Storage::disk('public')->put($destino, file_get_contents(public_path($p[5])));

            return Producto::create([
                'categoria_id' => $categorias[$p[1]]->id,
                'nombre' => $p[0],
                'slug' => Str::slug($p[0]),
                'descripcion' => $p[3],
                'precio' => $p[2],
                'stock' => $p[2] > 10000 ? mt_rand(1, 4) : (mt_rand(0, 9) === 0 ? mt_rand(1, 4) : mt_rand(12, 60)),
                'destacado' => $p[4],
                'activo' => true,
                'imagen' => $destino,
            ]);
        })->values();

        $clientes = collect(self::CLIENTES)->values()->map(function (array $c, int $i) {
            [$nombre, $empresa] = $c;

            return Cliente::create([
                'nombre' => $empresa ? "{$nombre} SA de CV" : $nombre,
                'rfc' => $this->rfc($nombre, $empresa, $i),
                'direccion' => $this->direccion($i),
                'telefono' => '55 01'.str_pad((string) (10 + $i), 2, '0', STR_PAD_LEFT).' '.mt_rand(1000, 9999),
                'email' => Str::slug($nombre, '.').'@example.com',
                'descripcion' => $empresa ? 'Cliente mayorista. Compra para reventa en su tienda física.' : null,
            ]);
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
        $tienda = $productos->filter(fn (Producto $p) => (float) $p->precio < 10000)->values();
        $motos = $productos->diff($tienda)->sortBy('precio')->values();

        // Pedidos en línea a lo largo del último año, con crecimiento en los meses recientes.
        $fechas = collect(range(1, 64))
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

            $this->partidas($pedido, $tienda->random(mt_rand(1, 3)));

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

        // Motos: venta ocasional (dos en el año), siempre fuera del mes actual y del anterior.
        foreach ([[250, 0], [130, 1]] as [$diasAtras, $indice]) {
            $fecha = $hoy->copy()->subDays($diasAtras)->setTime(12, 30);
            $cliente = $personas->random();
            $pedido = Pedido::create([
                'folio' => 'PED-M'.str_pad((string) ($indice + 1), 4, '0', STR_PAD_LEFT),
                'cliente_id' => $cliente->id,
                'estado' => 'entregado',
                'total' => 0,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
            $this->partidas($pedido, collect([$motos[$indice]]), 1);
            $porCrear[] = [
                'cliente_id' => $cliente->id,
                'pedido_id' => $pedido->id,
                'forma_pago_id' => $formas[0],
                'fecha_emision' => $fecha->copy()->addDay(),
                'detalles' => "Pedido en línea {$pedido->folio}",
                'estado_id' => $estado(EstadoFactura::PAGADA),
            ] + Factura::importesDesdeTotal((float) $pedido->total);
        }

        // Ventas de mayoreo facturadas directamente.
        foreach (range(1, 10) as $i) {
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
                'detalles' => 'Venta de mayoreo: surtido de '.collect(['peluches de Kuromi', 'figuras de Kuromi', 'labiales', 'paletas de sombras'])->random(2)->implode(' y ').' para reventa.',
                'estado_id' => $estado($clave),
            ] + Factura::importesDesdeSubtotal(mt_rand(120, 520) * 10));
        }

        // Mes en curso realista: cobros pagados este mes con una variacion creible frente al mes anterior.
        $pagada = $estado(EstadoFactura::PAGADA);
        $cobradoEn = fn (Carbon $mes) => collect($porCrear)
            ->filter(fn (array $f) => $f['estado_id'] === $pagada && $f['fecha_emision']->isSameMonth($mes))
            ->sum('total');
        $anterior = $cobradoEn($hoy->copy()->subMonthNoOverflow()) ?: 24000;
        $faltante = $anterior * mt_rand(108, 122) / 100 - $cobradoEn($hoy);

        $folio = 1;
        while ($faltante > 150) {
            $fecha = $hoy->copy()->startOfMonth()->addDays(mt_rand(0, max(0, $hoy->day - 2)))->setTime(mt_rand(10, 19), mt_rand(0, 59));
            $cliente = $personas->random();
            $pedido = Pedido::create([
                'folio' => 'PED-A'.str_pad((string) $folio++, 4, '0', STR_PAD_LEFT),
                'cliente_id' => $cliente->id,
                'estado' => 'entregado',
                'total' => 0,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
            // Cerca de la meta se agrega una sola pieza que quepa, para no pasarse.
            $caben = $tienda->filter(fn (Producto $p) => (float) $p->precio <= $faltante);
            $faltante > 1800
                ? $this->partidas($pedido, $tienda->random(mt_rand(1, 2)), 1)
                : $this->partidas($pedido, $caben->isEmpty() ? $tienda->sortBy('precio')->take(1) : $caben->random(1), 1);
            $faltante -= (float) $pedido->total;
            $porCrear[] = [
                'cliente_id' => $cliente->id,
                'pedido_id' => $pedido->id,
                'forma_pago_id' => $formas[array_rand($formas)],
                'fecha_emision' => $fecha->copy()->min($hoy),
                'detalles' => "Pedido en línea {$pedido->folio}",
                'estado_id' => $pagada,
            ] + Factura::importesDesdeTotal((float) $pedido->total);
        }

        // Se crean en orden cronológico para que la numeracion KC-AAAA-0001 sea consecutiva.
        collect($porCrear)->sortBy(fn (array $f) => $f['fecha_emision']->timestamp)->each(fn (array $f) => $this->factura($f));
    }

    /** Agrega partidas al pedido y recalcula su total. */
    private function partidas(Pedido $pedido, $articulos, ?int $cantidadFija = null): void
    {
        foreach ($articulos as $producto) {
            $cantidad = $cantidadFija ?? mt_rand(1, 3);
            $pedido->items()->create([
                'producto_id' => $producto->id,
                'nombre' => $producto->nombre,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal' => round((float) $producto->precio * $cantidad, 2),
            ]);
        }

        $pedido->update(['total' => $pedido->items()->sum('subtotal')]);
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
}
