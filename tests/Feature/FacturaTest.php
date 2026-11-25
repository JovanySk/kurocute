<?php

namespace Tests\Feature;

use App\Mail\FacturaCreadaMail;
use App\Models\Cliente;
use App\Models\EstadoFactura;
use App\Models\Factura;
use App\Models\FormaPago;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FacturaTest extends TestCase
{
    use RefreshDatabase;

    private function datos(array $cambios = []): array
    {
        return array_merge([
            'numero' => 'KC-2026-0100',
            'cliente_id' => Cliente::factory()->create()->id,
            'estado_id' => EstadoFactura::idDe(EstadoFactura::PENDIENTE),
            'forma_pago_id' => FormaPago::first()->id,
            'fecha_emision' => '2026-11-01',
            'fecha_vencimiento' => '2026-11-16',
            'subtotal' => 1000,
            'detalles' => 'Surtido de peluches',
        ], $cambios);
    }

    public function test_el_admin_crea_una_factura_con_pdf_y_xml_y_se_envia_el_correo(): void
    {
        Storage::fake('public');
        Mail::fake();
        $admin = User::factory()->admin()->create();

        $respuesta = $this->actingAs($admin)->post(route('facturas.store'), $this->datos([
            'archivo_pdf' => UploadedFile::fake()->create('comprobante.pdf', 120, 'application/pdf'),
            'archivo_xml' => UploadedFile::fake()->createWithContent('timbre.xml', '<?xml version="1.0"?><cfdi/>'),
        ]));

        $factura = Factura::firstOrFail();
        $respuesta->assertRedirect(route('facturas.show', $factura))->assertSessionHas('success');

        $this->assertSame('1000.00', $factura->subtotal);
        $this->assertSame('160.00', $factura->iva);
        $this->assertSame('1160.00', $factura->total);
        Storage::disk('public')->assertExists([$factura->archivo_pdf, $factura->archivo_xml]);

        Mail::assertSent(FacturaCreadaMail::class, fn (FacturaCreadaMail $mail) => $mail->hasTo($factura->cliente->email)
            && $mail->factura->is($factura));
    }

    public function test_el_correo_adjunta_el_pdf_generado(): void
    {
        $factura = Factura::factory()->create();

        $correo = new FacturaCreadaMail($factura);

        $correo->assertHasSubject("Tu factura {$factura->numero} de KuroCute");
        $correo->assertSeeInHtml($factura->numero);
        $this->assertCount(1, $correo->attachments());
    }

    public function test_se_rechazan_archivos_que_no_son_pdf_o_xml(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('facturas.store'), $this->datos([
            'archivo_pdf' => UploadedFile::fake()->image('foto.png'),
        ]))->assertSessionHasErrors('archivo_pdf');

        $this->assertSame(0, Factura::count());
    }

    public function test_el_folio_debe_ser_unico(): void
    {
        $admin = User::factory()->admin()->create();
        Factura::factory()->create(['numero' => 'KC-2026-0100']);

        $this->actingAs($admin)->post(route('facturas.store'), $this->datos())->assertSessionHasErrors('numero');
    }

    public function test_descargar_pdf_devuelve_application_pdf(): void
    {
        $usuario = User::factory()->create();
        $factura = Factura::factory()->create();

        $respuesta = $this->actingAs($usuario)->get(route('facturas.pdf', $factura));

        $respuesta->assertOk()->assertHeader('content-type', 'application/pdf');
        $this->assertStringStartsWith('%PDF', $respuesta->getContent());
        $this->assertStringContainsString("Factura-{$factura->numero}.pdf", $respuesta->headers->get('content-disposition'));
    }

    public function test_la_vista_previa_del_correo_se_renderiza(): void
    {
        $factura = Factura::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get(route('facturas.correo', $factura))
            ->assertOk()
            ->assertSee($factura->numero)
            ->assertSee('tu factura ya está lista');
    }

    public function test_el_listado_filtra_por_estado_y_texto(): void
    {
        $usuario = User::factory()->create();
        $pagada = Factura::factory()->estado(EstadoFactura::PAGADA)->create(['numero' => 'KC-2026-0001']);
        $pendiente = Factura::factory()->create(['numero' => 'KC-2026-0002']);

        $this->actingAs($usuario)->get(route('facturas.index', ['estado' => 'pagada']))
            ->assertOk()->assertSee($pagada->numero)->assertDontSee($pendiente->numero);

        $this->actingAs($usuario)->get(route('facturas.index', ['q' => $pendiente->cliente->nombre]))
            ->assertOk()->assertSee($pendiente->numero)->assertDontSee($pagada->numero);
    }

    public function test_el_admin_actualiza_y_elimina_una_factura(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $factura = Factura::factory()->create();

        $this->actingAs($admin)->put(route('facturas.update', $factura), $this->datos([
            'numero' => $factura->numero,
            'cliente_id' => $factura->cliente_id,
            'estado_id' => EstadoFactura::idDe(EstadoFactura::PAGADA),
            'subtotal' => 500,
        ]))->assertRedirect(route('facturas.show', $factura));

        $this->assertSame('pagada', $factura->fresh()->estado->clave);
        $this->assertSame('580.00', $factura->fresh()->total);

        $this->actingAs($admin)->delete(route('facturas.destroy', $factura))->assertRedirect(route('facturas.index'));
        $this->assertModelMissing($factura);
    }

    public function test_el_detalle_muestra_conceptos_y_totales(): void
    {
        $factura = Factura::factory()->create(['detalles' => 'Venta de mayoreo']);

        $this->actingAs(User::factory()->create())->get(route('facturas.show', $factura))
            ->assertOk()
            ->assertSee('Venta de mayoreo')
            ->assertSee('$'.number_format((float) $factura->total, 2))
            ->assertSee('Descargar PDF');
    }

    public function test_el_siguiente_numero_es_consecutivo_por_anio(): void
    {
        Factura::factory()->create(['numero' => 'KC-2026-0041']);

        $this->assertSame('KC-2026-0042', Factura::siguienteNumero(2026));
        $this->assertSame('KC-2027-0001', Factura::siguienteNumero(2027));
    }
}
