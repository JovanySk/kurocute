<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AccesoTest extends TestCase
{
    use RefreshDatabase;

    public static function rutasProtegidas(): array
    {
        return [
            'dashboard' => ['/dashboard'],
            'clientes' => ['/admin/clientes'],
            'facturas' => ['/admin/facturas'],
            'nueva factura' => ['/admin/facturas/create'],
            'productos' => ['/admin/productos'],
            'categorias' => ['/admin/categorias'],
            'pedidos' => ['/admin/pedidos'],
            'perfiles' => ['/admin/perfiles'],
        ];
    }

    #[DataProvider('rutasProtegidas')]
    public function test_los_invitados_son_enviados_al_login(string $ruta): void
    {
        $this->get($ruta)->assertRedirect(route('login'));
    }

    public function test_los_invitados_no_descargan_facturas(): void
    {
        $factura = Factura::factory()->create();

        $this->get(route('facturas.pdf', $factura))->assertRedirect(route('login'));
    }

    public function test_un_usuario_de_consulta_puede_ver_pero_no_modificar(): void
    {
        $usuario = User::factory()->create();
        $cliente = Cliente::factory()->create();

        $this->actingAs($usuario)->get('/admin/clientes')->assertOk()->assertSee($cliente->nombre)->assertSee('Solo lectura');
        $this->actingAs($usuario)->get('/admin/clientes/create')->assertForbidden();
        $this->actingAs($usuario)->delete(route('clientes.destroy', $cliente))->assertForbidden();
        $this->assertModelExists($cliente);
    }

    public function test_el_administrador_accede_a_todo_el_back_office(): void
    {
        $admin = User::factory()->admin()->create();
        Factura::factory()->create();

        foreach (array_column(self::rutasProtegidas(), 0) as $ruta) {
            $this->actingAs($admin)->get($ruta)->assertOk();
        }
    }

    public function test_el_registro_asigna_el_perfil_de_consulta(): void
    {
        $this->post('/register', [
            'name' => 'Nueva Persona',
            'email' => 'nueva@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $usuario = User::where('email', 'nueva@example.com')->firstOrFail();
        $this->assertSame('Consulta', $usuario->perfil->nombre);
        $this->assertFalse($usuario->esAdmin());
    }
}
