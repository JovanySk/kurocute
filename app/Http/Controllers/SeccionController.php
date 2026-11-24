<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index()
    {
        return view('Secciones.index');
    }
}
