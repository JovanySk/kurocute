<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = '_productos';

    protected $fillable = [
        'nombre',
        'precio',
        'cantidad'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'idproducto');
    }
}
