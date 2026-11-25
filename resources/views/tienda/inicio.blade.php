<x-layouts.tienda titulo="Peluches y coleccionables con lado oscuro">
    {{-- Hero: titular editorial + cascada de productos --}}
    <section class="relative mx-auto grid max-w-6xl items-center gap-14 px-4 pb-20 pt-14 sm:px-6 md:pt-20 lg:grid-cols-[1.1fr_0.9fr] lg:pb-28">
        <div>
            <p class="eyebrow reveal"><x-icono nombre="brillo" clase="size-3.5 text-accent" /> Colección Noche de Estrellas</p>
            <h1 class="reveal mt-7 font-display text-[2.6rem] font-semibold leading-[1.02] tracking-tight text-ink sm:text-6xl lg:text-[4.4rem]" style="--delay: 80ms">
                Lo tierno también tiene su <span class="relative whitespace-nowrap text-accent">lado oscuro<svg class="absolute -bottom-2 left-0 w-full text-accent/40" viewBox="0 0 300 12" fill="none" preserveAspectRatio="none" aria-hidden="true"><path d="M2 9c60-6 150-8 296-3" stroke="currentColor" stroke-width="4" stroke-linecap="round"/></svg></span>.
            </h1>
            <p class="reveal mt-7 max-w-lg text-lg leading-relaxed text-ink-2" style="--delay: 160ms">
                Peluches, papelería y coleccionables con estética nocturna. Arma tu pedido en minutos y recibe tu factura por correo.
            </p>

            <form action="{{ route('tienda.catalogo') }}" method="GET" class="reveal mt-9 max-w-lg" style="--delay: 240ms" role="search">
                <label for="hero-buscar" class="sr-only">Buscar productos</label>
                <div class="shell !rounded-full">
                    <div class="core flex items-center gap-2 !rounded-full py-1.5 pl-5 pr-1.5 transition focus-within:shadow-lift">
                        <x-icono nombre="buscar" clase="size-5 shrink-0 text-ink-3" />
                        <input id="hero-buscar" type="search" name="q" placeholder="Busca peluches, libretas, tazas..." class="w-full border-0 bg-transparent px-1 py-2 text-[0.95rem] text-ink placeholder:text-ink-3 focus:outline-none focus:ring-0">
                        <button type="submit" class="btn btn-primary shrink-0 py-2.5 pl-5 pr-2.5">
                            Buscar <span class="btn-orb"><x-icono nombre="flecha" clase="size-4" /></span>
                        </button>
                    </div>
                </div>
            </form>

            <dl class="reveal mt-10 grid max-w-lg grid-cols-3 gap-6 border-t border-line pt-7" style="--delay: 320ms">
                <div><dt class="text-xs text-ink-3">Productos</dt><dd class="num mt-1 font-display text-2xl font-semibold">{{ $totalProductos }}</dd></div>
                <div><dt class="text-xs text-ink-3">Envío nacional</dt><dd class="mt-1 font-display text-2xl font-semibold">48 h</dd></div>
                <div><dt class="text-xs text-ink-3">Factura</dt><dd class="mt-1 font-display text-2xl font-semibold">PDF</dd></div>
            </dl>
        </div>

        {{-- Cascada de tarjetas (sin rotaciones en movil) --}}
        <div class="relative mx-auto w-full max-w-md lg:max-w-none">
            <div class="absolute inset-8 -z-10 rounded-full bg-brand/25 blur-3xl"></div>
            @php $hero = $destacados->unique(fn ($p) => $p->variante())->take(3)->values(); @endphp
            <div class="relative grid grid-cols-2 gap-4 md:block md:h-[40rem]">
                @foreach ($hero as $i => $p)
                    @php
                        $pos = [
                            'md:absolute md:left-0 md:top-[16%] md:w-[50%] md:[--rot:-5deg]',
                            'md:absolute md:right-0 md:top-0 md:w-[44%] md:[--rot:4deg]',
                            'md:absolute md:bottom-0 md:right-[4%] md:w-[44%] md:[--rot:-2deg]',
                        ][$i];
                    @endphp
                    <a href="{{ route('tienda.producto', $p->slug) }}" class="reveal {{ $pos }} {{ $i === 2 ? 'col-span-2 mx-auto w-2/3 md:mx-0' : '' }} block" style="--delay: {{ 200 + $i * 120 }}ms">
                        <div class="float-slow shell md:rotate-[var(--rot)]" style="animation-delay: {{ $i * 900 }}ms">
                            <div class="core overflow-hidden">
                                <div class="aspect-square"><x-producto-arte :producto="$p" /></div>
                                <div class="flex items-center justify-between gap-2 px-4 py-3">
                                    <span class="truncate text-sm font-semibold">{{ $p->nombre }}</span>
                                    <span class="num shrink-0 text-sm font-semibold text-brand">${{ number_format($p->precio, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Categorías en bento --}}
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 md:py-24" aria-labelledby="titulo-categorias">
        <div class="reveal flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="eyebrow">Explora</p>
                <h2 id="titulo-categorias" class="mt-5 font-display text-3xl font-semibold tracking-tight sm:text-4xl">Encuentra tu rincón favorito</h2>
            </div>
            <a href="{{ route('tienda.catalogo') }}" class="link text-sm">Ver todo el catálogo</a>
        </div>
        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($categorias as $i => $c)
                <a href="{{ route('tienda.catalogo', ['categoria' => $c->slug]) }}"
                    class="reveal group relative overflow-hidden rounded-[1.75rem] p-6 ring-1 ring-line transition duration-700 ease-[var(--ease-spring)] hover:-translate-y-1 hover:shadow-lift {{ $i === 0 ? 'bg-night text-white lg:col-span-2 lg:row-span-2' : 'bg-surface' }}"
                    style="--delay: {{ $i * 70 }}ms">
                    <div class="relative z-10 flex h-full flex-col {{ $i === 0 ? 'min-h-56 lg:min-h-full' : 'min-h-40' }}">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] {{ $i === 0 ? 'text-white/60' : 'text-ink-3' }}">{{ $c->productos_count }} productos</p>
                        <h3 class="mt-2 font-display font-semibold {{ $i === 0 ? 'text-3xl sm:text-4xl' : 'text-xl' }}">{{ $c->nombre }}</h3>
                        <p class="mt-2 max-w-[15rem] text-sm {{ $i === 0 ? 'text-white/70' : 'text-ink-2' }}">{{ $c->descripcion }}</p>
                        <span class="mt-auto inline-flex size-10 items-center justify-center rounded-full pt-0 transition duration-500 ease-[var(--ease-spring)] group-hover:translate-x-1 group-hover:-translate-y-0.5 {{ $i === 0 ? 'bg-white/10' : 'bg-surface-2 ring-1 ring-line' }}">
                            <x-icono nombre="flecha" clase="size-4" />
                        </span>
                    </div>
                    <svg viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice" class="pointer-events-none absolute -bottom-10 -right-10 rounded-[2rem] transition duration-[1.2s] ease-[var(--ease-out-soft)] group-hover:scale-105 group-hover:-rotate-3 {{ $i === 0 ? 'size-72 opacity-90 sm:size-80' : 'size-36 opacity-90' }}" aria-hidden="true">
                        <use href="#kc-arte-{{ $i % 6 }}"/>
                    </svg>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Favoritos --}}
    <section class="mx-auto max-w-6xl px-4 py-16 sm:px-6 md:py-24" aria-labelledby="titulo-favoritos">
        <div class="reveal flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="eyebrow">Los más pedidos</p>
                <h2 id="titulo-favoritos" class="mt-5 font-display text-3xl font-semibold tracking-tight sm:text-4xl">Favoritos de la comunidad</h2>
            </div>
            <a href="{{ route('tienda.catalogo') }}" class="btn btn-ghost">Ver catálogo <x-icono nombre="derecha" clase="size-4" /></a>
        </div>
        <div class="mt-10 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($destacados as $i => $producto)
                <x-tarjeta-producto :producto="$producto" :retraso="($i % 4) * 80" />
            @endforeach
        </div>
    </section>

    {{-- Como funciona --}}
    <section id="como-funciona" class="mx-auto max-w-6xl scroll-mt-28 px-4 py-16 sm:px-6 md:py-24">
        <div class="shell reveal">
            <div class="core grid gap-10 p-8 sm:p-12 lg:grid-cols-[0.9fr_1.1fr]">
                <div>
                    <p class="eyebrow">Cómo pedir</p>
                    <h2 class="mt-5 font-display text-3xl font-semibold tracking-tight sm:text-4xl">Tres pasos y listo.</h2>
                    <p class="mt-4 max-w-sm text-ink-2">Sin crear cuenta. Confirmas tus datos, preparamos tu pedido y te enviamos la factura en PDF a tu correo.</p>
                </div>
                <ol class="grid gap-4 sm:grid-cols-3">
                    @foreach ([['bolsa', 'Arma tu pedido', 'Agrega productos desde el catálogo o desde cada ficha.'], ['check', 'Confirma tus datos', 'Nombre, correo y dirección de envío. Nada más.'], ['correo', 'Recibe tu factura', 'Te llega el PDF por correo cuando facturamos tu pedido.']] as $n => [$icono, $titulo, $texto])
                        <li class="reveal rounded-3xl bg-surface-2 p-5 ring-1 ring-line" style="--delay: {{ $n * 100 }}ms">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex size-11 items-center justify-center rounded-full bg-brand-soft text-brand"><x-icono :nombre="$icono" clase="size-5" /></span>
                                <span class="num font-display text-sm font-semibold text-ink-3">0{{ $n + 1 }}</span>
                            </div>
                            <p class="mt-6 font-semibold">{{ $titulo }}</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink-2">{{ $texto }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- Llamado final --}}
    <section class="mx-auto max-w-6xl px-4 sm:px-6">
        <div class="reveal relative overflow-hidden rounded-[2rem] bg-night px-8 py-14 text-white sm:px-14 sm:py-20">
            <div class="pointer-events-none absolute inset-0" style="background: radial-gradient(34rem 20rem at 90% 0%, rgb(139 92 246 / .4), transparent 70%), radial-gradient(24rem 18rem at 0% 100%, rgb(240 106 168 / .25), transparent 70%);"></div>
            <div class="relative flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
                <div class="max-w-xl">
                    <h2 class="font-display text-3xl font-semibold leading-tight sm:text-4xl">Tu próximo favorito ya te está esperando.</h2>
                    <p class="mt-3 text-white/70">Nuevos peluches y coleccionables cada mes. Piezas limitadas.</p>
                </div>
                <a href="{{ route('tienda.catalogo') }}" class="btn btn-primary py-3.5 pl-6 pr-3">Ir al catálogo <span class="btn-orb"><x-icono nombre="flecha" clase="size-4" /></span></a>
            </div>
        </div>
    </section>
</x-layouts.tienda>
