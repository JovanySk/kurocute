<?php

namespace Database\Seeders;

use App\Models\EstadoFactura;
use App\Models\FormaPago;
use App\Models\Perfil;
use Illuminate\Database\Seeder;

/**
 * Catalogos base que la aplicacion necesita para funcionar.
 */
class CatalogosSeeder extends Seeder
{
    public function run(): void
    {
        Perfil::firstOrCreate(['nombre' => Perfil::ADMINISTRADOR], [
            'descripcion' => 'Acceso completo: crea, edita y elimina clientes, facturas, productos y pedidos.',
            'estado' => 'activo',
            'fecha_registro' => now()->subYear(),
        ]);

        Perfil::firstOrCreate(['nombre' => Perfil::CONSULTA], [
            'descripcion' => 'Solo lectura del back office. Perfil asignado a los registros nuevos.',
            'estado' => 'activo',
            'fecha_registro' => now()->subYear(),
        ]);

        foreach ([
            EstadoFactura::PENDIENTE => 'Pendiente',
            EstadoFactura::PAGADA => 'Pagada',
            EstadoFactura::VENCIDA => 'Vencida',
            EstadoFactura::CANCELADA => 'Cancelada',
        ] as $clave => $nombre) {
            EstadoFactura::firstOrCreate(['clave' => $clave], ['nombre' => $nombre]);
        }

        foreach (['Transferencia', 'Tarjeta de crédito', 'Tarjeta de débito', 'Efectivo', 'PayPal'] as $forma) {
            FormaPago::firstOrCreate(['nombre' => $forma]);
        }
    }
}
