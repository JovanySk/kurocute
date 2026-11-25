<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PieDePaginaTest extends TestCase
{
    use RefreshDatabase;

    public static function paginasDeAyuda(): array
    {
        return [
            'preguntas frecuentes' => ['/preguntas-frecuentes', 'Preguntas frecuentes', '¿Cómo pago?'],
            'envíos y entregas' => ['/envios-y-entregas', 'Envíos y entregas', 'Recoger en Tantoyuca'],
            'contacto' => ['/contacto', 'Escríbenos', 'Enviar mensaje'],
        ];
    }

    #[DataProvider('paginasDeAyuda')]
    public function test_las_paginas_de_ayuda_responden(string $ruta, string $titulo, string $texto): void
    {
        $this->get($ruta)
            ->assertOk()
            ->assertSee($titulo)
            ->assertSee($texto);
    }

    public function test_el_pie_enlaza_a_paginas_y_categorias_reales(): void
    {
        $peluches = Categoria::factory()->create(['nombre' => 'Peluches Kuromi', 'slug' => 'kuromi']);
        $motos = Categoria::factory()->create(['nombre' => 'Motos', 'slug' => 'motos']);

        $respuesta = $this->get('/')->assertOk();

        foreach ([
            route('tienda.catalogo'),
            route('carrito.index'),
            route('ayuda.preguntas'),
            route('ayuda.envios'),
            route('contacto'),
            route('inicio').'#como-funciona',
            route('inicio').'#favoritos',
            route('tienda.catalogo', ['categoria' => $peluches->slug]),
            route('tienda.catalogo', ['categoria' => $motos->slug]),
        ] as $url) {
            $respuesta->assertSee('href="'.$url.'"', false);
        }

        $respuesta->assertDontSee('href="#"', false);
    }

    public function test_el_pie_muestra_los_creditos_sin_avisos_de_proyecto_escolar(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('KuroCute. Todos los derechos reservados.')
            ->assertSee('Tienda desarrollada por')
            ->assertSee('Kuromi &copy; Sanrio. Sitio de fans.', false)
            ->assertSee('Lunes a sábado, 10:00 a 19:00')
            ->assertSee('Tantoyuca, Veracruz')
            ->assertSee('title="Próximamente"', false)
            ->assertDontSee('Proyecto escolar')
            ->assertDontSee('hola@example.com');
    }

    public function test_mi_cuenta_cambia_segun_la_sesion(): void
    {
        $this->get('/')
            ->assertSee('href="'.route('register').'"', false)
            ->assertSee('href="'.route('password.request').'"', false);

        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertSee('href="'.route('profile.edit').'"', false)
            ->assertSee('Cerrar sesión')
            ->assertDontSee('href="'.route('register').'"', false);
    }

    public function test_el_back_office_tiene_pie_con_creditos(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('KuroCute. Todos los derechos reservados.')
            ->assertSee('Tienda desarrollada por');
    }
}
