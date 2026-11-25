<x-app-layout titulo="Dashboard">
    @push('head')
        @vite('resources/js/dashboard.js')
    @endpush

    <x-slot name="header">
        <div class="flex items-center gap-5">
            <img src="{{ asset('images/image1.jpg') }}" alt="Kuromi saludando" class="sticker hidden size-24 rotate-[-4deg] object-cover sm:block">
            <div>
            <p class="eyebrow">{{ ucfirst(now()->translatedFormat('l j \\d\\e F')) }}</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight sm:text-4xl">Hola, {{ strtok(auth()->user()->name, ' ') }}</h1>
            <p class="mt-1.5 text-ink-2">Así va la tienda este mes.</p>
        </div>
        </div>
        @can('administrar')
            <a href="{{ route('facturas.create') }}" class="btn btn-primary self-start py-3 pl-5 pr-2.5 sm:self-auto">Nueva factura <span class="btn-orb"><x-icono nombre="mas" clase="size-4" /></span></a>
        @endcan
    </x-slot>

    @php $dinero = fn ($v) => '$'.number_format($v, 0); @endphp

    {{-- KPIs --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="reveal shell sm:col-span-2 xl:col-span-1">
            <div class="core relative h-full overflow-hidden !bg-night p-6 text-white">
                <div class="pointer-events-none absolute -right-16 -top-16 size-52 rounded-full bg-[#ff4fa3]/40 blur-3xl"></div>
                <p class="relative text-sm text-white/65">Cobrado este mes</p>
                <p class="num relative mt-3 font-display text-4xl font-semibold">{{ $dinero($kpis['cobrado_mes']) }}</p>
                <p class="relative mt-3 text-sm text-white/65">
                    @if (! is_null($kpis['variacion_mes']))
                        <span class="font-semibold {{ $kpis['variacion_mes'] >= 0 ? 'text-[#7be0b0]' : 'text-[#ff9fb0]' }}">{{ $kpis['variacion_mes'] >= 0 ? '+' : '' }}{{ $kpis['variacion_mes'] }}%</span> vs. mes anterior
                    @else
                        Primer mes con cobros
                    @endif
                </p>
            </div>
        </div>
        @foreach ([
            ['Ventas cobradas', $dinero($kpis['cobrado']), 'Histórico de facturas pagadas', 'check'],
            ['Por cobrar', $dinero($kpis['por_cobrar']), $kpis['vencidas'].' facturas vencidas', 'reloj'],
            ['Ticket promedio', $dinero($kpis['ticket_promedio']), $kpis['facturas'].' facturas emitidas', 'factura'],
        ] as $i => [$etiqueta, $valor, $nota, $icono])
            <div class="reveal panel p-6" style="--delay: {{ ($i + 1) * 70 }}ms">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-ink-2">{{ $etiqueta }}</p>
                    <span class="inline-flex size-9 items-center justify-center rounded-full bg-brand-soft text-brand"><x-icono :nombre="$icono" clase="size-[1.1rem]" /></span>
                </div>
                <p class="num mt-3 font-display text-3xl font-semibold">{{ $valor }}</p>
                <p class="mt-2 text-sm text-ink-3">{{ $nota }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-3">
        {{-- Ingresos mensuales --}}
        <section class="reveal panel p-6 xl:col-span-2" aria-labelledby="g-ingresos">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 id="g-ingresos" class="font-semibold">Ingresos por mes</h2>
                    <p class="text-sm text-ink-3">Total facturado de los últimos 12 meses, por estado de cobro.</p>
                </div>
                <ul class="flex items-center gap-4 text-sm text-ink-2">
                    <li class="flex items-center gap-2"><span class="size-2.5 rounded-sm" style="background: var(--chart-1)"></span>Cobrado</li>
                    <li class="flex items-center gap-2"><span class="size-2.5 rounded-sm" style="background: var(--chart-2)"></span>Por cobrar</li>
                </ul>
            </div>
            <div class="mt-6 h-72">
                <canvas data-grafica="ingresos" data-serie='@json($serie)' role="img" aria-label="Gráfica de barras apiladas con ingresos cobrados y por cobrar de los últimos 12 meses"></canvas>
            </div>
            <details class="mt-4 text-sm">
                <summary class="cursor-pointer text-ink-3 hover:text-ink">Ver datos en tabla</summary>
                <div class="mt-3 overflow-x-auto">
                    <table class="tabla">
                        <thead><tr><th>Mes</th><th class="!text-right">Cobrado</th><th class="!text-right">Por cobrar</th></tr></thead>
                        <tbody>
                            @foreach ($serie as $m)
                                <tr><td>{{ $m['etiqueta'] }}</td><td class="num text-right">{{ $dinero($m['cobrado']) }}</td><td class="num text-right">{{ $dinero($m['pendiente']) }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </details>
        </section>

        {{-- Facturas por estado --}}
        <section class="reveal panel p-6" style="--delay: 80ms" aria-labelledby="g-estados">
            <h2 id="g-estados" class="font-semibold">Facturas por estado</h2>
            <p class="text-sm text-ink-3">Distribución de todas las facturas.</p>
            <div class="relative mx-auto mt-5 h-48 w-48">
                <canvas data-grafica="estados" data-estados='@json($estados)' role="img" aria-label="Gráfica de dona con facturas por estado"></canvas>
                <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                    <span class="num font-display text-3xl font-semibold">{{ $kpis['facturas'] }}</span>
                    <span class="text-xs text-ink-3">facturas</span>
                </div>
            </div>
            <ul class="mt-6 space-y-2.5">
                @foreach ($estados as $e)
                    <li>
                        <a href="{{ route('facturas.index', ['estado' => $e['clave']]) }}" class="flex items-center justify-between gap-3 rounded-xl px-2 py-1.5 text-sm hover:bg-surface-2">
                            <x-estado-factura :estado="(object) ['clave' => $e['clave'], 'nombre' => $e['nombre']]" />
                            <span class="num text-ink-2"><span class="font-semibold text-ink">{{ $e['cantidad'] }}</span> &middot; {{ $dinero($e['monto']) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-3">
        {{-- Top clientes --}}
        <section class="reveal panel p-6" aria-labelledby="t-clientes">
            <div class="flex items-center justify-between">
                <h2 id="t-clientes" class="font-semibold">Mejores clientes</h2>
                <a href="{{ route('clientes.index') }}" class="link text-sm">Ver todos</a>
            </div>
            @php $maximo = max(1, $topClientes->max('total') ?? 1); @endphp
            <ol class="mt-5 space-y-4">
                @foreach ($topClientes as $i => $t)
                    <li>
                        <a href="{{ route('clientes.show', $t['cliente']) }}" class="group block">
                            <div class="flex items-center justify-between gap-3 text-sm">
                                <span class="flex min-w-0 items-center gap-3">
                                    <span class="num w-4 text-ink-3">{{ $i + 1 }}</span>
                                    <span class="truncate font-medium group-hover:text-brand">{{ $t['cliente']->nombre }}</span>
                                </span>
                                <span class="num shrink-0 font-semibold">{{ $dinero($t['total']) }}</span>
                            </div>
                            <div class="ml-7 mt-2 h-1.5 overflow-hidden rounded-full bg-muted-soft">
                                <div class="h-full rounded-full bg-brand" style="width: {{ round($t['total'] / $maximo * 100) }}%"></div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Facturas recientes --}}
        <section class="reveal panel overflow-hidden xl:col-span-2" style="--delay: 80ms" aria-labelledby="t-recientes">
            <div class="flex items-center justify-between p-6 pb-2">
                <h2 id="t-recientes" class="font-semibold">Facturas recientes</h2>
                <a href="{{ route('facturas.index') }}" class="link text-sm">Ver todas</a>
            </div>
            <div class="overflow-x-auto">
                <table class="tabla">
                    <thead><tr><th>Folio</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th class="!text-right">Total</th></tr></thead>
                    <tbody>
                        @foreach ($recientes as $f)
                            <tr>
                                <td><a href="{{ route('facturas.show', $f) }}" class="num font-semibold hover:text-brand">{{ $f->numero }}</a></td>
                                <td class="max-w-48 truncate text-ink-2">{{ $f->cliente->nombre }}</td>
                                <td class="num whitespace-nowrap text-ink-2">{{ $f->fecha_emision->format('d/m/Y') }}</td>
                                <td><x-estado-factura :estado="$f->estado" /></td>
                                <td class="num text-right font-semibold">${{ number_format($f->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        @foreach ([
            ['Pedidos nuevos', $kpis['pedidos_nuevos'], route('pedidos.index', ['estado' => 'nuevo']), 'carrito'],
            ['Clientes registrados', $kpis['clientes'], route('clientes.index'), 'clientes'],
            ['Productos con stock bajo', $kpis['stock_bajo'], route('productos.index'), 'caja'],
        ] as [$etiqueta, $valor, $url, $icono])
            <a href="{{ $url }}" class="reveal panel group flex items-center gap-4 p-5 transition duration-500 ease-[var(--ease-spring)] hover:-translate-y-0.5">
                <span class="inline-flex size-11 items-center justify-center rounded-2xl bg-accent-soft text-accent-strong"><x-icono :nombre="$icono" clase="size-5" /></span>
                <span class="flex-1">
                    <span class="block text-sm text-ink-2">{{ $etiqueta }}</span>
                    <span class="num font-display text-2xl font-semibold">{{ $valor }}</span>
                </span>
                <x-icono nombre="flecha" clase="size-4 text-ink-3 transition group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-ink" />
            </a>
        @endforeach
    </div>
</x-app-layout>
