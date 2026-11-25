<?php

namespace Tests\Unit;

use App\Models\Factura;
use PHPUnit\Framework\TestCase;

class ImportesFacturaTest extends TestCase
{
    public function test_calcula_iva_desde_subtotal(): void
    {
        $this->assertSame(['subtotal' => 1000.0, 'iva' => 160.0, 'total' => 1160.0], Factura::importesDesdeSubtotal(1000));
    }

    public function test_desglosa_iva_de_un_total_con_iva_incluido(): void
    {
        $importes = Factura::importesDesdeTotal(1160);

        $this->assertSame(1000.0, $importes['subtotal']);
        $this->assertSame(160.0, $importes['iva']);
        $this->assertEqualsWithDelta($importes['total'], $importes['subtotal'] + $importes['iva'], 0.001);
    }

    public function test_el_desglose_cuadra_con_centavos(): void
    {
        $importes = Factura::importesDesdeTotal(459.99);

        $this->assertEqualsWithDelta(459.99, $importes['subtotal'] + $importes['iva'], 0.001);
    }
}
