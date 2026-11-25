<?php

namespace Tests\Feature;

use App\Mail\MensajeContactoMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactoTest extends TestCase
{
    use RefreshDatabase;

    private function datos(array $cambios = []): array
    {
        return array_merge([
            'nombre' => 'Luna Pérez',
            'email' => 'luna@example.com',
            'asunto' => 'pedido',
            'folio' => 'PED-00012',
            'mensaje' => '¿Ya salió a envío mi pedido? Gracias.',
        ], $cambios);
    }

    public function test_el_formulario_incluye_el_token_csrf(): void
    {
        $this->get('/contacto')
            ->assertOk()
            ->assertSee('action="'.route('contacto.enviar').'"', false)
            ->assertSee('name="_token"', false);
    }

    public function test_enviar_el_formulario_manda_el_correo_a_la_tienda(): void
    {
        Mail::fake();
        config(['mail.contacto' => 'contacto@example.com']);

        $this->from('/contacto')
            ->post('/contacto', $this->datos())
            ->assertRedirect(route('contacto'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        Mail::assertSent(MensajeContactoMail::class, function (MensajeContactoMail $mail) {
            return $mail->hasTo('contacto@example.com')
                && $mail->hasReplyTo('luna@example.com')
                && $mail->hasSubject('Contacto KuroCute: Mi pedido');
        });
    }

    public function test_el_mensaje_exitoso_se_muestra_en_la_pagina(): void
    {
        Mail::fake();

        $this->followingRedirects()
            ->post('/contacto', $this->datos())
            ->assertOk()
            ->assertSee('Recibimos tu mensaje');
    }

    public function test_valida_los_campos_obligatorios(): void
    {
        Mail::fake();

        $this->from('/contacto')
            ->post('/contacto', ['email' => 'no-es-correo', 'asunto' => 'inventado', 'mensaje' => 'corto'])
            ->assertRedirect('/contacto')
            ->assertSessionHasErrors(['nombre', 'email', 'asunto', 'mensaje']);

        Mail::assertNothingSent();
    }

    public function test_rechaza_el_campo_trampa_de_bots(): void
    {
        Mail::fake();

        $this->post('/contacto', $this->datos(['sitio_web' => 'https://spam.example.com']))
            ->assertSessionHasErrors('sitio_web');

        Mail::assertNothingSent();
    }

    public function test_el_correo_incluye_los_datos_del_mensaje(): void
    {
        $correo = new MensajeContactoMail($this->datos(['asunto_nombre' => 'Mi pedido']));

        $correo->assertSeeInHtml('Luna Pérez');
        $correo->assertSeeInHtml('PED-00012');
        $correo->assertSeeInHtml('¿Ya salió a envío mi pedido? Gracias.');
    }
}
