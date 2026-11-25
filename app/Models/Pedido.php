<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    use HasFactory;

    public const ESTADOS = [
        'nuevo' => 'Nuevo',
        'en_proceso' => 'En proceso',
        'enviado' => 'Enviado',
        'entregado' => 'Entregado',
        'cancelado' => 'Cancelado',
    ];

    protected $fillable = ['folio', 'cliente_id', 'estado', 'total', 'notas'];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function factura(): HasOne
    {
        return $this->hasOne(Factura::class);
    }

    public function estadoNombre(): string
    {
        return self::ESTADOS[$this->estado] ?? ucfirst($this->estado);
    }

    public static function siguienteFolio(): string
    {
        $ultimo = (int) static::max('id');

        return 'PED-'.str_pad((string) ($ultimo + 1), 5, '0', STR_PAD_LEFT);
    }
}
