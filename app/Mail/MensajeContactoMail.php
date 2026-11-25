<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Mensaje enviado desde el formulario de contacto de la tienda. */
class MensajeContactoMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @param array{nombre: string, email: string, asunto: string, asunto_nombre: string, folio?: ?string, mensaje: string} $datos */
    public function __construct(public array $datos) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->datos['email'], $this->datos['nombre'])],
            subject: "Contacto KuroCute: {$this->datos['asunto_nombre']}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contacto.mensaje', with: ['datos' => $this->datos]);
    }
}
