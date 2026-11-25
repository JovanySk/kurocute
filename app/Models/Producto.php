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

    /** Variante visual (0-5) para la ilustración SVG generada del producto. */
    public function variante(): int
    {
        // Se elige el motivo según el nombre; si no hay coincidencia, uno estable por slug.
        $motivos = [
            5 => ['planeta', 'globo'],
            1 => ['corazon'],
            3 => ['lazo', 'diadema', 'orejitas', 'pinzas'],
            2 => ['estrella', 'constelacion', 'galaxia'],
            4 => ['nube', 'cojin', 'manta', 'vela'],
            0 => ['luna'],
        ];

        foreach ($motivos as $variante => $palabras) {
            foreach ($palabras as $palabra) {
                if (str_contains((string) $this->slug, $palabra)) {
                    return $variante;
                }
            }
        }

        return crc32((string) $this->slug) % 6;
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
            'imagen' => $this->imagenUrl(),
            'variante' => $this->variante(),
            'url' => route('tienda.producto', $this->slug),
        ];
    }
}
