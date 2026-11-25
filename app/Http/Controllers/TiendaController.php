<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TiendaController extends Controller
{
    public function inicio(): View
    {
        $destacados = Producto::with('categoria')->visibles()->where('destacado', true)->orderBy('categoria_id')->orderBy('id')->take(8)->get();
        $categorias = Categoria::withCount(['productos' => fn ($q) => $q->where('activo', true)])->orderBy('id')->get();

        return view('tienda.inicio', [
            'destacados' => $destacados,
            'categorias' => $categorias,
            'totalProductos' => Producto::visibles()->count(),
        ]);
    }

    public function catalogo(Request $request): View
    {
        $filtros = $this->filtros($request);

        return view('tienda.catalogo', [
            'categorias' => Categoria::orderBy('id')->get(),
            'filtros' => $filtros,
            'productos' => $this->consulta($filtros)->map->paraCatalogo()->values(),
        ]);
    }

    /** Endpoint AJAX para el filtro instantaneo del catálogo. */
    public function buscar(Request $request): JsonResponse
    {
        $filtros = $this->filtros($request);
        $productos = $this->consulta($filtros);

        return response()->json([
            'total' => $productos->count(),
            'productos' => $productos->map->paraCatalogo()->values(),
        ]);
    }

    public function producto(Producto $producto): View
    {
        abort_unless($producto->activo, 404);

        $producto->load('categoria');

        $relacionados = Producto::with('categoria')
            ->visibles()
            ->where('id', '!=', $producto->id)
            ->where('categoria_id', $producto->categoria_id)
            ->take(4)
            ->get();

        return view('tienda.producto', compact('producto', 'relacionados'));
    }

    private function filtros(Request $request): array
    {
        $validados = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'categoria' => ['nullable', 'string', 'max:80'],
            'orden' => ['nullable', 'in:recientes,precio_asc,precio_desc,nombre'],
        ]);

        return [
            'q' => trim($validados['q'] ?? ''),
            'categoria' => $validados['categoria'] ?? '',
            'orden' => $validados['orden'] ?? 'recientes',
        ];
    }

    private function consulta(array $filtros)
    {
        $query = Producto::with('categoria')->visibles()->buscar($filtros['q'] ?: null, $filtros['categoria'] ?: null);

        match ($filtros['orden']) {
            'precio_asc' => $query->orderBy('precio'),
            'precio_desc' => $query->orderByDesc('precio'),
            'nombre' => $query->orderBy('nombre'),
            default => $query->orderByDesc('destacado')->latest('id'),
        };

        return $query->get();
    }
}
