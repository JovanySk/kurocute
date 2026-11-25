<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    use HasFactory;

    public const TASA_IVA = 0.16;

    protected $fillable = [
        'numero', 'cliente_id', 'pedido_id', 'estado_id', 'forma_pago_id', 'fecha_emision',
        'fecha_vencimiento', 'detalles', 'subtotal', 'iva', 'total', 'archivo_pdf', 'archivo_xml',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
            'subtotal' => 'decimal:2',
            'iva' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(EstadoFactura::class, 'estado_id');
    }

    public function formaPago(): BelongsTo
    {
        return $this->belongsTo(FormaPago::class, 'forma_pago_id');
    }

    /** Calcula IVA y total a partir de un subtotal (precio sin impuestos). */
    public static function importesDesdeSubtotal(float $subtotal): array
    {
        $iva = round($subtotal * self::TASA_IVA, 2);

        return ['subtotal' => round($subtotal, 2), 'iva' => $iva, 'total' => round($subtotal + $iva, 2)];
    }

    /** Desglosa un total con IVA incluido (precios de la tienda). */
    public static function importesDesdeTotal(float $total): array
    {
        $subtotal = round($total / (1 + self::TASA_IVA), 2);

        return ['subtotal' => $subtotal, 'iva' => round($total - $subtotal, 2), 'total' => round($total, 2)];
    }

    public static function siguienteNumero(?int $anio = null): string
    {
        $anio ??= (int) now()->format('Y');
        $prefijo = "KC-{$anio}-";
        $ultimo = static::where('numero', 'like', $prefijo.'%')->orderByDesc('numero')->value('numero');
        $consecutivo = $ultimo ? ((int) substr($ultimo, strlen($prefijo))) + 1 : 1;

        return $prefijo.str_pad((string) $consecutivo, 4, '0', STR_PAD_LEFT);
    }

    public function scopeFiltrar(Builder $query, array $filtros): Builder
    {
        return $query
            ->when($filtros['q'] ?? null, function (Builder $q, string $texto) {
                $q->where(function (Builder $q) use ($texto) {
                    $q->where('numero', 'like', "%{$texto}%")
                        ->orWhereHas('cliente', fn (Builder $c) => $c->where('nombre', 'like', "%{$texto}%"));
                });
            })
            ->when($filtros['estado'] ?? null, fn (Builder $q, string $clave) => $q->whereHas('estado', fn (Builder $e) => $e->where('clave', $clave)))
            ->when($filtros['forma_pago'] ?? null, fn (Builder $q, $id) => $q->where('forma_pago_id', $id))
            ->when($filtros['desde'] ?? null, fn (Builder $q, $fecha) => $q->whereDate('fecha_emision', '>=', $fecha))
            ->when($filtros['hasta'] ?? null, fn (Builder $q, $fecha) => $q->whereDate('fecha_emision', '<=', $fecha));
    }

    /** Conceptos para PDF y correo: partidas del pedido o un concepto único. */
    public function conceptos(): array
    {
        if ($this->pedido && $this->pedido->items->isNotEmpty()) {
            $conceptos = $this->pedido->items->map(function (PedidoItem $item) {
                $importe = round((float) $item->subtotal / (1 + self::TASA_IVA), 2);

                return [
                    'descripcion' => $item->nombre,
                    'cantidad' => $item->cantidad,
                    'unitario' => round($importe / max(1, $item->cantidad), 2),
                    'importe' => $importe,
                ];
            })->all();

            // El redondeo por partida se ajusta en la última para que cuadre con el subtotal.
            $diferencia = round((float) $this->subtotal - array_sum(array_column($conceptos, 'importe')), 2);
            if (abs($diferencia) > 0 && abs($diferencia) < 0.1) {
                $conceptos[array_key_last($conceptos)]['importe'] = round($conceptos[array_key_last($conceptos)]['importe'] + $diferencia, 2);
            }

            return $conceptos;
        }

        return [[
            'descripcion' => $this->detalles ? trim(strip_tags($this->detalles)) : 'Productos KuroCute',
            'cantidad' => 1,
            'unitario' => (float) $this->subtotal,
            'importe' => (float) $this->subtotal,
        ]];
    }
}
