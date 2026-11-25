<x-app-layout titulo="Pedidos">
    <x-slot name="header">
        <div>
            <p class="eyebrow">Ventas</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight">Pedidos de la tienda</h1>
            <p class="mt-1.5 text-ink-2">Pedidos confirmados desde el carrito público.</p>
        </div>
    </x-slot>

    <div class="-mx-4 mb-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0">
        <a href="{{ route('pedidos.index', ['q' => $q ?: null]) }}" class="chip shrink-0" aria-pressed="{{ $estado === '' ? 'true' : 'false' }}">Todos</a>
        @foreach (\App\Models\Pedido::ESTADOS as $clave => $nombre)
            <a href="{{ route('pedidos.index', ['estado' => $clave, 'q' => $q ?: null]) }}" class="chip shrink-0" aria-pressed="{{ $estado === $clave ? 'true' : 'false' }}">{{ $nombre }}</a>
        @endforeach
    </div>

    <div class="panel overflow-hidden">
        <form method="GET" class="flex gap-3 border-b border-line p-4" role="search">
            @if ($estado)<input type="hidden" name="estado" value="{{ $estado }}">@endif
            <div class="relative flex-1">
                <label for="q" class="sr-only">Buscar pedidos</label>
                <x-icono nombre="buscar" clase="pointer-events-none absolute left-4 top-1/2 size-[1.1rem] -translate-y-1/2 text-ink-3" />
                <input id="q" name="q" value="{{ $q }}" type="search" placeholder="Folio o cliente" class="field !rounded-full pl-11">
            </div>
            <button class="btn btn-dark">Buscar</button>
        </form>
        <div class="overflow-x-auto">
            <table class="tabla">
                <thead><tr><th>Folio</th><th>Cliente</th><th>Fecha</th><th class="!text-right">Piezas</th><th>Estado</th><th>Factura</th><th class="!text-right">Total</th></tr></thead>
                <tbody>
                    @forelse ($pedidos as $p)
                        <tr>
                            <td><a href="{{ route('pedidos.show', $p) }}" class="num font-semibold hover:text-brand">{{ $p->folio }}</a></td>
                            <td class="max-w-52 truncate">{{ $p->cliente->nombre }}</td>
                            <td class="num whitespace-nowrap text-ink-2">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                            <td class="num text-right">{{ $p->items_count }}</td>
                            <td><x-estado-pedido :pedido="$p" /></td>
                            <td>
                                @if ($p->factura)
                                    <a href="{{ route('facturas.show', $p->factura) }}" class="num link text-sm">{{ $p->factura->numero }}</a>
                                @else
                                    <span class="text-sm text-ink-3">Sin facturar</span>
                                @endif
                            </td>
                            <td class="num text-right font-semibold">${{ number_format($p->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-16 text-center text-ink-3">No hay pedidos con estos filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-line p-4">{{ $pedidos->links() }}</div>
    </div>
</x-app-layout>
