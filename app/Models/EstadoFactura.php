<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoFactura extends Model
{
    public const PENDIENTE = 'pendiente';

    public const PAGADA = 'pagada';

    public const VENCIDA = 'vencida';

    public const CANCELADA = 'cancelada';

    protected $table = 'estadosfacturas';

    public $timestamps = false;

    protected $fillable = ['clave', 'nombre'];

    public static function idDe(string $clave): int
    {
        return (int) static::where('clave', $clave)->value('id');
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class, 'estado_id');
    }
}
