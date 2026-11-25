{{-- Cajon lateral con el pedido en curso --}}
<div x-data x-show="$store.carrito.abierto" x-cloak class="fixed inset-0 z-40" role="dialog" aria-modal="true" aria-label="Mi pedido"
    @keydown.escape.window="$store.carrito.abierto = false">
    <div class="absolute inset-0 bg-night/50 backdrop-blur-sm" x-show="$store.carrito.abierto" x-transition.opacity.duration.400ms @click="$store.carrito.abierto = false"></div>

    <aside x-show="$store.carrito.abierto" x-trap.noscroll="$store.carrito.abierto"
        x-transition:enter="transition duration-700 ease-[var(--ease-spring)]" x-transition:enter-start="translate-x-full"
        x-transition:leave="transition duration-400 ease-[var(--ease-out-soft)]" x-transition:leave-end="translate-x-full"
        class="absolute inset-y-2 right-2 flex w-[calc(100%-1rem)] max-w-md flex-col rounded-[1.75rem] bg-surface shadow-lift ring-1 ring-line">
        <div class="flex items-center justify-between px-6 pb-4 pt-6">
            <div>
                <p class="eyebrow">Tu pedido</p>
                <h2 class="mt-3 font-display text-2xl font-semibold">Mi pedido</h2>
            </div>
            <button type="button" @click="$store.carrito.abierto = false" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-line-strong hover:bg-surface-2" aria-label="Cerrar">
                <x-icono nombre="cerrar" clase="size-5" />
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-6">
            <template x-if="$store.carrito.partidas.length === 0">
                <div class="flex h-full flex-col items-center justify-center py-16 text-center">
                    <span class="inline-flex size-16 items-center justify-center rounded-full bg-brand-soft text-brand"><x-icono nombre="bolsa" clase="size-7" /></span>
                    <p class="mt-5 font-display text-lg font-semibold">Aún no agregas nada</p>
                    <p class="mt-1 max-w-[16rem] text-sm text-ink-2">Explora el catálogo y arma tu pedido. Lo confirmas en un solo paso.</p>
                    <a href="{{ route('tienda.catalogo') }}" class="btn btn-dark mt-6">Ver catálogo</a>
                </div>
            </template>

            <ul class="divide-y divide-line">
                <template x-for="p in $store.carrito.partidas" :key="p.id">
                    <li class="flex gap-4 py-4">
                        <div class="size-20 shrink-0 overflow-hidden rounded-2xl ring-1 ring-line">
                            <template x-if="p.imagen"><img :src="p.imagen" :alt="p.nombre" class="size-full object-cover"></template>
                            <template x-if="!p.imagen"><svg viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice" class="size-full"><use :href="'#kc-arte-' + p.variante"/></svg></template>
                        </div>
                        <div class="flex min-w-0 flex-1 flex-col">
                            <p class="truncate font-semibold" x-text="p.nombre"></p>
                            <p class="text-xs text-ink-3" x-text="p.categoria"></p>
                            <div class="mt-auto flex items-center justify-between pt-2">
                                <div class="inline-flex items-center rounded-full ring-1 ring-line-strong">
                                    <button type="button" class="inline-flex size-8 items-center justify-center text-ink-2 hover:text-ink" @click="$store.carrito.cambiar(p.id, p.cantidad - 1)" aria-label="Quitar uno"><x-icono nombre="menos" clase="size-4" /></button>
                                    <span class="num w-6 text-center text-sm font-semibold" x-text="p.cantidad"></span>
                                    <button type="button" class="inline-flex size-8 items-center justify-center text-ink-2 hover:text-ink disabled:opacity-30" :disabled="p.cantidad >= p.stock" @click="$store.carrito.cambiar(p.id, p.cantidad + 1)" aria-label="Agregar uno"><x-icono nombre="mas" clase="size-4" /></button>
                                </div>
                                <p class="num font-semibold" x-text="p.subtotal_formato"></p>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>
        </div>

        <div class="border-t border-line p-6" x-show="$store.carrito.partidas.length > 0">
            <div class="flex items-baseline justify-between">
                <span class="text-sm text-ink-2">Total (IVA incluido)</span>
                <span class="num font-display text-2xl font-semibold" x-text="$store.carrito.total_formato"></span>
            </div>
            <a href="{{ route('carrito.index') }}" class="btn btn-primary mt-5 w-full justify-between py-3.5 pl-6 pr-3">
                Confirmar pedido
                <span class="btn-orb"><x-icono nombre="flecha" clase="size-4" /></span>
            </a>
        </div>
    </aside>
</div>
