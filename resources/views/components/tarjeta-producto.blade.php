@props(['producto', 'retraso' => 0, 'giro' => 0])
<article class="reveal group" style="--delay: {{ $retraso }}ms">
    <div class="sticker overflow-hidden transition duration-500 ease-[var(--ease-spring)] group-hover:-translate-y-1.5 group-hover:rotate-[var(--giro)]" style="--giro: {{ $giro }}deg">
        <a href="{{ route('tienda.producto', $producto->slug) }}" class="relative block aspect-square overflow-hidden border-b-[3px] border-sticker" aria-label="Ver {{ $producto->nombre }}">
            <x-producto-arte :producto="$producto" class="transition duration-700 ease-[var(--ease-spring)] group-hover:scale-105" />
            @if ($producto->destacado)
                <span class="absolute left-3 top-3 inline-flex items-center gap-1 rounded-full border-2 border-sticker bg-accent px-2.5 py-0.5 font-display text-xs font-semibold text-on-accent">
                    <x-deco tipo="corazon" clase="size-3.5 text-white" /> Favorito
                </span>
            @endif
            @if ($producto->stock > 0 && $producto->stock <= 5)
                <span class="absolute right-3 top-3 rounded-full border-2 border-sticker bg-surface px-2.5 py-0.5 font-display text-xs font-semibold text-ink">Últimas {{ $producto->stock }}</span>
            @endif
        </a>
        <div class="flex items-end justify-between gap-3 p-4">
            <div class="min-w-0">
                <p class="text-xs font-extrabold uppercase tracking-wider text-brand">{{ $producto->categoria?->nombre }}</p>
                <h3 class="mt-0.5 line-clamp-2 font-display text-lg font-semibold leading-tight"><a href="{{ route('tienda.producto', $producto->slug) }}">{{ $producto->nombre }}</a></h3>
                <p class="num mt-1.5 font-display text-xl font-bold text-accent-strong">${{ number_format($producto->precio, 2) }}</p>
            </div>
            @if ($producto->stock > 0)
                <button type="button" x-data @click="$store.carrito.agregar({{ $producto->id }})"
                    class="wiggle inline-flex size-11 shrink-0 items-center justify-center rounded-full border-[2.5px] border-sticker bg-brand text-white shadow-[2px_2px_0_var(--sticker)] transition hover:bg-accent active:translate-y-0.5"
                    aria-label="Agregar {{ $producto->nombre }} al pedido">
                    <x-icono nombre="mas" clase="size-5" />
                </button>
            @else
                <span class="badge badge-muted">Agotado</span>
            @endif
        </div>
    </div>
</article>
