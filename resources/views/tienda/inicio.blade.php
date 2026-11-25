<x-layouts.tienda titulo="Tu tiendita de Kuromi">
    {{-- Hero con el collage de ilustraciones de Kuromi --}}
    <section class="relative mx-auto grid max-w-6xl items-center gap-10 px-4 pb-14 pt-10 sm:px-6 md:pt-14 lg:grid-cols-[1fr_1.05fr]">
        <div class="relative z-10">
            <p class="eyebrow reveal"><x-deco tipo="calavera" clase="size-5 text-white" /> Tu tiendita de Kuromi</p>
            <h1 class="reveal mt-5 font-display text-5xl font-bold leading-[0.95] tracking-tight sm:text-6xl lg:text-7xl" style="--delay: 80ms">
                ¡Hola, esto es <span class="relative inline-block text-brand">Kuro<span class="text-accent">Cute</span></span>!
            </h1>
            <p class="reveal mt-5 max-w-md text-lg font-semibold leading-relaxed text-ink-2" style="--delay: 160ms">
                Peluches, figuras, láminas y stickers de Kuromi. Arma tu pedido en minutos y te mandamos tu factura por correo.
            </p>

            <form action="{{ route('tienda.catalogo') }}" method="GET" class="reveal mt-7 max-w-md" style="--delay: 240ms" role="search">
                <label for="hero-buscar" class="sr-only">Buscar productos</label>
                <div class="flex items-center gap-2 rounded-full border-[3px] border-sticker bg-surface py-1.5 pl-5 pr-1.5 shadow-sticker">
                    <x-icono nombre="buscar" clase="size-5 shrink-0 text-brand" />
                    <input id="hero-buscar" type="search" name="q" placeholder="Busca peluches, figuras, stickers..." class="w-full border-0 bg-transparent px-1 py-2 font-semibold text-ink placeholder:text-ink-3 focus:outline-none focus:ring-0">
                    <button type="submit" class="btn btn-primary shrink-0 !shadow-none py-2.5">Buscar</button>
                </div>
            </form>

            <div class="reveal mt-7 flex flex-wrap gap-3" style="--delay: 320ms">
                <a href="{{ route('tienda.catalogo', ['categoria' => 'kuromi']) }}" class="btn btn-dark">Ver peluches de Kuromi</a>
                <a href="#como-funciona" class="btn btn-ghost">¿Cómo pido?</a>
            </div>
        </div>

        {{-- Collage tipo stickers --}}
        <div class="relative order-first mx-auto h-[22rem] w-full max-w-lg sm:h-[32rem] lg:order-none" aria-label="Ilustraciones de Kuromi">
            <x-deco tipo="corazon" clase="twinkle absolute left-2 top-6 z-20 size-9 text-accent" />
            <x-deco tipo="estrella" clase="twinkle absolute bottom-4 left-1/2 z-20 size-8 text-[#ffd84d] [animation-delay:600ms]" />
            <x-deco tipo="brillo" clase="twinkle absolute right-4 top-1/2 z-20 size-7 text-brand [animation-delay:1200ms]" />

            <img src="{{ asset('images/ima4.jpg') }}" alt="Kuromi dentro de una burbuja"
                class="float-slow sticker absolute left-[6%] top-[6%] w-[58%] !rounded-[2rem] [--rot:-5deg]">
            <img src="{{ asset('images/ima3.jpg') }}" alt="Kuromi sentada en la luna"
                class="float-slow sticker absolute right-0 top-0 w-[42%] !rounded-[1.6rem] [--rot:6deg] [animation-delay:800ms]">
            <img src="{{ asset('images/image1.jpg') }}" alt="Kuromi con arcoíris"
                class="float-slow sticker absolute bottom-[4%] right-[6%] w-[40%] !rounded-[1.6rem] [--rot:-3deg] [animation-delay:1600ms]">
            <img src="{{ asset('images/ima2.jpg') }}" alt="Kuromi con los ojos cerrados"
                class="float-slow sticker absolute bottom-[2%] left-0 w-[34%] !rounded-[1.4rem] [--rot:4deg] [animation-delay:400ms]">
            <img src="{{ asset('images/ima6.jpg') }}" alt="Kuromi guiñando el ojo"
                class="medalla absolute left-[40%] top-[48%] z-10 size-24 rotate-[-8deg] object-[50%_20%] shadow-sticker sm:size-28">
        </div>
    </section>

    {{-- Cinta de Kuromis --}}
    <section class="overflow-hidden border-y-[3px] border-sticker bg-brand py-4" aria-label="Galería de Kuromi">
        @php $galeria = ['images/descarga1.jpg', 'images/descarga2.jpg', 'images/descarga3.jpg', 'images/ima5.jpg', 'images/descarga4.jpg', 'images/descarga5.jpg', 'images/ima1.jpg', 'images/ima2.jpg']; @endphp
        <div class="marquee flex w-max gap-4">
            @foreach (array_merge($galeria, $galeria) as $i => $img)
                <img src="{{ asset($img) }}" alt="" class="h-28 w-auto shrink-0 rounded-2xl border-[3px] border-white object-cover sm:h-32" style="transform: rotate({{ [-2, 2, -1, 3, -3, 1][$i % 6] }}deg)">
            @endforeach
        </div>
    </section>

    {{-- Categorías --}}
    <section id="categorias" class="mx-auto max-w-6xl scroll-mt-28 px-4 py-16 sm:px-6 md:py-20" aria-labelledby="titulo-categorias">
        <div class="reveal flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div>
                <p class="eyebrow"><x-deco tipo="estrella" clase="size-4 text-[#ffd84d]" /> Explora</p>
                <h2 id="titulo-categorias" class="mt-3 font-display text-4xl font-bold tracking-tight">¿Qué se te antoja hoy?</h2>
            </div>
            <a href="{{ route('tienda.catalogo') }}" class="link">Ver todo el catálogo</a>
        </div>
        <div class="mt-9 grid gap-6 md:grid-cols-3">
            @foreach ($categorias as $i => $c)
                <a href="{{ route('tienda.catalogo', ['categoria' => $c->slug]) }}" class="reveal sticker wiggle group relative block overflow-hidden" style="--delay: {{ $i * 90 }}ms">
                    <div class="aspect-[4/3] overflow-hidden border-b-[3px] border-sticker">
                        <img src="{{ $c->portadaUrl() }}" alt="{{ $c->nombre }}" class="size-full object-cover transition duration-700 ease-[var(--ease-spring)] group-hover:scale-110">
                    </div>
                    <div class="flex items-center justify-between gap-3 p-5">
                        <div>
                            <h3 class="font-display text-2xl font-bold leading-tight">{{ $c->nombre }}</h3>
                            <p class="text-sm font-bold text-ink-3">{{ $c->productos_count }} productos</p>
                        </div>
                        <span class="inline-flex size-11 shrink-0 items-center justify-center rounded-full border-[2.5px] border-sticker bg-accent text-on-accent transition group-hover:rotate-45"><x-icono nombre="flecha" clase="size-5" /></span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Favoritos --}}
    <section id="favoritos" class="mx-auto max-w-6xl scroll-mt-28 px-4 py-10 sm:px-6" aria-labelledby="titulo-favoritos">
        <div class="reveal flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/descarga4.jpg') }}" alt="" class="medalla size-16 sm:size-20">
                <div>
                    <p class="eyebrow"><x-deco tipo="corazon" clase="size-4 text-accent" /> Los más pedidos</p>
                    <h2 id="titulo-favoritos" class="mt-2 font-display text-4xl font-bold tracking-tight">Favoritos de Kuromi</h2>
                </div>
            </div>
            <a href="{{ route('tienda.catalogo') }}" class="btn btn-ghost">Ver catálogo <x-icono nombre="derecha" clase="size-4" /></a>
        </div>
        <div class="mt-9 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($destacados as $i => $producto)
                <x-tarjeta-producto :producto="$producto" :retraso="($i % 4) * 70" :giro="[-1.5, 1.5, -1, 1][$i % 4]" />
            @endforeach
        </div>
    </section>

    {{-- Cómo pedir --}}
    <section id="como-funciona" class="mx-auto max-w-6xl scroll-mt-28 px-4 py-16 sm:px-6 md:py-20">
        <div class="reveal sticker relative overflow-hidden !bg-brand-soft p-7 sm:p-10">
            <div class="grid items-center gap-10 lg:grid-cols-[0.8fr_1.2fr]">
                <div class="relative">
                    <p class="eyebrow">Cómo pedir</p>
                    <h2 class="mt-3 font-display text-4xl font-bold tracking-tight">¡Tres pasitos y listo!</h2>
                    <p class="mt-3 max-w-sm font-semibold text-ink-2">Sin crear cuenta. Confirmas tus datos, preparamos tu pedido y te mandamos la factura en PDF a tu correo.</p>
                    <img src="{{ asset('images/ima1.jpg') }}" alt="Kuromi y My Melody" class="sticker mt-6 hidden h-64 w-auto rotate-[-3deg] object-cover lg:block">
                </div>
                <ol class="grid gap-5 sm:grid-cols-3">
                    @foreach ([['images/descarga1.jpg', 'Arma tu pedido', 'Agrega productos desde el catálogo o desde cada ficha.'], ['images/descarga2.jpg', 'Confirma tus datos', 'Nombre, correo y dirección de envío. Nada más.'], ['images/descarga5.jpg', 'Recibe tu factura', 'Te llega el PDF por correo cuando facturamos tu pedido.']] as $n => [$img, $titulo, $texto])
                        <li class="reveal sticker wiggle overflow-hidden" style="--delay: {{ $n * 100 }}ms">
                            <div class="relative aspect-[4/3] overflow-hidden border-b-[3px] border-sticker">
                                <img src="{{ asset($img) }}" alt="" class="size-full object-cover">
                                <span class="absolute left-3 top-3 inline-flex size-9 items-center justify-center rounded-full border-[2.5px] border-sticker bg-accent font-display text-lg font-bold text-on-accent">{{ $n + 1 }}</span>
                            </div>
                            <div class="p-4">
                                <p class="font-display text-lg font-semibold">{{ $titulo }}</p>
                                <p class="mt-1 text-sm font-semibold text-ink-2">{{ $texto }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    {{-- Colección de láminas y stickers con las ilustraciones originales --}}
    <section class="mx-auto max-w-6xl px-4 sm:px-6">
        <a href="{{ route('tienda.catalogo', ['categoria' => 'laminas-y-stickers']) }}" class="reveal sticker group grid overflow-hidden !bg-night text-white md:grid-cols-[1fr_1.2fr]">
            <div class="flex flex-col justify-center gap-4 p-8 sm:p-10">
                <img src="{{ asset('images/ima6.jpg') }}" alt="" class="medalla size-20 !border-white object-[50%_20%] rotate-[-6deg] transition duration-500 ease-[var(--ease-spring)] group-hover:rotate-6">
                <p class="eyebrow w-fit"><x-deco tipo="brillo" clase="size-4 text-white" /> Colección Kuromi</p>
                <h2 class="font-display text-3xl font-bold leading-tight sm:text-4xl">Lleva a Kuromi <span class="text-[#ff6fb7]">a todas partes</span>.</h2>
                <p class="font-semibold text-white/70">Láminas ilustradas para tu cuarto y stickers de vinil para tu laptop, termo o libreta.</p>
                <span class="btn btn-primary w-fit">Ver láminas y stickers <x-icono nombre="flecha" clase="size-4" /></span>
            </div>
            <div class="relative grid min-h-64 grid-cols-3 items-center gap-4 bg-brand/25 p-6 sm:p-8" aria-hidden="true">
                <img src="{{ asset('images/ima3.jpg') }}" alt="" class="aspect-square w-full rounded-2xl border-[3px] border-white object-cover rotate-[-4deg] transition duration-500 group-hover:rotate-0">
                <img src="{{ asset('images/image1.jpg') }}" alt="" class="aspect-square w-full -translate-y-4 rounded-2xl border-[3px] border-white object-cover rotate-[3deg] transition duration-500 group-hover:rotate-0">
                <img src="{{ asset('images/ima2.jpg') }}" alt="" class="aspect-square w-full rounded-2xl border-[3px] border-white object-cover rotate-[-2deg] transition duration-500 group-hover:rotate-0">
                <x-deco tipo="estrella" clase="twinkle absolute right-5 top-4 size-7 text-[#ffd84d]" />
                <x-deco tipo="corazon" clase="twinkle absolute bottom-4 left-6 size-7 text-[#ff6fb7] [animation-delay:700ms]" />
            </div>
        </a>
    </section>
</x-layouts.tienda>
