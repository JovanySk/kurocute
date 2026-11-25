<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Secciones del catálogo (antes "secciones"), ahora categorías de producto.
 */
class CategoriaController extends Controller
{
    public function index(): View
    {
        return view('admin.categorias.index', [
            'categorias' => Categoria::withCount('productos')->orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        Categoria::create($datos + ['slug' => Str::slug($datos['nombre'])]);

        return back()->with('success', 'Categoría creada.');
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $datos = $this->validar($request, $categoria);
        $categoria->update($datos + ['slug' => Str::slug($datos['nombre'])]);

        return back()->with('success', 'Categoría actualizada.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        $categoria->delete();

        return back()->with('success', 'Categoría eliminada. Sus productos quedaron sin categoría.');
    }

    private function validar(Request $request, ?Categoria $categoria = null): array
    {
        $request->merge(['slug' => Str::slug((string) $request->input('nombre'))]);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:60'],
            'slug' => [Rule::unique('categorias', 'slug')->ignore($categoria)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ], ['slug.unique' => 'Ya existe una categoría con ese nombre.']);

        unset($datos['slug']);

        return $datos;
    }
}
