<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Support\Carrito;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CarritoController extends Controller
{
    public function __construct(private Carrito $carrito) {}

    public function index(): View
    {
        return view('tienda.carrito', [
            'partidas' => $this->carrito->partidas(),
            'total' => $this->carrito->total(),
        ]);
    }

    public function resumen(): JsonResponse
    {
        return response()->json($this->carrito->resumen());
    }

    public function agregar(Request $request): JsonResponse|RedirectResponse
    {
        $datos = $request->validate([
            'producto_id' => ['required', 'integer', 'exists:productos,id'],
            'cantidad' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $producto = Producto::visibles()->findOrFail($datos['producto_id']);

        if ($producto->stock < 1) {
            throw ValidationException::withMessages(['producto_id' => 'Este producto está agotado por ahora.']);
        }

        $this->carrito->agregar($producto, $datos['cantidad'] ?? 1);

        return $this->responder($request, "Agregaste {$producto->nombre} a tu pedido.");
    }

    public function actualizar(Request $request, Producto $producto): JsonResponse|RedirectResponse
    {
        $datos = $request->validate(['cantidad' => ['required', 'integer', 'min:0', 'max:20']]);
        $this->carrito->actualizar($producto, $datos['cantidad']);

        return $this->responder($request, 'Pedido actualizado.');
    }

    public function quitar(Request $request, Producto $producto): JsonResponse|RedirectResponse
    {
        $this->carrito->quitar($producto);

        return $this->responder($request, 'Producto retirado del pedido.');
    }

    /** Convierte el carrito en un pedido y registra (o reutiliza) al cliente. */
    public function confirmar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telefono' => ['required', 'string', 'max:20'],
            'direccion' => ['required', 'string', 'max:255'],
            'rfc' => ['nullable', 'string', 'min:12', 'max:13'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ]);

        $partidas = $this->carrito->partidas();

        if ($partidas->isEmpty()) {
            return redirect()->route('carrito.index')->with('error', 'Tu pedido está vacío.');
        }

        $pedido = DB::transaction(function () use ($datos, $partidas) {
            $cliente = Cliente::firstOrCreate(
                ['email' => mb_strtolower($datos['email'])],
                [
                    'nombre' => $datos['nombre'],
                    'telefono' => $datos['telefono'],
                    'direccion' => $datos['direccion'],
                    'rfc' => isset($datos['rfc']) ? mb_strtoupper($datos['rfc']) : null,
                ],
            );

            $pedido = Pedido::create([
                'folio' => Pedido::siguienteFolio(),
                'cliente_id' => $cliente->id,
                'estado' => 'nuevo',
                'total' => $partidas->sum('subtotal'),
                'notas' => $datos['notas'] ?? null,
            ]);

            foreach ($partidas as $partida) {
                $pedido->items()->create([
                    'producto_id' => $partida['producto']->id,
                    'nombre' => $partida['producto']->nombre,
                    'cantidad' => $partida['cantidad'],
                    'precio_unitario' => $partida['producto']->precio,
                    'subtotal' => $partida['subtotal'],
                ]);
                $partida['producto']->decrement('stock', $partida['cantidad']);
            }

            return $pedido;
        });

        $this->carrito->vaciar();
        $request->session()->put('ultimo_pedido', $pedido->folio);

        return redirect()->route('carrito.gracias', $pedido->folio);
    }

    public function gracias(Request $request, string $folio): View
    {
        // Solo quien acaba de confirmar el pedido puede ver su resumen.
        abort_unless($request->session()->get('ultimo_pedido') === $folio, 404);

        $pedido = Pedido::with(['items', 'cliente'])->where('folio', $folio)->firstOrFail();

        return view('tienda.gracias', compact('pedido'));
    }

    private function responder(Request $request, string $mensaje): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['mensaje' => $mensaje] + $this->carrito->resumen());
        }

        return back()->with('success', $mensaje);
    }
}
