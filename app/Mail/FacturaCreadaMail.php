<?php

namespace App\Mail;

use App\Models\Factura;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaCreadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $factura;

    public function __construct(Factura $factura)
    {
        $this->factura = $factura;
    }
    public function build()
    {
        $mail = $this->subject('Factura creada - VYHZ')
            ->view('emails.factura.creada')
            ->with([
                'factura' => $this->factura,
                'cliente' => $this->factura->cliente,
                'estado' => $this->factura->estado,
                'formaPago' => $this->factura->formaPago,
            ]);

        $pdfPath = storage_path('app/public/' . $this->factura->archivo);
        if ($this->factura->archivo && file_exists($pdfPath) && is_readable($pdfPath)) {
            $mail->attach($pdfPath, [
                'as' => 'Factura-' . $this->factura->numero . '.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
