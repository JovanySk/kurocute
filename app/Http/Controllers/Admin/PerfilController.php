<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PerfilController extends Controller
{
    public function index(): View
    {
        return view('admin.perfiles.index', [
            'perfiles' => Perfil::withCount('users')->orderBy('nombre')->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.perfiles.form', ['perfil' => new Perfil(['estado' => 'activo', 'fecha_registro' => now()])]);
    }

    public function store(Request $request): RedirectResponse
    {
        Perfil::create($this->validar($request));

        return redirect()->route('perfiles.index')->with('success', 'Perfil creado correctamente.');
    }

    public function edit(Perfil $perfil): View
    {
        return view('admin.perfiles.form', compact('perfil'));
    }

    public function update(Request $request, Perfil $perfil): RedirectResponse
    {
        $perfil->update($this->validar($request, $perfil));

        return redirect()->route('perfiles.index')->with('success', 'Perfil actualizado correctamente.');
    }

    public function destroy(Perfil $perfil): RedirectResponse
    {
        if ($perfil->nombre === Perfil::ADMINISTRADOR) {
            return back()->with('error', 'El perfil Administrador no se puede eliminar.');
        }

        $perfil->delete();

        return redirect()->route('perfiles.index')->with('success', 'Perfil eliminado correctamente.');
    }

    private function validar(Request $request, ?Perfil $perfil = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:60', Rule::unique('perfiles', 'nombre')->ignore($perfil)],
            'fecha_registro' => ['required', 'date'],
            'estado' => ['required', Rule::in(['activo', 'inactivo'])],
            'descripcion' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
