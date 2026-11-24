<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Formaspago;
use App\Models\Cliente;
use App\Models\EstadoFactura;

class Factura extends Model
{
    use HasFactory;

    protected $table = 'facturas';

    protected $fillable = [
        'numero',
        'detalles',
        'valor',
        'archivo',
        'idcliente',
        'idestado',
        'idforma'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idcliente');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoFactura::class, 'idestado');
    }

    public function formaPago()
    {
        return $this->belongsTo(Formaspago::class, 'idforma');
    }
}
