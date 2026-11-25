<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head', ['titulo' => $titulo])
</head>
<body class="min-h-[100dvh] bg-bg antialiased">
    <div class="grid min-h-[100dvh] lg:grid-cols-[1.05fr_1fr]">
        {{-- Panel de marca --}}
        <div class="relative hidden overflow-hidden bg-night p-12 text-white lg:flex lg:flex-col">
            <div class="pointer-events-none absolute inset-0" style="background: radial-gradient(40rem 30rem at 80% 10%, rgb(139 92 246 / .35), transparent 70%), radial-gradient(30rem 24rem at 10% 90%, rgb(240 106 168 / .22), transparent 70%);"></div>
            <a href="{{ route('inicio') }}" class="relative"><span class="inline-flex items-center gap-2.5"><x-logo :texto="false" tamano="size-9" /><span class="font-display text-lg font-semibold">kuro<span class="text-[#f06aa8]">cute</span></span></span></a>
            <div class="relative mt-auto max-w-md">
                <p class="eyebrow !bg-white/10 !text-[#e9dcff] !shadow-none">Back office</p>
                <h1 class="mt-6 font-display text-4xl font-semibold leading-[1.1]">Tu tienda, tus pedidos y tus facturas en un solo lugar.</h1>
                <p class="mt-5 text-white/65">Administra el catálogo, convierte pedidos en facturas y envía el PDF a tus clientes con un clic.</p>
            </div>
            <div class="relative mt-12 grid grid-cols-3 gap-3">
                @foreach ([0, 2, 5] as $i => $v)
                    <div class="float-slow aspect-square overflow-hidden rounded-3xl ring-1 ring-white/10" style="--rot: {{ [-4, 3, -2][$i] }}deg; animation-delay: {{ $i * 600 }}ms">
                        <svg viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice" class="size-full"><use href="#kc-arte-{{ $v }}"/></svg>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex flex-col px-5 py-8 sm:px-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('inicio') }}" class="lg:invisible"><x-logo tamano="size-8" /></a>
                <x-tema-toggle />
            </div>
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-10">
                {{ $slot }}
            </div>
        </div>
    </div>
    <x-arte-sprite />
</body>
</html>
