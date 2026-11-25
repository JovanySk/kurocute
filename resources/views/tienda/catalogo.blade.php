<x-layouts.tienda titulo="Catálogo">
    <section class="mx-auto max-w-6xl px-4 pb-10 pt-14 sm:px-6"
        x-data="catalogo(@js(['productos' => $productos, 'filtros' => $filtros]))">

        <div class="reveal flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div>
                <p class="eyebrow">Catálogo</p>
                <h1 class="mt-5 font-display text-4xl font-semibold tracking-tight sm:text-5xl">Todo lo que brilla de noche</h1>
                <p class="mt-3 text-ink-2">Escribe y los resultados se filtran al instante.</p>
            </div>

            <div class="w-full lg:max-w-md" role="search">
                <label for="buscar" class="sr-only">Buscar en el catálogo</label>
                <div class="shell !rounded-full">
                    <div class="core flex items-center gap-3 !rounded-full py-1.5 pl-5 pr-2 transition duration-500 ease-[var(--ease-spring)] focus-within:shadow-lift">
                        <span class="relative inline-flex size-5 shrink-0 items-center justify-center text-ink-3">
                            <x-icono nombre="buscar" clase="size-5 transition duration-300" x-bind:class="buscando && 'scale-0 opacity-0'" />
                            <span x-show="buscando" x-cloak class="absolute size-4 animate-spin rounded-full border-2 border-brand border-t-transparent"></span>
                        </span>
                        <input id="buscar" type="search" x-model="q" placeholder="Busca por nombre o descripción" autocomplete="off"
                            class="w-full border-0 bg-transparent py-2 text-[0.95rem] text-ink placeholder:text-ink-3 focus:outline-none focus:ring-0">
                        <button type="button" x-show="q" x-cloak @click="q = ''" class="inline-flex size-8 shrink-0 items-center justify-center rounded-full text-ink-3 hover:bg-surface-2 hover:text-ink" aria-label="Borrar búsqueda">
                            <x-icono nombre="cerrar" clase="size-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="reveal mt-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" style="--delay: 100ms">
            <div class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0" role="group" aria-label="Filtrar por categoría">
                <button type="button" class="chip shrink-0" :aria-pressed="categoria === ''" @click="categoria = ''">Todo</button>
                @foreach ($categorias as $c)
                    <button type="button" class="chip shrink-0" :aria-pressed="categoria === '{{ $c->slug }}'" @click="categoria = categoria === '{{ $c->slug }}' ? '' : '{{ $c->slug }}'">{{ $c->nombre }}</button>
                @endforeach
            </div>
            <div class="flex items-center gap-3">
                <p class="num whitespace-nowrap text-sm text-ink-3" aria-live="polite"><span class="font-semibold text-ink" x-text="productos.length"></span> resultados</p>
                <label for="orden" class="sr-only">Ordenar</label>
                <select id="orden" x-model="orden" class="field !w-auto !rounded-full !py-2 text-sm">
                    <option value="recientes">Destacados</option>
                    <option value="precio_asc">Precio: menor a mayor</option>
                    <option value="precio_desc">Precio: mayor a menor</option>
                    <option value="nombre">Nombre A a Z</option>
                </select>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-5 transition-opacity duration-300 sm:grid-cols-2 lg:grid-cols-4" :class="buscando && 'opacity-60'">
            <template x-for="(p, i) in productos" :key="p.id">
                <article class="group" x-transition:enter="transition duration-700 ease-[var(--ease-out-soft)]" x-transition:enter-start="translate-y-6 opacity-0"
                    :style="`transition-delay: ${Math.min(i, 8) * 40}ms`">
                    <div class="shell transition duration-700 ease-[var(--ease-spring)] group-hover:-translate-y-1.5">
                        <div class="core overflow-hidden">
                            <button type="button" @click="abrir(p)" class="relative block aspect-[4/5] w-full overflow-hidden text-left" :aria-label="'Ver detalle de ' + p.nombre">
                                <template x-if="p.imagen"><img :src="p.imagen" :alt="p.nombre" class="size-full object-cover transition duration-[1.2s] group-hover:scale-[1.06]"></template>
                                <template x-if="!p.imagen">
                                    <svg viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice" class="size-full transition duration-[1.2s] ease-[var(--ease-out-soft)] group-hover:scale-[1.06]"><use :href="'#kc-arte-' + p.variante"/></svg>
                                </template>
                                <span x-show="p.destacado" class="absolute left-3 top-3 rounded-full bg-surface/85 px-2.5 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.14em] text-ink backdrop-blur">Favorito</span>
                                <span x-show="p.stock > 0 && p.stock <= 5" class="absolute right-3 top-3 rounded-full bg-accent px-2.5 py-1 text-[0.68rem] font-semibold text-on-accent" x-text="'Últimas ' + p.stock"></span>
                                <span class="absolute inset-x-3 bottom-3 translate-y-3 rounded-full bg-ink/85 py-2 text-center text-xs font-semibold text-bg opacity-0 backdrop-blur transition duration-500 ease-[var(--ease-spring)] group-hover:translate-y-0 group-hover:opacity-100">Vista rápida</span>
                            </button>
                            <div class="flex items-end justify-between gap-3 p-4">
                                <div class="min-w-0">
                                    <p class="text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-ink-3" x-text="p.categoria"></p>
                                    <h3 class="mt-1 truncate font-semibold"><a :href="p.url" x-text="p.nombre"></a></h3>
                                    <p class="num mt-1 font-display text-lg font-semibold text-brand" x-text="p.precio_formato"></p>
                                </div>
                                <button type="button" x-show="p.stock > 0" @click="$store.carrito.agregar(p.id)"
                                    class="inline-flex size-11 shrink-0 items-center justify-center rounded-full bg-ink text-bg transition duration-500 ease-[var(--ease-spring)] hover:scale-105 hover:bg-accent hover:text-on-accent active:scale-95"
                                    :aria-label="'Agregar ' + p.nombre + ' al pedido'">
                                    <x-icono nombre="mas" clase="size-5" />
                                </button>
                                <span x-show="p.stock < 1" class="badge badge-muted">Agotado</span>
                            </div>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <div x-show="!buscando && productos.length === 0" x-cloak class="mx-auto mt-8 max-w-md py-16 text-center">
            <span class="inline-flex size-16 items-center justify-center rounded-full bg-brand-soft text-brand"><x-icono nombre="buscar" clase="size-7" /></span>
            <p class="mt-5 font-display text-xl font-semibold">Nada por aquí</p>
            <p class="mt-1 text-ink-2">No encontramos productos para "<span x-text="q"></span>". Prueba con otra palabra o quita los filtros.</p>
            <button type="button" class="btn btn-dark mt-6" @click="limpiar()">Limpiar filtros</button>
        </div>

        {{-- Vista rápida del producto --}}
        <div x-show="seleccionado" x-cloak class="fixed inset-0 z-40 flex items-end justify-center p-2 sm:items-center sm:p-6" role="dialog" aria-modal="true" :aria-label="seleccionado?.nombre"
            @keydown.escape.window="seleccionado = null">
            <div class="absolute inset-0 bg-night/55 backdrop-blur-sm" x-show="seleccionado" x-transition.opacity.duration.400ms @click="seleccionado = null"></div>
            <template x-if="seleccionado">
                <div class="relative w-full max-w-3xl" x-trap.noscroll="seleccionado"
                    x-transition:enter="transition duration-700 ease-[var(--ease-spring)]" x-transition:enter-start="translate-y-10 opacity-0 sm:scale-95">
                    <div class="shell !bg-surface/10">
                        <div class="core grid overflow-hidden sm:grid-cols-2">
                            <div class="aspect-square sm:aspect-auto">
                                <template x-if="seleccionado.imagen"><img :src="seleccionado.imagen" :alt="seleccionado.nombre" class="size-full object-cover"></template>
                                <template x-if="!seleccionado.imagen"><svg viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice" class="size-full"><use :href="'#kc-arte-' + seleccionado.variante"/></svg></template>
                            </div>
                            <div class="flex flex-col p-7">
                                <div class="flex items-start justify-between gap-4">
                                    <p class="eyebrow" x-text="seleccionado.categoria"></p>
                                    <button type="button" @click="seleccionado = null" class="-mr-2 -mt-2 inline-flex size-10 items-center justify-center rounded-full ring-1 ring-line-strong hover:bg-surface-2" aria-label="Cerrar">
                                        <x-icono nombre="cerrar" clase="size-5" />
                                    </button>
                                </div>
                                <h2 class="mt-5 font-display text-2xl font-semibold leading-tight" x-text="seleccionado.nombre"></h2>
                                <p class="num mt-3 font-display text-3xl font-semibold text-brand" x-text="seleccionado.precio_formato"></p>
                                <p class="mt-4 leading-relaxed text-ink-2" x-text="seleccionado.descripcion"></p>
                                <p class="mt-4 text-sm" :class="seleccionado.stock > 5 ? 'text-ok' : 'text-warn'"
                                    x-text="seleccionado.stock > 5 ? 'Disponible para envío inmediato' : (seleccionado.stock > 0 ? 'Quedan ' + seleccionado.stock + ' piezas' : 'Agotado por ahora')"></p>
                                <div class="mt-auto flex flex-col gap-2 pt-8 sm:flex-row">
                                    <button type="button" class="btn btn-primary flex-1 justify-between py-3 pl-5 pr-2.5" :disabled="seleccionado.stock < 1"
                                        @click="$store.carrito.agregar(seleccionado.id); seleccionado = null">
                                        Agregar al pedido <span class="btn-orb"><x-icono nombre="mas" clase="size-4" /></span>
                                    </button>
                                    <a :href="seleccionado.url" class="btn btn-ghost">Ver ficha</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>
</x-layouts.tienda>
