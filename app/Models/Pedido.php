<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = '_pedidos';

    protected $fillable = [
        'numero',
        'idproducto',
        'cantidad',
        'precio'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idproducto');
    }
}
