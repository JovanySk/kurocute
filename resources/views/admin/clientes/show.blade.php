<x-app-layout :titulo="$cliente->nombre">
    <x-slot name="header">
        <div class="flex items-center gap-5">
            <x-avatar-cliente :cliente="$cliente" tamano="size-20 text-2xl" />
            <div>
                <p class="eyebrow">Cliente</p>
                <h1 class="mt-3 font-display text-3xl font-semibold tracking-tight">{{ $cliente->nombre }}</h1>
                <p class="mt-1 text-ink-2">{{ $cliente->email }}</p>
            </div>
        </div>
        @can('administrar')
            <div class="flex gap-2">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-ghost"><x-icono nombre="editar" clase="size-4" /> Editar</a>
                <a href="{{ route('facturas.create', ['cliente' => $cliente->id]) }}" class="btn btn-primary py-3 pl-5 pr-2.5">Facturar <span class="btn-orb"><x-icono nombre="mas" clase="size-4" /></span></a>
            </div>
        @endcan
    </x-slot>

    <div class="grid gap-4 lg:grid-cols-3">
        <section class="panel p-6">
            <h2 class="font-semibold">Datos fiscales</h2>
            <dl class="mt-5 space-y-4 text-sm">
                @foreach (['RFC' => $cliente->rfc ?? 'Sin RFC', 'Teléfono' => $cliente->telefono, 'Dirección' => $cliente->direccion, 'Cliente desde' => $cliente->created_at->translatedFormat('F Y')] as $k => $v)
                    <div><dt class="text-ink-3">{{ $k }}</dt><dd class="mt-0.5 font-medium">{{ $v }}</dd></div>
                @endforeach
                @if ($cliente->descripcion)
                    <div><dt class="text-ink-3">Notas</dt><dd class="mt-0.5 text-ink-2">{{ $cliente->descripcion }}</dd></div>
                @endif
            </dl>
            <div class="mt-6 grid grid-cols-2 gap-3 border-t border-line pt-5">
                <div><p class="text-xs text-ink-3">Facturado</p><p class="num font-display text-xl font-semibold">${{ number_format($cliente->facturas->where('estado.clave', '!=', 'cancelada')->sum('total'), 0) }}</p></div>
                <div><p class="text-xs text-ink-3">Pedidos</p><p class="num font-display text-xl font-semibold">{{ $cliente->pedidos->count() }}</p></div>
            </div>
            @can('administrar')
                <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" class="mt-6" x-data @submit="if (! confirm('Eliminar a {{ $cliente->nombre }} también elimina sus facturas y pedidos. ¿Continuar?')) $event.preventDefault()">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm"><x-icono nombre="borrar" clase="size-4" /> Eliminar cliente</button>
                </form>
            @endcan
        </section>

        <section class="panel overflow-hidden lg:col-span-2">
            <h2 class="p-6 pb-2 font-semibold">Facturas</h2>
            <div class="overflow-x-auto">
                <table class="tabla">
                    <thead><tr><th>Folio</th><th>Fecha</th><th>Estado</th><th class="!text-right">Total</th></tr></thead>
                    <tbody>
                        @forelse ($cliente->facturas as $f)
                            <tr>
                                <td><a class="num font-semibold hover:text-brand" href="{{ route('facturas.show', $f) }}">{{ $f->numero }}</a></td>
                                <td class="num text-ink-2">{{ $f->fecha_emision->format('d/m/Y') }}</td>
                                <td><x-estado-factura :estado="$f->estado" /></td>
                                <td class="num text-right font-semibold">${{ number_format($f->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-12 text-center text-ink-3">Este cliente aún no tiene facturas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
