<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'folio' => 'PED-'.fake()->unique()->numerify('#####'),
            'cliente_id' => Cliente::factory(),
            'estado' => 'nuevo',
            'total' => 0,
            'notas' => null,
        ];
    }

    /** Agrega partidas con productos y recalcula el total. */
    public function conPartidas(int $cantidad = 2): static
    {
        return $this->afterCreating(function (Pedido $pedido) use ($cantidad) {
            Producto::factory()->count($cantidad)->create()->each(function (Producto $producto) use ($pedido) {
                $pedido->items()->create([
                    'producto_id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'cantidad' => 2,
                    'precio_unitario' => $producto->precio,
                    'subtotal' => round((float) $producto->precio * 2, 2),
                ]);
            });

            $pedido->update(['total' => $pedido->items()->sum('subtotal')]);
        });
    }
}
