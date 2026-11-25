@props(['titulo' => null])
<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head', ['titulo' => $titulo])
</head>
<body class="kc-backdrop min-h-[100dvh] antialiased" data-carrito="{{ $carritoCantidad }}" x-data="{ menu: false }">
    <a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 btn btn-dark">Saltar al contenido</a>

    <header class="sticky top-0 z-30 px-3 pt-3 sm:px-4">
        <nav class="mx-auto flex max-w-6xl items-center justify-between gap-3 rounded-full border-[3px] border-sticker bg-surface/90 py-1.5 pl-2 pr-2 shadow-sticker backdrop-blur-md" aria-label="Principal">
            <a href="{{ route('inicio') }}" class="shrink-0" aria-label="KuroCute, inicio"><x-logo tamano="size-11" /></a>

            <div class="hidden items-center gap-1 md:flex">
                @foreach ([['Inicio', route('inicio'), request()->routeIs('inicio')], ['Catálogo', route('tienda.catalogo'), request()->routeIs('tienda.*')], ['Cómo pedir', route('inicio').'#como-funciona', false]] as [$texto, $url, $activo])
                    <a href="{{ $url }}" @class(['rounded-full px-4 py-2 font-display text-[1.02rem] font-semibold transition', 'bg-brand-soft text-brand-strong' => $activo, 'text-ink-2 hover:text-brand' => ! $activo])>{{ $texto }}</a>
                @endforeach
            </div>

            <div class="flex items-center gap-1.5">
                <a href="{{ route('tienda.catalogo') }}" class="inline-flex size-10 items-center justify-center rounded-full border-2 border-line-strong text-ink-2 transition hover:border-brand hover:text-brand" aria-label="Buscar productos">
                    <x-icono nombre="buscar" clase="size-[1.15rem]" />
                </a>
                <x-tema-toggle />
                <button type="button" @click="$store.carrito.abrir()" class="btn btn-primary btn-sm relative h-10 pl-3 pr-4" aria-label="Abrir mi pedido">
                    <x-icono nombre="bolsa" clase="size-[1.1rem]" />
                    <span class="hidden sm:inline">Mi pedido</span>
                    <span x-show="$store.carrito.cantidad > 0" x-cloak x-text="$store.carrito.cantidad"
                        class="num absolute -right-2 -top-2 inline-flex min-w-6 items-center justify-center rounded-full border-2 border-sticker bg-surface px-1 text-[0.72rem] font-extrabold text-ink"></span>
                </button>
                <button type="button" class="relative inline-flex size-10 items-center justify-center rounded-full border-2 border-line-strong md:hidden" @click="menu = !menu" :aria-expanded="menu" aria-label="Menú">
                    <span class="absolute h-[2px] w-4 rounded bg-ink transition duration-500 ease-[var(--ease-spring)]" :class="menu ? 'rotate-45' : '-translate-y-[4px]'"></span>
                    <span class="absolute h-[2px] w-4 rounded bg-ink transition duration-500 ease-[var(--ease-spring)]" :class="menu ? '-rotate-45' : 'translate-y-[4px]'"></span>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden btn btn-ghost btn-sm h-10 md:inline-flex">Back office</a>
                @else
                    <a href="{{ route('login') }}" class="hidden btn btn-ghost btn-sm h-10 md:inline-flex">Entrar</a>
                @endauth
            </div>
        </nav>
    </header>

    {{-- Menú móvil --}}
    <div x-show="menu" x-cloak x-transition.opacity.duration.300ms class="fixed inset-0 z-20 bg-bg/95 px-6 pt-28 backdrop-blur-xl md:hidden" @keydown.escape.window="menu = false">
        <nav class="flex flex-col gap-3" aria-label="Menú móvil">
            @foreach ([['Inicio', route('inicio')], ['Catálogo', route('tienda.catalogo')], ['Cómo pedir', route('inicio').'#como-funciona'], [auth()->check() ? 'Back office' : 'Entrar', auth()->check() ? route('dashboard') : route('login')]] as $i => [$texto, $url])
                <a href="{{ $url }}" @click="menu = false" class="font-display text-4xl font-bold text-ink transition duration-500 ease-[var(--ease-spring)]"
                    :class="menu ? 'translate-y-0 opacity-100' : 'translate-y-6 opacity-0'" style="transition-delay: {{ 60 + $i * 60 }}ms">{{ $texto }}</a>
            @endforeach
        </nav>
        <img src="{{ asset('images/ima6.jpg') }}" alt="" class="medalla absolute bottom-10 right-8 size-36 rotate-6">
    </div>

    <main id="contenido">
        {{ $slot }}
    </main>

    <footer class="mt-24 bg-night text-white">
        <div class="h-3 bg-[repeating-linear-gradient(90deg,#7b4397_0_24px,#ff4fa3_24px_48px)]"></div>
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-[1.4fr_1fr_1fr]">
            <div class="max-w-sm">
                <span class="inline-flex items-center gap-2.5">
                    <img src="{{ asset('images/ima6.jpg') }}" alt="" class="medalla size-12 !border-white object-[50%_20%]">
                    <span class="font-display text-2xl font-bold">Kuro<span class="text-[#ff6fb7]">Cute</span></span>
                </span>
                <p class="mt-4 leading-relaxed text-white/70">Tu tiendita favorita de Kuromi: peluches, figuras, maquillaje y hasta motos. Hecho con mucho cariño en Tantoyuca.</p>
            </div>
            <div>
                <p class="font-display text-lg font-semibold text-[#e0b8ff]">Tienda</p>
                <ul class="mt-3 space-y-2 text-white/75">
                    <li><a class="hover:text-white" href="{{ route('tienda.catalogo') }}">Catálogo completo</a></li>
                    <li><a class="hover:text-white" href="{{ route('carrito.index') }}">Mi pedido</a></li>
                    <li><a class="hover:text-white" href="{{ route('inicio') }}#como-funciona">Cómo pedir</a></li>
                </ul>
            </div>
            <div>
                <p class="font-display text-lg font-semibold text-[#e0b8ff]">Contacto</p>
                <ul class="mt-3 space-y-2 text-white/75">
                    <li>hola@example.com</li>
                    <li>Lunes a sábado, 10:00 a 19:00</li>
                    <li>Tantoyuca, Veracruz</li>
                </ul>
            </div>
        </div>
        <div class="mx-auto flex max-w-6xl flex-col gap-2 border-t border-white/10 px-4 py-6 text-xs text-white/55 sm:flex-row sm:justify-between sm:px-6">
            <p>&copy; {{ date('Y') }} KuroCute. Proyecto escolar de fans, sin fines de lucro.</p>
            <p>Kuromi y sus imágenes pertenecen a sus respectivos dueños (Sanrio).</p>
        </div>
    </footer>

    @include('tienda.partials.cajon-carrito')

    <div x-data x-show="$store.carrito.aviso" x-cloak
        x-transition:enter="transition duration-500 ease-[var(--ease-spring)]" x-transition:enter-start="translate-y-6 opacity-0"
        x-transition:leave="transition duration-300" x-transition:leave-end="translate-y-6 opacity-0"
        class="fixed inset-x-4 bottom-5 z-40 mx-auto flex max-w-sm items-center gap-3 rounded-full border-[3px] border-sticker bg-surface py-2 pl-2 pr-5 font-bold text-ink shadow-sticker" role="status">
        <img src="{{ asset('images/descarga4.jpg') }}" alt="" class="medalla size-10 !border-2">
        <span x-text="$store.carrito.aviso"></span>
    </div>
</body>
</html>
