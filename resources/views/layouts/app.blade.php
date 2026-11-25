<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head', ['titulo' => $titulo])
    @stack('head')
</head>
<body class="kc-backdrop min-h-[100dvh] antialiased" x-data="{ lateral: false }">
    @php
        $grupos = [
            'General' => [
                ['Dashboard', 'dashboard', 'panel', 'dashboard'],
            ],
            'Ventas' => [
                ['Pedidos', 'pedidos.index', 'carrito', 'pedidos.*'],
                ['Facturas', 'facturas.index', 'factura', 'facturas.*'],
                ['Clientes', 'clientes.index', 'clientes', 'clientes.*'],
            ],
            'Catálogo' => [
                ['Productos', 'productos.index', 'caja', 'productos.*'],
                ['Categorías', 'categorias.index', 'etiqueta', 'categorias.*'],
            ],
            'Ajustes' => [
                ['Perfiles', 'perfiles.index', 'escudo', 'perfiles.*'],
                ['Mi cuenta', 'profile.edit', 'usuario', 'profile.*'],
            ],
        ];
        $usuario = auth()->user();
    @endphp

    {{-- Barra lateral --}}
    <div x-show="lateral" x-cloak x-transition.opacity class="fixed inset-0 z-30 bg-night/50 backdrop-blur-sm lg:hidden" @click="lateral = false"></div>
    <aside class="fixed inset-y-3 left-3 z-40 flex w-64 -translate-x-[110%] flex-col rounded-[1.75rem] border-[3px] border-sticker bg-surface p-4 shadow-sticker transition duration-700 ease-[var(--ease-spring)] lg:translate-x-0 lg:shadow-soft"
        :class="lateral && '!translate-x-0'" aria-label="Navegación del back office">
        <div class="flex items-center justify-between px-2 pb-6 pt-2">
            <a href="{{ route('dashboard') }}"><x-logo tamano="size-10" /></a>
            <button type="button" class="inline-flex size-9 items-center justify-center rounded-full ring-1 ring-line-strong lg:hidden" @click="lateral = false" aria-label="Cerrar menú">
                <x-icono nombre="cerrar" clase="size-4" />
            </button>
        </div>

        <nav class="flex-1 space-y-6 overflow-y-auto">
            @foreach ($grupos as $grupo => $enlaces)
                <div>
                    <p class="px-3 pb-2 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-ink-3">{{ $grupo }}</p>
                    <div class="space-y-1">
                        @foreach ($enlaces as [$texto, $ruta, $icono, $patron])
                            <a href="{{ route($ruta) }}" class="nav-pill" @if (request()->routeIs($patron)) aria-current="page" @endif>
                                <x-icono :nombre="$icono" clase="size-[1.15rem]" />
                                {{ $texto }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </nav>

        <div class="relative mt-4 overflow-hidden rounded-2xl border-2 border-line-strong bg-brand-soft">
            <img src="{{ asset('images/descarga5.jpg') }}" alt="Kuromi" class="h-20 w-full object-cover">
        </div>
        <div class="mt-3 rounded-2xl border-2 border-line bg-surface-2 p-3">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Usuario.jpg') }}" alt="" class="size-10 shrink-0 rounded-full border-2 border-line-strong object-cover">
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold">{{ $usuario->name }}</p>
                    <p class="truncate text-xs text-ink-3">{{ $usuario->perfil?->nombre ?? 'Sin perfil' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm w-full">
                    <x-icono nombre="salir" clase="size-4" /> Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="lg:pl-[17.5rem]">
        <header class="sticky top-0 z-20 px-4 pt-3 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-3 rounded-full border-2 border-line-strong bg-surface/85 py-2 pl-3 pr-2 shadow-soft backdrop-blur-xl sm:pl-5">
                <div class="flex min-w-0 items-center gap-2">
                    <button type="button" class="inline-flex size-10 items-center justify-center rounded-full ring-1 ring-line-strong lg:hidden" @click="lateral = true" aria-label="Abrir menú">
                        <x-icono nombre="menu" clase="size-5" />
                    </button>
                    <p class="truncate text-sm text-ink-3">
                        <span class="hidden sm:inline">Back office</span>
                        @if ($titulo)<span class="hidden sm:inline px-1.5">/</span><span class="font-semibold text-ink">{{ $titulo }}</span>@endif
                    </p>
                </div>
                <div class="flex items-center gap-1.5">
                    @cannot('administrar')
                        <span class="badge badge-muted hidden sm:inline-flex">Solo lectura</span>
                    @endcannot
                    <a href="{{ route('inicio') }}" class="btn btn-ghost btn-sm h-10" target="_blank" rel="noopener">
                        <x-icono nombre="tienda" clase="size-4" /> <span class="hidden sm:inline">Ver tienda</span>
                    </a>
                    <x-tema-toggle />
                </div>
            </div>
        </header>

        <main class="px-4 pb-12 pt-8 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <x-flash />
                @isset($header)
                    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        {{ $header }}
                    </div>
                @endisset
                {{ $slot }}
            </div>
        </main>

        <footer class="px-4 pb-6 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-7xl flex-col gap-1.5 border-t-2 border-dashed border-line pt-5 text-xs font-semibold text-ink-3 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} KuroCute. Todos los derechos reservados.</p>
                <p>Tienda desarrollada por <span class="text-ink-2">Jovany</span> <span class="px-1 text-line-strong" aria-hidden="true">&middot;</span> Kuromi &copy; Sanrio.</p>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
