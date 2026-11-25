<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perfil extends Model
{
    use HasFactory;

    public const ADMINISTRADOR = 'Administrador';

    public const CONSULTA = 'Consulta';

    protected $table = 'perfiles';

    protected $fillable = ['nombre', 'descripcion', 'estado', 'fecha_registro'];

    protected function casts(): array
    {
        return ['fecha_registro' => 'date'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'perfil_id');
    }
}
