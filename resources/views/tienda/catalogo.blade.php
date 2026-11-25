<x-layouts.tienda titulo="Catálogo">
    <section class="mx-auto max-w-6xl px-4 pb-10 pt-10 sm:px-6"
        x-data="catalogo(@js(['productos' => $productos, 'filtros' => $filtros]))">

        {{-- Encabezado con Kuromi --}}
        <div class="reveal sticker relative overflow-hidden !bg-brand text-white">
            <img src="{{ asset('images/descarga2.jpg') }}" alt="" class="pointer-events-none absolute inset-y-0 right-0 hidden h-full w-auto object-cover opacity-90 md:block [mask-image:linear-gradient(90deg,transparent,#000_35%)]">
            <div class="relative grid gap-6 p-7 sm:p-9 md:max-w-[62%]">
                <div>
                    <p class="font-display text-lg font-semibold text-white/80">Catálogo KuroCute</p>
                    <h1 class="mt-1 font-display text-4xl font-bold leading-tight sm:text-5xl">Encuentra tu nuevo favorito</h1>
                    <p class="mt-2 font-semibold text-white/80">Escribe y los resultados se filtran al instante.</p>
                </div>
                <div role="search">
                    <label for="buscar" class="sr-only">Buscar en el catálogo</label>
                    <div class="flex items-center gap-3 rounded-full border-[3px] border-sticker bg-surface py-1.5 pl-5 pr-2 text-ink shadow-[4px_4px_0_var(--night)]">
                        <span class="relative inline-flex size-5 shrink-0 items-center justify-center text-brand">
                            <x-icono nombre="buscar" clase="size-5 transition duration-300" x-bind:class="buscando && 'scale-0 opacity-0'" />
                            <span x-show="buscando" x-cloak class="absolute size-4 animate-spin rounded-full border-[3px] border-accent border-t-transparent"></span>
                        </span>
                        <input id="buscar" type="search" x-model="q" placeholder="Peluche, figura, sticker..." autocomplete="off"
                            class="w-full border-0 bg-transparent py-2 font-semibold text-ink placeholder:text-ink-3 focus:outline-none focus:ring-0">
                        <button type="button" x-show="q" x-cloak @click="q = ''" class="inline-flex size-8 shrink-0 items-center justify-center rounded-full text-ink-3 hover:bg-surface-2 hover:text-ink" aria-label="Borrar búsqueda">
                            <x-icono nombre="cerrar" clase="size-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="reveal mt-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between" style="--delay: 100ms">
            <div class="-mx-4 flex gap-2 overflow-x-auto px-4 pb-2 sm:mx-0 sm:flex-wrap sm:px-0" role="group" aria-label="Filtrar por categoría">
                <button type="button" class="chip shrink-0" :aria-pressed="categoria === ''" @click="categoria = ''">
                    <img src="{{ asset('images/ima6.jpg') }}" alt="" class="size-7 rounded-full object-cover object-[50%_20%]"> Todo
                </button>
                @foreach ($categorias as $c)
                    <button type="button" class="chip shrink-0" :aria-pressed="categoria === '{{ $c->slug }}'" @click="categoria = categoria === '{{ $c->slug }}' ? '' : '{{ $c->slug }}'">
                        <img src="{{ $c->portadaUrl() }}" alt="" class="size-7 rounded-full object-cover"> {{ $c->nombre }}
                    </button>
                @endforeach
            </div>
            <div class="flex items-center gap-3">
                <p class="num whitespace-nowrap font-bold text-ink-3" aria-live="polite"><span class="font-display text-lg text-ink" x-text="productos.length"></span> resultados</p>
                <label for="orden" class="sr-only">Ordenar</label>
                <select id="orden" x-model="orden" class="field !w-auto !rounded-full !py-2 font-bold">
                    <option value="recientes">Destacados</option>
                    <option value="precio_asc">Precio: menor a mayor</option>
                    <option value="precio_desc">Precio: mayor a menor</option>
                    <option value="nombre">Nombre A a Z</option>
                </select>
            </div>
        </div>

        <div class="mt-7 grid grid-cols-1 gap-6 transition-opacity duration-300 sm:grid-cols-2 lg:grid-cols-4" :class="buscando && 'opacity-60'">
            <template x-for="(p, i) in productos" :key="p.id">
                <article class="group" x-transition:enter="transition duration-500 ease-[var(--ease-spring)]" x-transition:enter-start="translate-y-6 opacity-0">
                    <div class="sticker overflow-hidden transition duration-500 ease-[var(--ease-spring)] group-hover:-translate-y-1.5" :class="i % 2 ? 'group-hover:rotate-1' : 'group-hover:-rotate-1'">
                        <button type="button" @click="abrir(p)" class="foto relative block aspect-square w-full overflow-hidden border-b-[3px] border-sticker text-left" :aria-label="'Ver detalle de ' + p.nombre">
                            <img :src="p.imagen" :alt="p.nombre" class="size-full object-contain p-3 mix-blend-multiply transition duration-700 ease-[var(--ease-spring)] group-hover:scale-105 dark:mix-blend-normal">
                            <span x-show="p.destacado" class="absolute left-3 top-3 rounded-full border-2 border-sticker bg-accent px-2.5 py-0.5 font-display text-xs font-semibold text-on-accent">Favorito</span>
                            <span x-show="p.stock > 0 && p.stock <= 5" class="absolute right-3 top-3 rounded-full border-2 border-sticker bg-surface px-2.5 py-0.5 font-display text-xs font-semibold text-ink" x-text="'Últimas ' + p.stock"></span>
                            <span class="absolute inset-x-3 bottom-3 translate-y-3 rounded-full border-2 border-sticker bg-surface py-1.5 text-center font-display text-sm font-semibold text-ink opacity-0 transition duration-300 group-hover:translate-y-0 group-hover:opacity-100">Vista rápida</span>
                        </button>
                        <div class="flex items-end justify-between gap-3 p-4">
                            <div class="min-w-0">
                                <p class="text-xs font-extrabold uppercase tracking-wider text-brand" x-text="p.categoria"></p>
                                <h3 class="mt-0.5 line-clamp-2 font-display text-lg font-semibold leading-tight"><a :href="p.url" x-text="p.nombre"></a></h3>
                                <p class="num mt-1.5 font-display text-xl font-bold text-accent-strong" x-text="p.precio_formato"></p>
                            </div>
                            <button type="button" x-show="p.stock > 0" @click="$store.carrito.agregar(p.id)"
                                class="wiggle inline-flex size-11 shrink-0 items-center justify-center rounded-full border-[2.5px] border-sticker bg-brand text-white shadow-[2px_2px_0_var(--sticker)] transition hover:bg-accent"
                                :aria-label="'Agregar ' + p.nombre + ' al pedido'">
                                <x-icono nombre="mas" clase="size-5" />
                            </button>
                            <span x-show="p.stock < 1" class="badge badge-muted">Agotado</span>
                        </div>
                    </div>
                </article>
            </template>
        </div>

        <div x-show="!buscando && productos.length === 0" x-cloak class="mx-auto mt-6 max-w-md py-12 text-center">
            <img src="{{ asset('images/ima5.jpg') }}" alt="Kuromi dormida" class="medalla mx-auto size-40">
            <p class="mt-5 font-display text-3xl font-bold">Nada por aquí...</p>
            <p class="mt-1 font-semibold text-ink-2">Kuromi buscó "<span x-text="q"></span>" por todos lados y no lo encontró. Prueba con otra palabra.</p>
            <button type="button" class="btn btn-primary mt-6" @click="limpiar()">Limpiar filtros</button>
        </div>

        {{-- Vista rápida del producto --}}
        <div x-show="seleccionado" x-cloak class="fixed inset-0 z-40 flex items-end justify-center p-2 sm:items-center sm:p-6" role="dialog" aria-modal="true" :aria-label="seleccionado?.nombre"
            @keydown.escape.window="seleccionado = null">
            <div class="absolute inset-0 bg-night/55 backdrop-blur-sm" x-show="seleccionado" x-transition.opacity.duration.300ms @click="seleccionado = null"></div>
            <template x-if="seleccionado">
                <div class="sticker relative grid w-full max-w-3xl overflow-hidden sm:grid-cols-2" x-trap.noscroll="seleccionado"
                    x-transition:enter="transition duration-500 ease-[var(--ease-spring)]" x-transition:enter-start="translate-y-10 opacity-0 sm:scale-90">
                    <div class="foto aspect-square border-b-[3px] border-sticker sm:aspect-auto sm:border-b-0 sm:border-r-[3px]">
                        <img :src="seleccionado.imagen" :alt="seleccionado.nombre" class="size-full object-contain p-5 mix-blend-multiply dark:mix-blend-normal">
                    </div>
                    <div class="flex flex-col p-7">
                        <div class="flex items-start justify-between gap-4">
                            <p class="eyebrow" x-text="seleccionado.categoria"></p>
                            <button type="button" @click="seleccionado = null" class="-mr-2 -mt-2 inline-flex size-10 items-center justify-center rounded-full border-2 border-line-strong hover:border-accent" aria-label="Cerrar">
                                <x-icono nombre="cerrar" clase="size-5" />
                            </button>
                        </div>
                        <h2 class="mt-4 font-display text-3xl font-bold leading-tight" x-text="seleccionado.nombre"></h2>
                        <p class="num mt-2 font-display text-3xl font-bold text-accent-strong" x-text="seleccionado.precio_formato"></p>
                        <p class="mt-3 font-semibold leading-relaxed text-ink-2" x-text="seleccionado.descripcion"></p>
                        <p class="mt-3 text-sm font-extrabold" :class="seleccionado.stock > 5 ? 'text-ok' : 'text-warn'"
                            x-text="seleccionado.stock > 5 ? 'Disponible para envío inmediato' : (seleccionado.stock > 0 ? 'Quedan ' + seleccionado.stock + ' piezas' : 'Agotado por ahora')"></p>
                        <div class="mt-auto flex flex-col gap-2 pt-7 sm:flex-row">
                            <button type="button" class="btn btn-primary flex-1" :disabled="seleccionado.stock < 1"
                                @click="$store.carrito.agregar(seleccionado.id); seleccionado = null">
                                <x-icono nombre="bolsa" clase="size-4" /> Agregar al pedido
                            </button>
                            <a :href="seleccionado.url" class="btn btn-ghost">Ver ficha</a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>
</x-layouts.tienda>
