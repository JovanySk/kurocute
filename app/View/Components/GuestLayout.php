<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Layout de las pantallas de acceso (login, registro, contrasena).
 */
class GuestLayout extends Component
{
    public function __construct(public ?string $titulo = null) {}

    public function render(): View
    {
        return view('layouts.guest');
    }
}
