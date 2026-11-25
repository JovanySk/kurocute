@props(['producto', 'retraso' => 0])
<article class="reveal group" style="--delay: {{ $retraso }}ms">
    <div class="shell transition duration-700 ease-[var(--ease-spring)] group-hover:-translate-y-1.5">
        <div class="core overflow-hidden">
            <a href="{{ route('tienda.producto', $producto->slug) }}" class="relative block aspect-[4/5] overflow-hidden" aria-label="Ver {{ $producto->nombre }}">
                <x-producto-arte :producto="$producto" class="size-full transition duration-[1.2s] ease-[var(--ease-out-soft)] group-hover:scale-[1.06]" />
                @if ($producto->destacado)
                    <span class="absolute left-3 top-3 rounded-full bg-surface/85 px-2.5 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-ink backdrop-blur">Favorito</span>
                @endif
                @if ($producto->stock > 0 && $producto->stock <= 5)
                    <span class="absolute right-3 top-3 rounded-full bg-accent px-2.5 py-1 text-[0.68rem] font-semibold text-on-accent">Últimas {{ $producto->stock }}</span>
                @endif
            </a>
            <div class="flex items-end justify-between gap-3 p-4">
                <div class="min-w-0">
                    <p class="text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-ink-3">{{ $producto->categoria?->nombre }}</p>
                    <h3 class="mt-1 truncate font-semibold text-ink"><a href="{{ route('tienda.producto', $producto->slug) }}">{{ $producto->nombre }}</a></h3>
                    <p class="num mt-1 font-display text-lg font-semibold text-brand">${{ number_format($producto->precio, 2) }}</p>
                </div>
                @if ($producto->stock > 0)
                    <button type="button" x-data @click="$store.carrito.agregar({{ $producto->id }})"
                        class="inline-flex size-11 shrink-0 items-center justify-center rounded-full bg-ink text-bg transition duration-500 ease-[var(--ease-spring)] hover:scale-105 hover:bg-accent hover:text-on-accent active:scale-95"
                        aria-label="Agregar {{ $producto->nombre }} al pedido">
                        <x-icono nombre="mas" clase="size-5" />
                    </button>
                @else
                    <span class="badge badge-muted">Agotado</span>
                @endif
            </div>
        </div>
    </div>
</article>
