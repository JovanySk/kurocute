<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'formaspago';

    public $timestamps = false;

    protected $fillable = ['nombre'];
}
