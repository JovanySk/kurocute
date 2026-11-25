<?php

namespace App\Support;

use App\Models\Factura;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;

/**
 * Genera la representacion impresa (PDF) de una factura.
 */
class FacturaPdf
{
    public static function crear(Factura $factura): DomPdf
    {
        $factura->loadMissing(['cliente', 'estado', 'formaPago', 'pedido.items']);

        return Pdf::loadView('pdf.factura', [
            'factura' => $factura,
            'conceptos' => $factura->conceptos(),
        ])->setPaper('letter');
    }

    public static function nombreArchivo(Factura $factura): string
    {
        return 'Factura-'.$factura->numero.'.pdf';
    }
}
