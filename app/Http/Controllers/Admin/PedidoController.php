<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstadoFactura;
use App\Models\Factura;
use App\Models\FormaPago;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(Request $request): View
    {
        $estado = (string) $request->query('estado', '');
        $q = trim((string) $request->query('q', ''));

        $pedidos = Pedido::with(['cliente', 'factura'])
            ->withCount('items')
            ->when($estado, fn ($query) => $query->where('estado', $estado))
            ->when($q, fn ($query) => $query->where(fn ($w) => $w
                ->where('folio', 'like', "%{$q}%")
                ->orWhereHas('cliente', fn ($c) => $c->where('nombre', 'like', "%{$q}%"))))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.pedidos.index', compact('pedidos', 'estado', 'q'));
    }

    public function show(Pedido $pedido): View
    {
        $pedido->load(['cliente', 'items.producto', 'factura.estado']);

        return view('admin.pedidos.show', [
            'pedido' => $pedido,
            'formasPago' => FormaPago::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $datos = $request->validate(['estado' => ['required', Rule::in(array_keys(Pedido::ESTADOS))]]);
        $pedido->update($datos);

        return back()->with('success', "Pedido {$pedido->folio} marcado como {$pedido->estadoNombre()}.");
    }

    /** Genera la factura del pedido (precios de tienda con IVA incluido). */
    public function facturar(Request $request, Pedido $pedido): RedirectResponse
    {
        if ($pedido->factura) {
            return redirect()->route('facturas.show', $pedido->factura)->with('warning', 'Este pedido ya tiene factura.');
        }

        abort_if($pedido->estado === 'cancelado', 422, 'No se puede facturar un pedido cancelado.');

        $datos = $request->validate(['forma_pago_id' => ['required', 'exists:formaspago,id']]);

        $factura = Factura::create([
            'numero' => Factura::siguienteNumero(),
            'cliente_id' => $pedido->cliente_id,
            'pedido_id' => $pedido->id,
            'estado_id' => EstadoFactura::idDe(EstadoFactura::PENDIENTE),
            'forma_pago_id' => $datos['forma_pago_id'],
            'fecha_emision' => now(),
            'fecha_vencimiento' => now()->addDays(15),
            'detalles' => "Pedido en línea {$pedido->folio}",
        ] + Factura::importesDesdeTotal((float) $pedido->total));

        return redirect()->route('facturas.show', $factura)->with('success', "Factura {$factura->numero} generada desde el pedido {$pedido->folio}.");
    }
}
