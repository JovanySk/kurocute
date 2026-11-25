<x-app-layout :titulo="$pedido->folio">
    <x-slot name="header">
        <div>
            <a href="{{ route('pedidos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink-3 hover:text-ink"><x-icono nombre="izquierda" clase="size-4" /> Pedidos</a>
            <div class="mt-3 flex flex-wrap items-center gap-3">
                <h1 class="num font-display text-3xl font-semibold tracking-tight">{{ $pedido->folio }}</h1>
                <x-estado-pedido :pedido="$pedido" />
            </div>
            <p class="mt-1.5 text-ink-2">Recibido el {{ $pedido->created_at->translatedFormat('j \\d\\e F \\a \\l\\a\\s H:i') }}.</p>
        </div>
    </x-slot>

    <div class="grid gap-4 lg:grid-cols-[1fr_22rem]">
        <section class="panel overflow-hidden">
            <h2 class="p-6 pb-2 font-semibold">Productos</h2>
            <table class="tabla">
                <thead><tr><th>Producto</th><th class="!text-right">Cant.</th><th class="!text-right">Precio</th><th class="!text-right">Subtotal</th></tr></thead>
                <tbody>
                    @foreach ($pedido->items as $item)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if ($item->producto)<div class="size-11 shrink-0 overflow-hidden rounded-xl ring-1 ring-line"><x-producto-arte :producto="$item->producto" /></div>@endif
                                    <span class="font-medium">{{ $item->nombre }}</span>
                                </div>
                            </td>
                            <td class="num text-right">{{ $item->cantidad }}</td>
                            <td class="num text-right">${{ number_format($item->precio_unitario, 2) }}</td>
                            <td class="num text-right font-semibold">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex items-baseline justify-end gap-6 border-t border-line p-6">
                <span class="text-sm text-ink-2">Total (IVA incluido)</span>
                <span class="num font-display text-2xl font-semibold">${{ number_format($pedido->total, 2) }}</span>
            </div>
            @if ($pedido->notas)
                <div class="border-t border-line p-6 text-sm"><p class="text-ink-3">Notas del cliente</p><p class="mt-1">{{ $pedido->notas }}</p></div>
            @endif
        </section>

        <aside class="space-y-4">
            <section class="panel p-6">
                <h2 class="font-semibold">Cliente</h2>
                <a href="{{ route('clientes.show', $pedido->cliente) }}" class="mt-4 flex items-center gap-3">
                    <x-avatar-cliente :cliente="$pedido->cliente" />
                    <span class="min-w-0"><span class="block truncate font-semibold hover:text-brand">{{ $pedido->cliente->nombre }}</span><span class="block truncate text-xs text-ink-3">{{ $pedido->cliente->email }}</span></span>
                </a>
                <p class="mt-4 text-sm text-ink-2">{{ $pedido->cliente->direccion }}</p>
                <p class="num text-sm text-ink-2">{{ $pedido->cliente->telefono }}</p>
            </section>

            @can('administrar')
                <section class="panel p-6">
                    <h2 class="font-semibold">Estado del pedido</h2>
                    <form method="POST" action="{{ route('pedidos.update', $pedido) }}" class="mt-4 flex gap-2">
                        @csrf @method('PATCH')
                        <label for="estado" class="sr-only">Estado</label>
                        <select id="estado" name="estado" class="field">
                            @foreach (\App\Models\Pedido::ESTADOS as $clave => $nombre)
                                <option value="{{ $clave }}" @selected($pedido->estado === $clave)>{{ $nombre }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-dark">Guardar</button>
                    </form>
                </section>
            @endcan

            <section class="panel p-6">
                <h2 class="font-semibold">Factura</h2>
                @if ($pedido->factura)
                    <a href="{{ route('facturas.show', $pedido->factura) }}" class="mt-4 flex items-center justify-between rounded-2xl bg-surface-2 p-4 ring-1 ring-line hover:ring-brand/50">
                        <span><span class="num block font-semibold">{{ $pedido->factura->numero }}</span><span class="num text-sm text-ink-3">${{ number_format($pedido->factura->total, 2) }}</span></span>
                        <x-estado-factura :estado="$pedido->factura->estado" />
                    </a>
                @elseif ($pedido->estado === 'cancelado')
                    <p class="mt-2 text-sm text-ink-3">Los pedidos cancelados no se facturan.</p>
                @else
                    @can('administrar')
                        <form method="POST" action="{{ route('pedidos.facturar', $pedido) }}" class="mt-4 space-y-3">
                            @csrf
                            <label for="forma_pago_id" class="label">Forma de pago</label>
                            <select id="forma_pago_id" name="forma_pago_id" class="field">
                                @foreach ($formasPago as $fp)<option value="{{ $fp->id }}">{{ $fp->nombre }}</option>@endforeach
                            </select>
                            <button class="btn btn-primary w-full justify-between py-3 pl-5 pr-2.5">Generar factura <span class="btn-orb"><x-icono nombre="factura" clase="size-4" /></span></button>
                        </form>
                    @else
                        <p class="mt-2 text-sm text-ink-3">Este pedido aún no tiene factura.</p>
                    @endcan
                @endif
            </section>
        </aside>
    </div>
</x-app-layout>
