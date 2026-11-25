<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'slug', 'descripcion', 'imagen'];

    /** Portada de la categoria (imagen publica del proyecto) con una de Kuromi como respaldo. */
    public function portadaUrl(): string
    {
        return asset($this->imagen ?: 'images/ima3.jpg');
    }

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
