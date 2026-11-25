{{-- Encabezado común de las páginas de ayuda: medalla de Kuromi, título y pestañas. --}}
<div class="reveal flex flex-col gap-6 sm:flex-row sm:items-center">
    <div class="relative w-fit shrink-0">
        <img src="{{ asset($imagen) }}" alt="" class="medalla size-24 rotate-[-4deg] shadow-sticker sm:size-28">
        <x-deco tipo="estrella" clase="twinkle absolute -right-2 -top-1 size-8 text-[#ffd84d]" />
    </div>
    <div>
        <p class="eyebrow">{{ $eyebrow }}</p>
        <h1 class="mt-3 font-display text-4xl font-bold tracking-tight sm:text-5xl">{{ $titulo }}</h1>
        <p class="mt-3 max-w-2xl text-lg font-semibold text-ink-2">{{ $texto }}</p>
    </div>
</div>

<nav class="reveal mt-8 flex flex-wrap gap-2.5" style="--delay: 80ms" aria-label="Ayuda">
    @foreach ([['Preguntas frecuentes', 'ayuda.preguntas'], ['Envíos y entregas', 'ayuda.envios'], ['Contacto', 'contacto']] as [$etiqueta, $ruta])
        <a href="{{ route($ruta) }}" class="chip chip-sola" @if (request()->routeIs($ruta)) aria-current="page" @endif>{{ $etiqueta }}</a>
    @endforeach
</nav>
