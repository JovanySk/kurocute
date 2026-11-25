<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    public function definition(): array
    {
        $nombre = 'Producto '.fake()->unique()->words(2, true).' '.fake()->unique()->numberBetween(1, 99999);

        return [
            'categoria_id' => Categoria::factory(),
            'nombre' => $nombre,
            'slug' => Str::slug($nombre),
            'descripcion' => fake()->sentence(12),
            'precio' => fake()->randomFloat(2, 59, 999),
            'stock' => fake()->numberBetween(5, 40),
            'destacado' => false,
            'activo' => true,
        ];
    }

    public function destacado(): static
    {
        return $this->state(fn () => ['destacado' => true]);
    }

    public function inactivo(): static
    {
        return $this->state(fn () => ['activo' => false]);
    }
}
