<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    public function definition(): array
    {
        $nombre = fake()->firstName().' '.fake()->lastName();

        return [
            'nombre' => $nombre,
            'rfc' => strtoupper(Str::random(4)).fake()->date('ymd').strtoupper(Str::random(3)),
            'direccion' => fake()->streetAddress().', '.fake()->city(),
            'telefono' => fake()->numerify('55 #### ####'),
            'email' => Str::slug($nombre, '.').'.'.fake()->unique()->numberBetween(1, 99999).'@example.com',
            'descripcion' => null,
        ];
    }
}
