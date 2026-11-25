<!DOCTYPE html>
<html lang="es">
<head>
    @include('partials.head', ['titulo' => $titulo])
</head>
<body class="kc-backdrop min-h-[100dvh] antialiased">
    <div class="grid min-h-[100dvh] lg:grid-cols-[1.05fr_1fr]">
        {{-- Panel con ilustraciones de Kuromi --}}
        <div class="relative hidden overflow-hidden border-r-[3px] border-sticker bg-brand p-10 text-white lg:flex lg:flex-col">
            <img src="{{ asset('images/descarga2.jpg') }}" alt="" class="pointer-events-none absolute inset-0 size-full object-cover opacity-25">
            <a href="{{ route('inicio') }}" class="relative inline-flex items-center gap-2.5">
                <img src="{{ asset('images/ima6.jpg') }}" alt="" class="medalla size-12 !border-white object-[50%_20%]">
                <span class="font-display text-2xl font-bold">Kuro<span class="text-[#ffb3da]">Cute</span></span>
            </a>
            <div class="relative mt-8 flex flex-1 items-center justify-center">
                <img src="{{ asset('images/ima1.jpg') }}" alt="Kuromi y My Melody" class="float-slow sticker h-[26rem] w-auto object-cover [--rot:-4deg]">
                <img src="{{ asset('images/ima4.jpg') }}" alt="Kuromi en una burbuja" class="float-slow medalla absolute bottom-4 right-6 size-36 !border-white [--rot:6deg] [animation-delay:900ms]">
                <x-deco tipo="estrella" clase="twinkle absolute left-10 top-6 size-10 text-[#ffd84d]" />
                <x-deco tipo="corazon" clase="twinkle absolute bottom-10 left-14 size-9 text-[#ff6fb7] [animation-delay:600ms]" />
            </div>
            <div class="relative mt-8 max-w-md">
                <h1 class="font-display text-4xl font-bold leading-tight">Tu tiendita de Kuromi, también por dentro.</h1>
                <p class="mt-3 font-semibold text-white/80">Administra el catálogo, convierte pedidos en facturas y envía el PDF a tus clientes con un clic.</p>
            </div>
        </div>

        <div class="flex flex-col px-5 py-8 sm:px-10">
            <div class="flex items-center justify-between">
                <a href="{{ route('inicio') }}" class="lg:invisible"><x-logo tamano="size-10" /></a>
                <x-tema-toggle />
            </div>
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-10">
                <div class="sticker p-7 sm:p-9">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
