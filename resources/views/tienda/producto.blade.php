<x-layouts.tienda :titulo="$producto->nombre">
    <section class="mx-auto max-w-6xl px-4 pt-10 sm:px-6">
        <nav class="font-bold text-ink-3" aria-label="Ruta">
            <a href="{{ route('tienda.catalogo') }}" class="hover:text-brand">Catálogo</a>
            @if ($producto->categoria)
                <span class="px-1.5 text-accent">&#9733;</span>
                <a href="{{ route('tienda.catalogo', ['categoria' => $producto->categoria->slug]) }}" class="hover:text-brand">{{ $producto->categoria->nombre }}</a>
            @endif
        </nav>

        <div class="mt-6 grid gap-10 lg:grid-cols-2 lg:gap-14">
            <div class="reveal relative">
                <x-deco tipo="estrella" clase="twinkle absolute -left-3 -top-4 z-10 size-10 text-[#ffd84d]" />
                <x-deco tipo="corazon" clase="twinkle absolute -bottom-3 right-6 z-10 size-9 text-accent [animation-delay:700ms]" />
                <div class="sticker overflow-hidden rotate-[-1.5deg]">
                    <div class="aspect-square"><x-producto-arte :producto="$producto" ajuste="object-contain p-6" /></div>
                </div>
            </div>

            <div class="reveal flex flex-col lg:py-4" style="--delay: 120ms" x-data="{ cantidad: 1 }">
                <p class="eyebrow">{{ $producto->categoria?->nombre ?? 'KuroCute' }}</p>
                <h1 class="mt-4 font-display text-4xl font-bold leading-tight tracking-tight sm:text-5xl">{{ $producto->nombre }}</h1>
                <p class="num mt-4 font-display text-4xl font-bold text-accent-strong">${{ number_format($producto->precio, 2) }} <span class="align-middle font-sans text-sm font-bold text-ink-3">MXN, IVA incluido</span></p>
                <p class="mt-5 max-w-lg text-lg font-semibold leading-relaxed text-ink-2">{{ $producto->descripcion }}</p>

                <ul class="mt-7 grid max-w-lg gap-3 font-bold text-ink-2 sm:grid-cols-2">
                    <li class="flex items-center gap-3 rounded-2xl border-2 border-line-strong bg-surface p-3"><x-icono nombre="camion" clase="size-5 text-brand" /> Envío nacional en 48 h</li>
                    <li class="flex items-center gap-3 rounded-2xl border-2 border-line-strong bg-surface p-3"><x-icono nombre="factura" clase="size-5 text-brand" /> Factura PDF por correo</li>
                </ul>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    @if ($producto->stock > 0)
                        <div class="inline-flex items-center self-start rounded-full border-[2.5px] border-sticker bg-surface">
                            <button type="button" class="inline-flex size-12 items-center justify-center text-ink-2 hover:text-accent" @click="cantidad = Math.max(1, cantidad - 1)" aria-label="Menos"><x-icono nombre="menos" clase="size-4" /></button>
                            <span class="num w-8 text-center font-display text-lg font-bold" x-text="cantidad" aria-live="polite"></span>
                            <button type="button" class="inline-flex size-12 items-center justify-center text-ink-2 hover:text-accent" @click="cantidad = Math.min({{ $producto->stock }}, cantidad + 1)" aria-label="Más"><x-icono nombre="mas" clase="size-4" /></button>
                        </div>
                        <button type="button" class="btn btn-primary py-3.5 sm:min-w-64" @click="$store.carrito.agregar({{ $producto->id }}, cantidad)">
                            <x-icono nombre="bolsa" clase="size-5" /> Agregar al pedido
                        </button>
                    @else
                        <span class="badge badge-muted">Agotado por ahora</span>
                    @endif
                </div>
                <p class="mt-4 font-extrabold {{ $producto->stock > 5 ? 'text-ok' : 'text-warn' }}">
                    {{ $producto->stock > 5 ? 'Disponible para envío inmediato' : ($producto->stock > 0 ? "Quedan {$producto->stock} piezas" : 'Te avisamos cuando vuelva') }}
                </p>
                <div class="mt-8 flex items-center gap-3 rounded-2xl border-2 border-dashed border-line-strong p-3">
                    <img src="{{ asset('images/descarga4.jpg') }}" alt="" class="medalla size-12 !border-2">
                    <p class="text-sm font-bold text-ink-2">Cada pedido sale envuelto con cariño y una estampita de Kuromi.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($relacionados->isNotEmpty())
        <section class="mx-auto max-w-6xl px-4 pt-20 sm:px-6">
            <h2 class="reveal font-display text-3xl font-bold tracking-tight">También te puede gustar</h2>
            <div class="mt-7 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($relacionados as $i => $r)
                    <x-tarjeta-producto :producto="$r" :retraso="$i * 70" :giro="[-1.5, 1.5, -1, 1][$i % 4]" />
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.tienda>
