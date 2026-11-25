{{-- Cajón lateral con el pedido en curso --}}
<div x-data x-show="$store.carrito.abierto" x-cloak class="fixed inset-0 z-40" role="dialog" aria-modal="true" aria-label="Mi pedido"
    @keydown.escape.window="$store.carrito.abierto = false">
    <div class="absolute inset-0 bg-night/50 backdrop-blur-sm" x-show="$store.carrito.abierto" x-transition.opacity.duration.300ms @click="$store.carrito.abierto = false"></div>

    <aside x-show="$store.carrito.abierto" x-trap.noscroll="$store.carrito.abierto"
        x-transition:enter="transition duration-500 ease-[var(--ease-spring)]" x-transition:enter-start="translate-x-full"
        x-transition:leave="transition duration-300 ease-[var(--ease-out-soft)]" x-transition:leave-end="translate-x-full"
        class="absolute inset-y-2 right-2 flex w-[calc(100%-1rem)] max-w-md flex-col overflow-hidden rounded-[1.75rem] border-[3px] border-sticker bg-surface shadow-sticker">
        <div class="relative flex items-end justify-between overflow-hidden bg-brand px-6 pb-4 pt-6 text-white">
            <img src="{{ asset('images/descarga3.jpg') }}" alt="" class="pointer-events-none absolute inset-0 size-full object-cover opacity-35">
            <div class="relative">
                <p class="font-display text-sm font-semibold text-white/80">Tu carrito</p>
                <h2 class="font-display text-3xl font-bold">Mi pedido</h2>
            </div>
            <button type="button" @click="$store.carrito.abierto = false" class="relative inline-flex size-10 items-center justify-center rounded-full border-2 border-white/60 bg-white/15 hover:bg-white/25" aria-label="Cerrar">
                <x-icono nombre="cerrar" clase="size-5" />
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6">
            <template x-if="$store.carrito.partidas.length === 0">
                <div class="flex h-full flex-col items-center justify-center py-12 text-center">
                    <img src="{{ asset('images/ima5.jpg') }}" alt="Kuromi dormida" class="medalla size-36">
                    <p class="mt-5 font-display text-2xl font-bold">Tu pedido está vacío</p>
                    <p class="mt-1 max-w-[16rem] text-ink-2">Kuromi se aburre. Agrega algo lindo desde el catálogo.</p>
                    <a href="{{ route('tienda.catalogo') }}" class="btn btn-primary mt-6">Ver catálogo</a>
                </div>
            </template>

            <ul class="divide-y-2 divide-dashed divide-line">
                <template x-for="p in $store.carrito.partidas" :key="p.id">
                    <li class="flex gap-4 py-4">
                        <div class="foto size-20 shrink-0 overflow-hidden rounded-2xl border-2 border-line-strong">
                            <img :src="p.imagen" :alt="p.nombre" class="size-full object-contain p-1 mix-blend-multiply dark:mix-blend-normal">
                        </div>
                        <div class="flex min-w-0 flex-1 flex-col">
                            <p class="truncate font-bold" x-text="p.nombre"></p>
                            <p class="text-xs text-ink-3" x-text="p.categoria"></p>
                            <div class="mt-auto flex items-center justify-between pt-2">
                                <div class="inline-flex items-center rounded-full border-2 border-line-strong">
                                    <button type="button" class="inline-flex size-8 items-center justify-center text-ink-2 hover:text-accent" @click="$store.carrito.cambiar(p.id, p.cantidad - 1)" aria-label="Quitar uno"><x-icono nombre="menos" clase="size-4" /></button>
                                    <span class="num w-6 text-center text-sm font-extrabold" x-text="p.cantidad"></span>
                                    <button type="button" class="inline-flex size-8 items-center justify-center text-ink-2 hover:text-accent disabled:opacity-30" :disabled="p.cantidad >= p.stock" @click="$store.carrito.cambiar(p.id, p.cantidad + 1)" aria-label="Agregar uno"><x-icono nombre="mas" clase="size-4" /></button>
                                </div>
                                <p class="num font-display text-lg font-bold text-brand" x-text="p.subtotal_formato"></p>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>

        <div class="border-t-[3px] border-dashed border-line-strong p-6" x-show="$store.carrito.partidas.length > 0">
            <div class="flex items-baseline justify-between">
                <span class="text-ink-2">Total (IVA incluido)</span>
                <span class="num font-display text-3xl font-bold" x-text="$store.carrito.total_formato"></span>
            </div>
            <a href="{{ route('carrito.index') }}" class="btn btn-primary mt-5 w-full justify-between py-3.5 pl-6 pr-3">
                Confirmar pedido
                <span class="btn-orb"><x-icono nombre="flecha" clase="size-4" /></span>
            </a>
        </div>
    </aside>
</div>
