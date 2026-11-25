<x-app-layout titulo="Clientes">
    <x-slot name="header">
        <div>
            <p class="eyebrow">Ventas</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight">Clientes</h1>
            <p class="mt-1.5 text-ink-2">{{ $clientes->total() }} clientes registrados.</p>
        </div>
        @can('administrar')
            <a href="{{ route('clientes.create') }}" class="btn btn-primary self-start py-3 pl-5 pr-2.5 sm:self-auto">Nuevo cliente <span class="btn-orb"><x-icono nombre="mas" clase="size-4" /></span></a>
        @endcan
    </x-slot>

    <div class="panel overflow-hidden">
        <form method="GET" class="flex flex-col gap-3 border-b border-line p-4 sm:flex-row sm:items-center" role="search">
            <label for="q" class="sr-only">Buscar clientes</label>
            <div class="relative flex-1">
                <x-icono nombre="buscar" clase="pointer-events-none absolute left-4 top-1/2 size-[1.1rem] -translate-y-1/2 text-ink-3" />
                <input id="q" name="q" value="{{ $q }}" type="search" placeholder="Buscar por nombre, correo o RFC" class="field !rounded-full pl-11">
            </div>
            <button class="btn btn-dark">Buscar</button>
            @if ($q)<a href="{{ route('clientes.index') }}" class="btn btn-ghost">Limpiar</a>@endif
        </form>

        <div class="overflow-x-auto">
            <table class="tabla">
                <thead>
                    <tr><th>Cliente</th><th>RFC</th><th>Teléfono</th><th class="!text-right">Facturas</th><th class="!text-right">Facturado</th><th class="!text-right"><span class="sr-only">Acciones</span></th></tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>
                                <a href="{{ route('clientes.show', $cliente) }}" class="flex items-center gap-3">
                                    <x-avatar-cliente :cliente="$cliente" />
                                    <span class="min-w-0">
                                        <span class="block truncate font-semibold hover:text-brand">{{ $cliente->nombre }}</span>
                                        <span class="block truncate text-xs text-ink-3">{{ $cliente->email }}</span>
                                    </span>
                                </a>
                            </td>
                            <td class="num text-ink-2">{{ $cliente->rfc ?? 'Sin RFC' }}</td>
                            <td class="num whitespace-nowrap text-ink-2">{{ $cliente->telefono }}</td>
                            <td class="num text-right">{{ $cliente->facturas_count }}</td>
                            <td class="num text-right font-semibold">${{ number_format($cliente->facturas_sum_total ?? 0, 2) }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('clientes.show', $cliente) }}" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2 hover:text-ink" aria-label="Ver {{ $cliente->nombre }}"><x-icono nombre="ojo" clase="size-[1.1rem]" /></a>
                                    @can('administrar')
                                        <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2 hover:text-ink" aria-label="Editar {{ $cliente->nombre }}"><x-icono nombre="editar" clase="size-[1.1rem]" /></a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-16 text-center text-ink-3">No hay clientes que coincidan con "{{ $q }}".</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-line p-4">{{ $clientes->links() }}</div>
    </div>
</x-app-layout>
