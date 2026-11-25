<x-layouts.tienda :titulo="$producto->nombre">
    <section class="mx-auto max-w-6xl px-4 pt-10 sm:px-6">
        <nav class="text-sm text-ink-3" aria-label="Ruta">
            <a href="{{ route('tienda.catalogo') }}" class="hover:text-ink">Catálogo</a>
            @if ($producto->categoria)
                <span class="px-1.5">/</span>
                <a href="{{ route('tienda.catalogo', ['categoria' => $producto->categoria->slug]) }}" class="hover:text-ink">{{ $producto->categoria->nombre }}</a>
            @endif
        </nav>

        <div class="mt-8 grid gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="reveal">
                <div class="shell">
                    <div class="core overflow-hidden">
                        <div class="aspect-square"><x-producto-arte :producto="$producto" /></div>
                    </div>
                </div>
            </div>

            <div class="reveal flex flex-col lg:py-6" style="--delay: 120ms" x-data="{ cantidad: 1 }">
                <p class="eyebrow self-start">{{ $producto->categoria?->nombre ?? 'KuroCute' }}</p>
                <h1 class="mt-6 font-display text-4xl font-semibold leading-tight tracking-tight sm:text-5xl">{{ $producto->nombre }}</h1>
                <p class="num mt-5 font-display text-4xl font-semibold text-brand">${{ number_format($producto->precio, 2) }} <span class="align-middle text-sm font-normal text-ink-3">MXN, IVA incluido</span></p>
                <p class="mt-6 max-w-lg text-lg leading-relaxed text-ink-2">{{ $producto->descripcion }}</p>

                <ul class="mt-8 grid max-w-lg gap-3 text-sm text-ink-2 sm:grid-cols-2">
                    <li class="flex items-center gap-3 rounded-2xl bg-surface p-3 ring-1 ring-line"><x-icono nombre="camion" clase="size-5 text-brand" /> Envío nacional en 48 h</li>
                    <li class="flex items-center gap-3 rounded-2xl bg-surface p-3 ring-1 ring-line"><x-icono nombre="factura" clase="size-5 text-brand" /> Factura PDF por correo</li>
                </ul>

                <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-center">
                    @if ($producto->stock > 0)
                        <div class="inline-flex items-center self-start rounded-full bg-surface ring-1 ring-line-strong">
                            <button type="button" class="inline-flex size-12 items-center justify-center text-ink-2 hover:text-ink" @click="cantidad = Math.max(1, cantidad - 1)" aria-label="Menos"><x-icono nombre="menos" clase="size-4" /></button>
                            <span class="num w-8 text-center font-semibold" x-text="cantidad" aria-live="polite"></span>
                            <button type="button" class="inline-flex size-12 items-center justify-center text-ink-2 hover:text-ink" @click="cantidad = Math.min({{ $producto->stock }}, cantidad + 1)" aria-label="Más"><x-icono nombre="mas" clase="size-4" /></button>
                        </div>
                        <button type="button" class="btn btn-primary justify-between py-3.5 pl-6 pr-3 sm:min-w-64" @click="$store.carrito.agregar({{ $producto->id }}, cantidad)">
                            Agregar al pedido <span class="btn-orb"><x-icono nombre="bolsa" clase="size-4" /></span>
                        </button>
                    @else
                        <span class="badge badge-muted">Agotado por ahora</span>
                    @endif
                </div>
                <p class="mt-4 text-sm {{ $producto->stock > 5 ? 'text-ok' : 'text-warn' }}">
                    {{ $producto->stock > 5 ? 'Disponible para envío inmediato' : ($producto->stock > 0 ? "Quedan {$producto->stock} piezas" : 'Te avisamos cuando vuelva') }}
                </p>
            </div>
        </div>
    </section>

    @if ($relacionados->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 pt-24 sm:px-6">
            <h2 class="reveal font-display text-2xl font-semibold tracking-tight sm:text-3xl">También te puede gustar</h2>
            <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($relacionados as $i => $r)
                    <x-tarjeta-producto :producto="$r" :retraso="$i * 80" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.tienda>
