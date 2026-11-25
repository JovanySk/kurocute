<x-app-layout titulo="Productos">
    <x-slot name="header">
        <div>
            <p class="eyebrow">Catálogo</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight">Productos</h1>
            <p class="mt-1.5 text-ink-2">{{ $productos->total() }} productos en el catálogo.</p>
        </div>
        @can('administrar')
            <a href="{{ route('productos.create') }}" class="btn btn-primary self-start py-3 pl-5 pr-2.5 sm:self-auto">Nuevo producto <span class="btn-orb"><x-icono nombre="mas" clase="size-4" /></span></a>
        @endcan
    </x-slot>

    <div class="panel overflow-hidden">
        <form method="GET" class="grid gap-3 border-b border-line p-4 sm:grid-cols-[1fr_14rem_auto]" role="search">
            <div class="relative">
                <label for="q" class="sr-only">Buscar productos</label>
                <x-icono nombre="buscar" clase="pointer-events-none absolute left-4 top-1/2 size-[1.1rem] -translate-y-1/2 text-ink-3" />
                <input id="q" name="q" value="{{ $q }}" type="search" placeholder="Buscar producto" class="field !rounded-full pl-11">
            </div>
            <label for="categoria" class="sr-only">Categoría</label>
            <select id="categoria" name="categoria" class="field !rounded-full">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $c)
                    <option value="{{ $c->slug }}" @selected($categoria === $c->slug)>{{ $c->nombre }}</option>
                @endforeach
            </select>
            <button class="btn btn-dark">Filtrar</button>
        </form>
        <div class="overflow-x-auto">
            <table class="tabla">
                <thead><tr><th>Producto</th><th>Categoría</th><th class="!text-right">Precio</th><th class="!text-right">Stock</th><th>Estado</th><th><span class="sr-only">Acciones</span></th></tr></thead>
                <tbody>
                    @forelse ($productos as $p)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="size-12 shrink-0 overflow-hidden rounded-xl ring-1 ring-line"><x-producto-arte :producto="$p" /></div>
                                    <div class="min-w-0">
                                        <a href="{{ route('tienda.producto', $p->slug) }}" target="_blank" class="block truncate font-semibold hover:text-brand">{{ $p->nombre }}</a>
                                        @if ($p->destacado)<span class="text-xs text-accent-strong">Favorito en portada</span>@endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-ink-2">{{ $p->categoria?->nombre ?? 'Sin categoría' }}</td>
                            <td class="num text-right font-semibold">${{ number_format($p->precio, 2) }}</td>
                            <td class="num text-right {{ $p->stock <= 5 ? 'font-semibold text-danger' : '' }}">{{ $p->stock }}</td>
                            <td>
                                @if (! $p->activo)<span class="badge badge-muted">Oculto</span>
                                @elseif ($p->stock === 0)<span class="badge badge-danger">Agotado</span>
                                @elseif ($p->stock <= 5)<span class="badge badge-warn">Stock bajo</span>
                                @else<span class="badge badge-ok">Disponible</span>@endif
                            </td>
                            <td>
                                @can('administrar')
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('productos.edit', $p) }}" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2 hover:text-ink" aria-label="Editar {{ $p->nombre }}"><x-icono nombre="editar" clase="size-[1.1rem]" /></a>
                                    </div>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-16 text-center text-ink-3">No hay productos con estos filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-line p-4">{{ $productos->links() }}</div>
    </div>
</x-app-layout>
