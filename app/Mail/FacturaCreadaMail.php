<?php

namespace App\Mail;

use App\Models\Factura;
use App\Support\FacturaPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FacturaCreadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Factura $factura) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Tu factura {$this->factura->numero} de KuroCute");
    }

    public function content(): Content
    {
        $this->factura->loadMissing(['cliente', 'estado', 'formaPago', 'pedido.items']);

        return new Content(
            view: 'emails.factura.creada',
            with: [
                'factura' => $this->factura,
                'conceptos' => $this->factura->conceptos(),
            ],
        );
    }

    /** Adjunta el PDF generado y, si existe, el XML timbrado cargado por el usuario. */
    public function attachments(): array
    {
        $adjuntos = [
            Attachment::fromData(fn () => FacturaPdf::crear($this->factura)->output(), FacturaPdf::nombreArchivo($this->factura))
                ->withMime('application/pdf'),
        ];

        if ($this->factura->archivo_xml && Storage::disk('public')->exists($this->factura->archivo_xml)) {
            $adjuntos[] = Attachment::fromStorageDisk('public', $this->factura->archivo_xml)
                ->as("Factura-{$this->factura->numero}.xml")
                ->withMime('application/xml');
        }

        return $adjuntos;
    }
}
