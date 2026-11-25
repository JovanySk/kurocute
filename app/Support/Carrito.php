<?php

namespace App\Support;

use App\Models\Producto;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

/**
 * Carrito de compras guardado en la sesion: [producto_id => cantidad].
 */
class Carrito
{
    private const CLAVE = 'carrito';

    public function __construct(private Session $session) {}

    public function agregar(Producto $producto, int $cantidad = 1): void
    {
        $lineas = $this->lineas();
        $lineas[$producto->id] = min($producto->stock, ($lineas[$producto->id] ?? 0) + $cantidad);
        $this->guardar($lineas);
    }

    public function actualizar(Producto $producto, int $cantidad): void
    {
        $lineas = $this->lineas();

        if ($cantidad <= 0) {
            unset($lineas[$producto->id]);
        } else {
            $lineas[$producto->id] = min($producto->stock, $cantidad);
        }

        $this->guardar($lineas);
    }

    public function quitar(Producto $producto): void
    {
        $this->actualizar($producto, 0);
    }

    public function vaciar(): void
    {
        $this->session->forget(self::CLAVE);
    }

    /** @return array<int, int> */
    public function lineas(): array
    {
        return $this->session->get(self::CLAVE, []);
    }

    /** Partidas con producto, cantidad y subtotal (solo productos activos). */
    public function partidas(): Collection
    {
        $lineas = $this->lineas();

        if ($lineas === []) {
            return collect();
        }

        return Producto::with('categoria')
            ->visibles()
            ->whereIn('id', array_keys($lineas))
            ->get()
            ->map(fn (Producto $producto) => [
                'producto' => $producto,
                'cantidad' => $lineas[$producto->id],
                'subtotal' => round((float) $producto->precio * $lineas[$producto->id], 2),
            ])
            ->values();
    }

    public function total(): float
    {
        return round($this->partidas()->sum('subtotal'), 2);
    }

    public function cantidad(): int
    {
        return array_sum($this->lineas());
    }

    /** Representacion JSON para el cajon del carrito (Alpine). */
    public function resumen(): array
    {
        $partidas = $this->partidas();

        return [
            'cantidad' => $this->cantidad(),
            'total' => round($partidas->sum('subtotal'), 2),
            'total_formato' => '$'.number_format($partidas->sum('subtotal'), 2),
            'partidas' => $partidas->map(fn (array $p) => [
                'id' => $p['producto']->id,
                'nombre' => $p['producto']->nombre,
                'categoria' => $p['producto']->categoria?->nombre,
                'imagen' => $p['producto']->fotoUrl(),
                'cantidad' => $p['cantidad'],
                'stock' => $p['producto']->stock,
                'precio_formato' => '$'.number_format((float) $p['producto']->precio, 2),
                'subtotal_formato' => '$'.number_format($p['subtotal'], 2),
            ])->all(),
        ];
    }

    private function guardar(array $lineas): void
    {
        $this->session->put(self::CLAVE, array_filter($lineas, fn (int $cantidad) => $cantidad > 0));
    }
}
