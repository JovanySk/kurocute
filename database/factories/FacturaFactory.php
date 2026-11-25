<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\EstadoFactura;
use App\Models\Factura;
use App\Models\FormaPago;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Requiere los catalogos (CatalogosSeeder) para estados y formas de pago.
 *
 * @extends Factory<Factura>
 */
class FacturaFactory extends Factory
{
    public function definition(): array
    {
        $fecha = fake()->dateTimeBetween('-6 months');

        return [
            'numero' => 'KC-'.$fecha->format('Y').'-'.fake()->unique()->numerify('9###'),
            'cliente_id' => Cliente::factory(),
            'estado_id' => fn () => EstadoFactura::idDe(EstadoFactura::PENDIENTE),
            'forma_pago_id' => fn () => FormaPago::query()->value('id'),
            'fecha_emision' => $fecha,
            'fecha_vencimiento' => (clone $fecha)->modify('+15 days'),
            'detalles' => 'Productos de la tienda KuroCute',
        ] + Factura::importesDesdeSubtotal(fake()->randomFloat(2, 300, 8000));
    }

    public function estado(string $clave): static
    {
        return $this->state(fn () => ['estado_id' => EstadoFactura::idDe($clave)]);
    }
}
