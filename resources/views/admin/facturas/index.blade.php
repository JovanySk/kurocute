<x-app-layout titulo="Facturas">
    <x-slot name="header">
        <div>
            <p class="eyebrow">Ventas</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight">Facturas</h1>
            <p class="mt-1.5 text-ink-2"><span class="num font-semibold text-ink">{{ $facturas->total() }}</span> facturas por <span class="num font-semibold text-ink">${{ number_format($totalFiltrado, 2) }}</span> con los filtros actuales.</p>
        </div>
        @can('administrar')
            <a href="{{ route('facturas.create') }}" class="btn btn-primary self-start py-3 pl-5 pr-2.5 sm:self-auto">Nueva factura <span class="btn-orb"><x-icono nombre="mas" clase="size-4" /></span></a>
        @endcan
    </x-slot>

    {{-- Pestañas por estado --}}
    @php $actual = $filtros['estado'] ?? ''; @endphp
    <div class="-mx-4 mb-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0" role="tablist" aria-label="Filtrar por estado">
        <a href="{{ route('facturas.index', array_merge(request()->except(['estado', 'page']))) }}" class="chip shrink-0" aria-pressed="{{ $actual === '' ? 'true' : 'false' }}">
            Todas <span class="num opacity-60">{{ $estados->sum('facturas_count') }}</span>
        </a>
        @foreach ($estados as $e)
            <a href="{{ route('facturas.index', array_merge(request()->except('page'), ['estado' => $e->clave])) }}" class="chip shrink-0" aria-pressed="{{ $actual === $e->clave ? 'true' : 'false' }}">
                {{ $e->nombre }} <span class="num opacity-60">{{ $e->facturas_count }}</span>
            </a>
        @endforeach
    </div>

    <div class="panel overflow-hidden">
        <form method="GET" class="grid gap-3 border-b border-line p-4 md:grid-cols-[1fr_12rem_9.5rem_9.5rem_auto]" role="search">
            @if ($actual)<input type="hidden" name="estado" value="{{ $actual }}">@endif
            <div class="relative">
                <label for="q" class="sr-only">Buscar</label>
                <x-icono nombre="buscar" clase="pointer-events-none absolute left-4 top-1/2 size-[1.1rem] -translate-y-1/2 text-ink-3" />
                <input id="q" name="q" value="{{ $filtros['q'] ?? '' }}" type="search" placeholder="Folio o cliente" class="field !rounded-full pl-11">
            </div>
            <div>
                <label for="forma_pago" class="sr-only">Forma de pago</label>
                <select id="forma_pago" name="forma_pago" class="field !rounded-full">
                    <option value="">Cualquier forma de pago</option>
                    @foreach ($formasPago as $fp)
                        <option value="{{ $fp->id }}" @selected(($filtros['forma_pago'] ?? '') == $fp->id)>{{ $fp->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="desde" class="sr-only">Desde</label>
                <input id="desde" type="date" name="desde" value="{{ $filtros['desde'] ?? '' }}" class="field !rounded-full" title="Desde">
            </div>
            <div>
                <label for="hasta" class="sr-only">Hasta</label>
                <input id="hasta" type="date" name="hasta" value="{{ $filtros['hasta'] ?? '' }}" class="field !rounded-full" title="Hasta">
            </div>
            <div class="flex gap-2">
                <button class="btn btn-dark flex-1"><x-icono nombre="filtro" clase="size-4" /> Filtrar</button>
                @if (array_filter($filtros))<a href="{{ route('facturas.index') }}" class="btn btn-ghost" aria-label="Limpiar filtros"><x-icono nombre="cerrar" clase="size-4" /></a>@endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="tabla">
                <thead>
                    <tr><th>Folio</th><th>Cliente</th><th>Emisión</th><th>Vence</th><th>Forma de pago</th><th>Estado</th><th class="!text-right">Total</th><th class="!text-right"><span class="sr-only">Acciones</span></th></tr>
                </thead>
                <tbody>
                    @forelse ($facturas as $f)
                        <tr>
                            <td>
                                <a href="{{ route('facturas.show', $f) }}" class="num font-semibold hover:text-brand">{{ $f->numero }}</a>
                                <span class="mt-0.5 flex gap-1.5 text-ink-3">
                                    @if ($f->archivo_pdf)<x-icono nombre="pdf" clase="size-3.5" title="Comprobante PDF cargado" />@endif
                                    @if ($f->archivo_xml)<x-icono nombre="xml" clase="size-3.5" title="XML cargado" />@endif
                                </span>
                            </td>
                            <td class="max-w-52"><a href="{{ route('clientes.show', $f->cliente) }}" class="block truncate hover:text-brand">{{ $f->cliente->nombre }}</a></td>
                            <td class="num whitespace-nowrap text-ink-2">{{ $f->fecha_emision->format('d/m/Y') }}</td>
                            <td class="num whitespace-nowrap {{ $f->estado?->clave === 'vencida' ? 'font-semibold text-danger' : 'text-ink-2' }}">{{ $f->fecha_vencimiento?->format('d/m/Y') ?? 'N/A' }}</td>
                            <td class="whitespace-nowrap text-ink-2">{{ $f->formaPago?->nombre }}</td>
                            <td><x-estado-factura :estado="$f->estado" /></td>
                            <td class="num whitespace-nowrap text-right font-semibold">${{ number_format($f->total, 2) }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('facturas.show', $f) }}" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2 hover:text-ink" aria-label="Ver factura {{ $f->numero }}"><x-icono nombre="ojo" clase="size-[1.1rem]" /></a>
                                    <a href="{{ route('facturas.pdf', $f) }}" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2 hover:text-ink" aria-label="Descargar PDF de {{ $f->numero }}"><x-icono nombre="descargar" clase="size-[1.1rem]" /></a>
                                    @can('administrar')
                                        <a href="{{ route('facturas.edit', $f) }}" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2 hover:text-ink" aria-label="Editar factura {{ $f->numero }}"><x-icono nombre="editar" clase="size-[1.1rem]" /></a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="py-16 text-center text-ink-3">No hay facturas con estos filtros.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-line p-4">{{ $facturas->links() }}</div>
    </div>
</x-app-layout>
