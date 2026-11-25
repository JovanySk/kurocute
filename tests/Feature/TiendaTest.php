<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TiendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_muestra_los_productos_destacados(): void
    {
        $destacado = Producto::factory()->destacado()->create(['nombre' => 'Peluche Luna Traviesa']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Lo tierno también tiene su')
            ->assertSee($destacado->nombre);
    }

    public function test_la_busqueda_del_catalogo_filtra_por_texto(): void
    {
        Producto::factory()->create(['nombre' => 'Taza Luna Rosa']);
        Producto::factory()->create(['nombre' => 'Libreta Estrellada']);

        $this->getJson('/catalogo/buscar?q=luna')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('productos.0.nombre', 'Taza Luna Rosa');
    }

    public function test_la_busqueda_tambien_revisa_la_descripcion(): void
    {
        Producto::factory()->create(['nombre' => 'Cojin', 'descripcion' => 'Con bordado de constelaciones']);
        Producto::factory()->create(['nombre' => 'Pluma', 'descripcion' => 'Tinta lavanda']);

        $this->getJson('/catalogo/buscar?q=constelacion')
            ->assertOk()
            ->assertJsonCount(1, 'productos')
            ->assertJsonPath('productos.0.nombre', 'Cojin');
    }

    public function test_la_busqueda_filtra_por_categoria_y_ordena_por_precio(): void
    {
        $peluches = Categoria::factory()->create(['nombre' => 'Peluches', 'slug' => 'peluches']);
        Producto::factory()->for($peluches)->create(['nombre' => 'Caro', 'precio' => 900]);
        Producto::factory()->for($peluches)->create(['nombre' => 'Barato', 'precio' => 100]);
        Producto::factory()->create(['nombre' => 'Otra categoria']);

        $this->getJson('/catalogo/buscar?categoria=peluches&orden=precio_asc')
            ->assertOk()
            ->assertJsonPath('total', 2)
            ->assertJsonPath('productos.0.nombre', 'Barato')
            ->assertJsonPath('productos.1.nombre', 'Caro');
    }

    public function test_los_productos_ocultos_no_aparecen_en_el_catalogo(): void
    {
        $oculto = Producto::factory()->inactivo()->create(['nombre' => 'Prototipo secreto']);

        $this->getJson('/catalogo/buscar?q=secreto')->assertJsonPath('total', 0);
        $this->get("/producto/{$oculto->slug}")->assertNotFound();
    }

    public function test_la_pagina_de_catalogo_conserva_los_filtros_de_la_url(): void
    {
        Producto::factory()->create(['nombre' => 'Vela Lavanda']);

        $this->get('/catalogo?q=vela')
            ->assertOk()
            ->assertSee('Vela Lavanda')
            ->assertViewHas('filtros', fn (array $filtros) => $filtros['q'] === 'vela')
            ->assertViewHas('productos', fn ($productos) => $productos->count() === 1);
    }

    public function test_la_busqueda_valida_el_orden(): void
    {
        $this->getJson('/catalogo/buscar?orden=inventado')->assertUnprocessable();
    }

    public function test_la_ficha_de_producto_muestra_detalle_y_relacionados(): void
    {
        $categoria = Categoria::factory()->create();
        $producto = Producto::factory()->for($categoria)->create(['nombre' => 'Lampara Planeta', 'precio' => 649]);
        $relacionado = Producto::factory()->for($categoria)->create(['nombre' => 'Manta Cielo']);

        $this->get("/producto/{$producto->slug}")
            ->assertOk()
            ->assertSee('Lampara Planeta')
            ->assertSee('$649.00')
            ->assertSee($relacionado->nombre);
    }
}
