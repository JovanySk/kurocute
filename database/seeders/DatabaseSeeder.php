<?php

namespace Database\Seeders;

use App\Models\Perfil;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(CatalogosSeeder::class);

        // Cuentas de demostración (contraseña: password).
        User::factory()->create([
            'name' => 'Admin KuroCute',
            'email' => 'admin@example.com',
            'perfil_id' => Perfil::where('nombre', Perfil::ADMINISTRADOR)->value('id'),
        ]);

        User::factory()->create([
            'name' => 'Consulta KuroCute',
            'email' => 'consulta@example.com',
            'perfil_id' => Perfil::where('nombre', Perfil::CONSULTA)->value('id'),
        ]);

        $this->call(DemoSeeder::class);
    }
}
