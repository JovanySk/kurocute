<x-app-layout :titulo="$factura->numero">
    <x-slot name="header">
        <div>
            <a href="{{ route('facturas.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink-3 hover:text-ink"><x-icono nombre="izquierda" clase="size-4" /> Facturas</a>
            <div class="mt-3 flex flex-wrap items-center gap-3">
                <h1 class="num font-display text-3xl font-semibold tracking-tight">{{ $factura->numero }}</h1>
                <x-estado-factura :estado="$factura->estado" />
            </div>
            <p class="mt-1.5 text-ink-2">Emitida el {{ $factura->fecha_emision->translatedFormat('j \\d\\e F \\d\\e Y') }} para {{ $factura->cliente->nombre }}.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('facturas.correo', $factura) }}" target="_blank" class="btn btn-ghost"><x-icono nombre="correo" clase="size-4" /> Ver correo</a>
            @can('administrar')
                <form method="POST" action="{{ route('facturas.reenviar', $factura) }}">
                    @csrf
                    <button class="btn btn-ghost"><x-icono nombre="enviar" clase="size-4" /> Reenviar</button>
                </form>
                <a href="{{ route('facturas.edit', $factura) }}" class="btn btn-ghost"><x-icono nombre="editar" clase="size-4" /> Editar</a>
            @endcan
            <a href="{{ route('facturas.pdf', $factura) }}" class="btn btn-primary py-3 pl-5 pr-2.5">Descargar PDF <span class="btn-orb"><x-icono nombre="descargar" clase="size-4" /></span></a>
        </div>
    </x-slot>

    <div class="grid gap-4 xl:grid-cols-[1fr_22rem]">
        {{-- Documento --}}
        <article class="shell">
            <div class="core overflow-hidden">
                <div class="flex flex-col justify-between gap-6 bg-night p-8 text-white sm:flex-row">
                    <div>
                        <span class="inline-flex items-center gap-2.5"><x-logo :texto="false" tamano="size-9" /><span class="font-display text-lg font-semibold">kuro<span class="text-[#f06aa8]">cute</span></span></span>
                        <p class="mt-3 text-sm text-white/60">KUROCUTE TIENDA DEMO<br>RFC KCU250101AB1</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-xs uppercase tracking-[0.2em] text-white/50">Factura</p>
                        <p class="num mt-1 font-display text-2xl font-semibold">{{ $factura->numero }}</p>
                        <p class="num mt-1 text-sm text-white/60">Vence {{ $factura->fecha_vencimiento?->format('d/m/Y') ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="grid gap-6 p-8 sm:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-ink-3">Facturar a</p>
                        <a href="{{ route('clientes.show', $factura->cliente) }}" class="mt-2 block font-semibold hover:text-brand">{{ $factura->cliente->nombre }}</a>
                        <p class="num text-sm text-ink-2">{{ $factura->cliente->rfc ?? 'Sin RFC' }}</p>
                        <p class="text-sm text-ink-2">{{ $factura->cliente->direccion }}</p>
                        <p class="text-sm text-ink-2">{{ $factura->cliente->email }}</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-ink-3">Forma de pago</p>
                        <p class="mt-2 font-semibold">{{ $factura->formaPago?->nombre }}</p>
                        @if ($factura->pedido)
                            <p class="mt-4 text-xs font-semibold uppercase tracking-[0.16em] text-ink-3">Pedido</p>
                            <a href="{{ route('pedidos.show', $factura->pedido) }}" class="num mt-2 inline-block font-semibold hover:text-brand">{{ $factura->pedido->folio }}</a>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto px-4">
                    <table class="tabla">
                        <thead><tr><th>Concepto</th><th class="!text-right">Cant.</th><th class="!text-right">Precio unitario</th><th class="!text-right">Importe</th></tr></thead>
                        <tbody>
                            @foreach ($conceptos as $c)
                                <tr>
                                    <td>{{ $c['descripcion'] }}</td>
                                    <td class="num text-right">{{ $c['cantidad'] }}</td>
                                    <td class="num text-right">${{ number_format($c['unitario'], 2) }}</td>
                                    <td class="num text-right font-semibold">${{ number_format($c['importe'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <dl class="ml-auto max-w-xs space-y-2 p-8 text-sm">
                    <div class="flex justify-between"><dt class="text-ink-2">Subtotal</dt><dd class="num">${{ number_format($factura->subtotal, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-2">IVA 16%</dt><dd class="num">${{ number_format($factura->iva, 2) }}</dd></div>
                    <div class="flex items-baseline justify-between border-t border-line pt-3"><dt class="font-semibold">Total MXN</dt><dd class="num font-display text-2xl font-semibold">${{ number_format($factura->total, 2) }}</dd></div>
                </dl>
            </div>
        </article>

        <aside class="space-y-4">
            <section class="panel p-6">
                <h2 class="font-semibold">Comprobantes adjuntos</h2>
                <ul class="mt-4 space-y-2">
                    @foreach (['archivo_pdf' => ['pdf', 'PDF timbrado'], 'archivo_xml' => ['xml', 'XML timbrado']] as $campo => [$icono, $nombre])
                        <li>
                            @if ($factura->{$campo})
                                <a href="{{ Storage::disk('public')->url($factura->{$campo}) }}" target="_blank" class="flex items-center gap-3 rounded-2xl bg-surface-2 p-3 ring-1 ring-line transition hover:ring-brand/50">
                                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-brand-soft text-brand"><x-icono :nombre="$icono" clase="size-5" /></span>
                                    <span class="min-w-0 flex-1"><span class="block text-sm font-semibold">{{ $nombre }}</span><span class="block truncate text-xs text-ink-3">{{ basename($factura->{$campo}) }}</span></span>
                                    <x-icono nombre="flecha" clase="size-4 text-ink-3" />
                                </a>
                            @else
                                <div class="flex items-center gap-3 rounded-2xl p-3 text-sm text-ink-3 ring-1 ring-line"><x-icono :nombre="$icono" clase="size-5" /> Sin {{ strtolower($nombre) }}</div>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
            @if ($factura->detalles)
                <section class="panel p-6">
                    <h2 class="font-semibold">Notas</h2>
                    <p class="mt-2 text-sm text-ink-2">{{ $factura->detalles }}</p>
                </section>
            @endif
            @can('administrar')
                <form method="POST" action="{{ route('facturas.destroy', $factura) }}" x-data @submit="if (! confirm('¿Eliminar la factura {{ $factura->numero }}?')) $event.preventDefault()">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm"><x-icono nombre="borrar" clase="size-4" /> Eliminar factura</button>
                </form>
            @endcan
        </aside>
    </div>
</x-app-layout>
