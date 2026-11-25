<?php

namespace App\Http\Controllers;

use App\Mail\MensajeContactoMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactoController extends Controller
{
    public const ASUNTOS = [
        'pedido' => 'Mi pedido',
        'factura' => 'Factura',
        'envio' => 'Envío o entrega',
        'producto' => 'Duda sobre un producto',
        'otro' => 'Otro tema',
    ];

    public function mostrar(): View
    {
        return view('tienda.contacto', ['asuntos' => self::ASUNTOS]);
    }

    /** Valida el formulario y envía el mensaje al correo de la tienda (Mailpit en local). */
    public function enviar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'asunto' => ['required', Rule::in(array_keys(self::ASUNTOS))],
            'folio' => ['nullable', 'string', 'max:30'],
            'mensaje' => ['required', 'string', 'min:10', 'max:2000'],
            // Campo trampa invisible: si un bot lo llena, el envío se rechaza.
            'sitio_web' => ['prohibited'],
        ], [
            'sitio_web.prohibited' => 'No pudimos enviar tu mensaje.',
        ], [
            'mensaje' => 'mensaje',
            'folio' => 'folio',
        ]);

        $datos['asunto_nombre'] = self::ASUNTOS[$datos['asunto']];

        Mail::to(config('mail.contacto'))->send(new MensajeContactoMail($datos));

        return redirect()->route('contacto')
            ->with('success', '¡Gracias! Recibimos tu mensaje y te responderemos a '.$datos['email'].' muy pronto.');
    }
}
