<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\EstadoFactura;
use App\Models\Factura;
use App\Models\FormaPago;
use App\Models\Pedido;
use App\Models\Perfil;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackOfficeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_el_dashboard_muestra_kpis_y_graficas(): void
    {
        Factura::factory()->estado(EstadoFactura::PAGADA)->create(['fecha_emision' => now(), 'subtotal' => 1000, 'iva' => 160, 'total' => 1160]);
        Factura::factory()->estado(EstadoFactura::VENCIDA)->create(['fecha_emision' => now()->subMonth(), 'subtotal' => 500, 'iva' => 80, 'total' => 580]);

        $this->actingAs($this->admin)->get('/dashboard')
            ->assertOk()
            ->assertSee('Cobrado este mes')
            ->assertSee('$1,160')
            ->assertSee('$580')
            ->assertSee('data-grafica="ingresos"', false)
            ->assertSee('data-grafica="estados"', false)
            ->assertViewHas('kpis', fn (array $kpis) => $kpis['vencidas'] === 1 && $kpis['facturas'] === 2);
    }

    public function test_el_admin_crea_un_cliente_con_imagen(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)->post(route('clientes.store'), [
            'nombre' => 'Regalos Medianoche',
            'rfc' => 'rme850101ab1',
            'direccion' => 'Av. Estrella 10',
            'telefono' => '55 0100 2000',
            'email' => 'regalos@example.com',
            'imagen' => UploadedFile::fake()->image('logo.png', 300, 300),
        ])->assertRedirect();

        $cliente = Cliente::firstOrFail();
        $this->assertSame('RME850101AB1', $cliente->rfc);
        Storage::disk('public')->assertExists($cliente->imagen);
    }

    public function test_el_rfc_se_valida(): void
    {
        $this->actingAs($this->admin)->post(route('clientes.store'), [
            'nombre' => 'X', 'rfc' => '123', 'direccion' => 'Calle', 'telefono' => '5500000000', 'email' => 'x@example.com',
        ])->assertSessionHasErrors('rfc');
    }

    public function test_la_busqueda_de_clientes(): void
    {
        Cliente::factory()->create(['nombre' => 'Mariana Castillo']);
        Cliente::factory()->create(['nombre' => 'Diego Hernandez']);

        $this->actingAs($this->admin)->get(route('clientes.index', ['q' => 'mariana']))
            ->assertOk()->assertSee('Mariana Castillo')->assertDontSee('Diego Hernandez');
    }

    public function test_el_admin_actualiza_y_elimina_un_cliente(): void
    {
        $cliente = Cliente::factory()->create();

        $this->actingAs($this->admin)->put(route('clientes.update', $cliente), [
            'nombre' => 'Nombre Nuevo', 'rfc' => 'NUNE900101AA1', 'direccion' => 'Calle 2', 'telefono' => '5511111111', 'email' => $cliente->email,
        ])->assertRedirect(route('clientes.show', $cliente));
        $this->assertSame('Nombre Nuevo', $cliente->fresh()->nombre);

        $this->actingAs($this->admin)->delete(route('clientes.destroy', $cliente))->assertRedirect(route('clientes.index'));
        $this->assertModelMissing($cliente);
    }

    public function test_un_pedido_se_factura_con_iva_desglosado(): void
    {
        $pedido = Pedido::factory()->conPartidas(2)->create(['estado' => 'en_proceso']);

        $this->actingAs($this->admin)->post(route('pedidos.facturar', $pedido), ['forma_pago_id' => FormaPago::first()->id])
            ->assertRedirect();

        $factura = $pedido->fresh()->factura;
        $this->assertNotNull($factura);
        $this->assertEquals((float) $pedido->fresh()->total, (float) $factura->total);
        $this->assertEquals(round((float) $factura->total / 1.16, 2), (float) $factura->subtotal);
        $this->assertCount(2, $factura->conceptos());
        $this->assertEqualsWithDelta((float) $factura->subtotal, array_sum(array_column($factura->conceptos(), 'importe')), 0.001);

        // No se duplica la factura.
        $this->actingAs($this->admin)->post(route('pedidos.facturar', $pedido), ['forma_pago_id' => FormaPago::first()->id]);
        $this->assertSame(1, Factura::count());
    }

    public function test_el_estado_del_pedido_se_actualiza(): void
    {
        $pedido = Pedido::factory()->create();

        $this->actingAs($this->admin)->patch(route('pedidos.update', $pedido), ['estado' => 'enviado'])->assertRedirect();
        $this->assertSame('enviado', $pedido->fresh()->estado);

        $this->actingAs($this->admin)->patch(route('pedidos.update', $pedido), ['estado' => 'perdido'])->assertSessionHasErrors('estado');
    }

    public function test_el_admin_gestiona_productos_y_categorias(): void
    {
        $this->actingAs($this->admin)->post(route('categorias.store'), ['nombre' => 'Peluches'])->assertRedirect();
        $categoria = Categoria::firstOrFail();
        $this->assertSame('peluches', $categoria->slug);

        $this->actingAs($this->admin)->post(route('productos.store'), [
            'nombre' => 'Peluche Luna', 'categoria_id' => $categoria->id, 'precio' => 459, 'stock' => 12, 'activo' => '1',
        ])->assertRedirect(route('productos.index'));

        $producto = Producto::firstOrFail();
        $this->assertSame('peluche-luna', $producto->slug);
        $this->assertTrue($producto->activo);

        $this->actingAs($this->admin)->get(route('productos.index', ['q' => 'luna']))->assertOk()->assertSee('Peluche Luna');

        $this->actingAs($this->admin)->delete(route('categorias.destroy', $categoria));
        $this->assertNull($producto->fresh()->categoria_id);
    }

    public function test_el_perfil_administrador_no_se_puede_eliminar(): void
    {
        $perfil = Perfil::where('nombre', Perfil::ADMINISTRADOR)->first();

        $this->actingAs($this->admin)->delete(route('perfiles.destroy', $perfil))->assertSessionHas('error');
        $this->assertModelExists($perfil);
    }

    public function test_el_admin_crea_un_perfil(): void
    {
        $this->actingAs($this->admin)->post(route('perfiles.store'), [
            'nombre' => 'Ventas', 'fecha_registro' => '2026-01-10', 'estado' => 'activo', 'descripcion' => 'Equipo de mostrador',
        ])->assertRedirect(route('perfiles.index'));

        $this->assertDatabaseHas('perfiles', ['nombre' => 'Ventas', 'estado' => 'activo']);
    }
}
