<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'rfc', 'direccion', 'telefono', 'email', 'descripcion', 'imagen'];

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    public function scopeBuscar(Builder $query, ?string $texto): Builder
    {
        return $query->when($texto, function (Builder $q, string $texto) {
            $q->where(function (Builder $q) use ($texto) {
                $q->where('nombre', 'like', "%{$texto}%")
                    ->orWhere('email', 'like', "%{$texto}%")
                    ->orWhere('rfc', 'like', "%{$texto}%");
            });
        });
    }

    public function imagenUrl(): ?string
    {
        return $this->imagen ? Storage::disk('public')->url($this->imagen) : null;
    }

    public function iniciales(): string
    {
        return collect(explode(' ', trim($this->nombre)))
            ->filter()
            ->take(2)
            ->map(fn (string $parte) => mb_strtoupper(mb_substr($parte, 0, 1)))
            ->implode('');
    }
}
