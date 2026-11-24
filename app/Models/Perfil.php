<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfiles'; // Especifica la tabla si no es "perfils"

    protected $fillable = ['nombre', 'fechaRegistro', 'estadoUsuario', 'descripcion']; // Incluye los campos que usas en el formulario

    // Relación con el modelo User
    public function users()
    {
        return $this->hasMany(User::class, 'perfil_id');
    }
}
