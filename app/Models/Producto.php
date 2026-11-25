<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'categoria_id', 'nombre', 'slug', 'descripcion', 'precio', 'stock', 'imagen', 'destacado', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'stock' => 'integer',
            'destacado' => 'boolean',
            'activo' => 'boolean',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function pedidoItems(): HasMany
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function scopeVisibles(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    /** Búsqueda del catálogo por texto y categoría. */
    public function scopeBuscar(Builder $query, ?string $texto, ?string $categoria = null): Builder
    {
        return $query
            ->when($texto, function (Builder $q, string $texto) {
                $q->where(function (Builder $q) use ($texto) {
                    $q->where('nombre', 'like', "%{$texto}%")
                        ->orWhere('descripcion', 'like', "%{$texto}%");
                });
            })
            ->when($categoria, fn (Builder $q, string $slug) => $q->whereHas('categoria', fn (Builder $c) => $c->where('slug', $slug)));
    }

    public function imagenUrl(): ?string
    {
        if (! $this->imagen) {
            return null;
        }

        return str_starts_with($this->imagen, 'http') ? $this->imagen : Storage::disk('public')->url($this->imagen);
    }

    /** Foto del producto o, si no tiene, una ilustracion de Kuromi del dueño de la tienda. */
    public function fotoUrl(): string
    {
        return $this->imagenUrl() ?? asset('images/descarga4.jpg');
    }

    public function paraCatalogo(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'slug' => $this->slug,
            'descripcion' => $this->descripcion,
            'precio' => (float) $this->precio,
            'precio_formato' => '$'.number_format((float) $this->precio, 2),
            'stock' => $this->stock,
            'destacado' => $this->destacado,
            'categoria' => $this->categoria?->nombre,
            'categoria_slug' => $this->categoria?->slug,
            'imagen' => $this->fotoUrl(),
            'url' => route('tienda.producto', $this->slug),
        ];
    }
}
