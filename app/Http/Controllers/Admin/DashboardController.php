<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\EstadoFactura;
use App\Models\Factura;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $facturas = Factura::with(['estado', 'cliente'])->get();
        $hoy = now();

        $porEstado = fn (string $clave) => $facturas->filter(fn (Factura $f) => $f->estado?->clave === $clave);

        $cobradas = $porEstado(EstadoFactura::PAGADA);
        $porCobrar = $porEstado(EstadoFactura::PENDIENTE)->merge($porEstado(EstadoFactura::VENCIDA));

        $mesActual = $cobradas->filter(fn (Factura $f) => $f->fecha_emision->isSameMonth($hoy))->sum('total');
        $mesAnterior = $cobradas->filter(fn (Factura $f) => $f->fecha_emision->isSameMonth($hoy->copy()->subMonthNoOverflow()))->sum('total');

        // Ingresos de los últimos 12 meses: cobrado vs facturado pendiente.
        $meses = collect(range(11, 0))->map(fn (int $i) => $hoy->copy()->startOfMonth()->subMonthsNoOverflow($i));
        $serie = $meses->map(function (Carbon $mes) use ($facturas) {
            $delMes = $facturas->filter(fn (Factura $f) => $f->fecha_emision->isSameMonth($mes));

            return [
                'etiqueta' => ucfirst($mes->translatedFormat('M y')),
                'cobrado' => round($delMes->filter(fn ($f) => $f->estado?->clave === EstadoFactura::PAGADA)->sum('total'), 2),
                'pendiente' => round($delMes->filter(fn ($f) => in_array($f->estado?->clave, [EstadoFactura::PENDIENTE, EstadoFactura::VENCIDA], true))->sum('total'), 2),
            ];
        });

        $estados = EstadoFactura::orderBy('id')->get()->map(fn (EstadoFactura $e) => [
            'clave' => $e->clave,
            'nombre' => $e->nombre,
            'cantidad' => $porEstado($e->clave)->count(),
            'monto' => round($porEstado($e->clave)->sum('total'), 2),
        ]);

        $topClientes = $facturas
            ->reject(fn (Factura $f) => $f->estado?->clave === EstadoFactura::CANCELADA)
            ->groupBy('cliente_id')
            ->map(fn ($grupo) => [
                'cliente' => $grupo->first()->cliente,
                'total' => round($grupo->sum('total'), 2),
                'facturas' => $grupo->count(),
            ])
            ->sortByDesc('total')
            ->take(5)
            ->values();

        return view('admin.dashboard', [
            'kpis' => [
                'cobrado' => round($cobradas->sum('total'), 2),
                'cobrado_mes' => round($mesActual, 2),
                'variacion_mes' => $mesAnterior > 0 ? round((($mesActual - $mesAnterior) / $mesAnterior) * 100, 1) : null,
                'por_cobrar' => round($porCobrar->sum('total'), 2),
                'vencidas' => $porEstado(EstadoFactura::VENCIDA)->count(),
                'facturas' => $facturas->count(),
                'ticket_promedio' => $facturas->count() ? round($facturas->avg('total'), 2) : 0,
                'clientes' => Cliente::count(),
                'pedidos_nuevos' => Pedido::where('estado', 'nuevo')->count(),
                'stock_bajo' => Producto::visibles()->where('stock', '<=', 5)->count(),
            ],
            'serie' => $serie,
            'estados' => $estados,
            'topClientes' => $topClientes,
            'recientes' => Factura::with(['cliente', 'estado'])->latest('fecha_emision')->latest('id')->take(6)->get(),
            'pedidosRecientes' => Pedido::with('cliente')->latest()->take(5)->get(),
        ]);
    }
}
