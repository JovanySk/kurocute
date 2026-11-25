<?php

namespace Database\Factories;

use App\Models\Perfil;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => ['email_verified_at' => null]);
    }

    /** Usuario con perfil Administrador (puede crear, editar y eliminar). */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'perfil_id' => Perfil::firstOrCreate(['nombre' => Perfil::ADMINISTRADOR], ['estado' => 'activo', 'fecha_registro' => now()])->id,
        ]);
    }
}
