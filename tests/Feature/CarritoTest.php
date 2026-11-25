<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarritoTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_agrega_un_producto_al_pedido(): void
    {
        $producto = Producto::factory()->create(['precio' => 150, 'stock' => 10]);

        $this->postJson('/carrito', ['producto_id' => $producto->id, 'cantidad' => 2])
            ->assertOk()
            ->assertJsonPath('cantidad', 2)
            ->assertJsonPath('total', 300)
            ->assertJsonPath('partidas.0.id', $producto->id);
    }

    public function test_la_cantidad_no_supera_el_stock(): void
    {
        $producto = Producto::factory()->create(['stock' => 3]);

        $this->postJson('/carrito', ['producto_id' => $producto->id, 'cantidad' => 5])
            ->assertJsonPath('cantidad', 3);
    }

    public function test_no_se_puede_agregar_un_producto_agotado(): void
    {
        $producto = Producto::factory()->create(['stock' => 0]);

        $this->postJson('/carrito', ['producto_id' => $producto->id])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('producto_id');
    }

    public function test_se_actualiza_y_se_quita_una_partida(): void
    {
        $producto = Producto::factory()->create(['stock' => 10]);
        $this->postJson('/carrito', ['producto_id' => $producto->id]);

        $this->patchJson("/carrito/{$producto->id}", ['cantidad' => 4])->assertJsonPath('cantidad', 4);
        $this->deleteJson("/carrito/{$producto->id}")->assertJsonPath('cantidad', 0);
    }

    public function test_confirmar_el_pedido_crea_cliente_pedido_y_descuenta_stock(): void
    {
        $producto = Producto::factory()->create(['precio' => 200, 'stock' => 10]);
        $this->postJson('/carrito', ['producto_id' => $producto->id, 'cantidad' => 3]);

        $respuesta = $this->post('/carrito/confirmar', [
            'nombre' => 'Valeria Ortega',
            'email' => 'Valeria@Example.com',
            'telefono' => '55 0100 1234',
            'direccion' => 'Calle Luna 12, Centro',
        ]);

        $pedido = Pedido::firstOrFail();
        $respuesta->assertRedirect(route('carrito.gracias', $pedido->folio));

        $this->assertSame('600.00', $pedido->total);
        $this->assertSame('valeria@example.com', $pedido->cliente->email);
        $this->assertSame(1, $pedido->items()->count());
        $this->assertSame(7, $producto->fresh()->stock);

        $this->get(route('carrito.gracias', $pedido->folio))->assertOk()->assertSee($pedido->folio);
        $this->getJson('/carrito/resumen')->assertJsonPath('cantidad', 0);
    }

    public function test_un_cliente_existente_se_reutiliza_por_correo(): void
    {
        $cliente = Cliente::factory()->create(['email' => 'ana@example.com']);
        $producto = Producto::factory()->create();
        $this->postJson('/carrito', ['producto_id' => $producto->id]);

        $this->post('/carrito/confirmar', [
            'nombre' => 'Ana', 'email' => 'ana@example.com', 'telefono' => '5500000000', 'direccion' => 'Calle 1',
        ])->assertRedirect();

        $this->assertSame(1, Cliente::count());
        $this->assertSame($cliente->id, Pedido::first()->cliente_id);
    }

    public function test_no_se_confirma_un_pedido_vacio(): void
    {
        $this->post('/carrito/confirmar', [
            'nombre' => 'Ana', 'email' => 'ana@example.com', 'telefono' => '5500000000', 'direccion' => 'Calle 1',
        ])->assertRedirect(route('carrito.index'));

        $this->assertSame(0, Pedido::count());
    }

    public function test_el_resumen_del_pedido_es_privado(): void
    {
        $pedido = Pedido::factory()->create();

        $this->get(route('carrito.gracias', $pedido->folio))->assertNotFound();
    }
}
