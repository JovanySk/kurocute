@props(['titulo' => null])
<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head', ['titulo' => $titulo ?? null])
</head>
<body class="kc-backdrop min-h-[100dvh] antialiased" data-carrito="{{ $carritoCantidad }}" x-data="{ menu: false }">
    <x-arte-sprite />

    <a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 btn btn-dark">Saltar al contenido</a>

    {{-- Barra flotante tipo isla --}}
    <header class="sticky top-0 z-30 px-4 pt-4 sm:pt-5">
        <nav class="mx-auto flex max-w-6xl items-center justify-between gap-3 rounded-full bg-surface/75 py-2 pl-4 pr-2 shadow-soft ring-1 ring-line backdrop-blur-xl sm:pl-5" aria-label="Principal">
            <a href="{{ route('inicio') }}" class="shrink-0" aria-label="KuroCute, inicio"><x-logo tamano="size-8" /></a>

            <div class="hidden items-center gap-1 md:flex">
                <a href="{{ route('inicio') }}" @class(['rounded-full px-4 py-2 text-sm font-medium transition', 'bg-ink text-bg' => request()->routeIs('inicio'), 'text-ink-2 hover:text-ink' => ! request()->routeIs('inicio')])>Inicio</a>
                <a href="{{ route('tienda.catalogo') }}" @class(['rounded-full px-4 py-2 text-sm font-medium transition', 'bg-ink text-bg' => request()->routeIs('tienda.*'), 'text-ink-2 hover:text-ink' => ! request()->routeIs('tienda.*')])>Catálogo</a>
                <a href="{{ route('inicio') }}#como-funciona" class="rounded-full px-4 py-2 text-sm font-medium text-ink-2 transition hover:text-ink">Cómo pedir</a>
            </div>

            <div class="flex items-center gap-1.5">
                <a href="{{ route('tienda.catalogo') }}" class="inline-flex size-10 items-center justify-center rounded-full text-ink-2 ring-1 ring-line-strong transition hover:text-ink" aria-label="Buscar productos">
                    <x-icono nombre="buscar" clase="size-[1.15rem]" />
                </a>
                <x-tema-toggle />
                <button type="button" @click="$store.carrito.abrir()" class="btn btn-dark btn-sm relative h-10 pl-3.5 pr-4" aria-label="Abrir mi pedido">
                    <x-icono nombre="bolsa" clase="size-[1.1rem]" />
                    <span class="hidden sm:inline">Mi pedido</span>
                    <span x-show="$store.carrito.cantidad > 0" x-cloak x-text="$store.carrito.cantidad"
                        class="num inline-flex min-w-5 items-center justify-center rounded-full bg-accent px-1.5 text-[0.7rem] font-bold text-on-accent"></span>
                </button>
                <button type="button" class="relative inline-flex size-10 items-center justify-center rounded-full ring-1 ring-line-strong md:hidden" @click="menu = !menu" :aria-expanded="menu" aria-label="Menú">
                    <span class="absolute h-[1.5px] w-4 rounded bg-ink transition duration-500 ease-[var(--ease-spring)]" :class="menu ? 'rotate-45' : '-translate-y-[4px]'"></span>
                    <span class="absolute h-[1.5px] w-4 rounded bg-ink transition duration-500 ease-[var(--ease-spring)]" :class="menu ? '-rotate-45' : 'translate-y-[4px]'"></span>
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden btn btn-ghost btn-sm h-10 md:inline-flex">Back office</a>
                @else
                    <a href="{{ route('login') }}" class="hidden btn btn-ghost btn-sm h-10 md:inline-flex">Entrar</a>
                @endauth
            </div>
        </nav>
    </header>

    {{-- Menú móvil a pantalla completa --}}
    <div x-show="menu" x-cloak x-transition.opacity.duration.400ms class="fixed inset-0 z-20 bg-bg/90 px-6 pt-28 backdrop-blur-2xl md:hidden" @keydown.escape.window="menu = false">
        <nav class="flex flex-col gap-2" aria-label="Menú móvil">
            @foreach ([['Inicio', route('inicio')], ['Catálogo', route('tienda.catalogo')], ['Cómo pedir', route('inicio').'#como-funciona'], [auth()->check() ? 'Back office' : 'Entrar', auth()->check() ? route('dashboard') : route('login')]] as $i => [$texto, $url])
                <a href="{{ $url }}" @click="menu = false" class="font-display text-3xl font-semibold text-ink transition duration-700 ease-[var(--ease-out-soft)]"
                    :class="menu ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'" style="transition-delay: {{ 80 + $i * 60 }}ms">{{ $texto }}</a>
            @endforeach
        </nav>
    </div>

    <main id="contenido">
        {{ $slot }}
    </main>

    <footer class="mt-24 border-t border-line">
        <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-[1.4fr_1fr_1fr]">
            <div class="max-w-sm">
                <x-logo />
                <p class="mt-4 text-sm leading-relaxed text-ink-2">Peluches, papelería y coleccionables con estética nocturna y corazón tierno. Hecho en México, enviado a todo el país.</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ink-3">Tienda</p>
                <ul class="mt-4 space-y-2.5 text-sm text-ink-2">
                    <li><a class="hover:text-ink" href="{{ route('tienda.catalogo') }}">Catálogo completo</a></li>
                    <li><a class="hover:text-ink" href="{{ route('carrito.index') }}">Mi pedido</a></li>
                    <li><a class="hover:text-ink" href="{{ route('inicio') }}#como-funciona">Cómo pedir</a></li>
                </ul>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ink-3">Contacto</p>
                <ul class="mt-4 space-y-2.5 text-sm text-ink-2">
                    <li>hola@example.com</li>
                    <li>Lunes a sábado, 10:00 a 19:00</li>
                    <li>Tantoyuca, Veracruz</li>
                </ul>
            </div>
        </div>
        <div class="mx-auto flex max-w-6xl flex-col gap-2 border-t border-line px-4 py-6 text-xs text-ink-3 sm:flex-row sm:justify-between sm:px-6">
            <p>&copy; {{ date('Y') }} KuroCute. Proyecto de demostración con datos ficticios.</p>
            <p>Ilustraciones originales. Sin afiliación con marcas de terceros.</p>
        </div>
    </footer>

    @include('tienda.partials.cajon-carrito')

    {{-- Aviso flotante del carrito --}}
    <div x-data x-show="$store.carrito.aviso" x-cloak
        x-transition:enter="transition duration-500 ease-[var(--ease-spring)]" x-transition:enter-start="translate-y-6 opacity-0"
        x-transition:leave="transition duration-300" x-transition:leave-end="translate-y-6 opacity-0"
        class="fixed inset-x-4 bottom-5 z-40 mx-auto flex max-w-sm items-center gap-3 rounded-full bg-ink py-2.5 pl-3 pr-5 text-sm font-medium text-bg shadow-lift" role="status">
        <span class="inline-flex size-7 items-center justify-center rounded-full bg-accent text-on-accent"><x-icono nombre="check" clase="size-4" /></span>
        <span x-text="$store.carrito.aviso"></span>
    </div>
</body>
</html>
