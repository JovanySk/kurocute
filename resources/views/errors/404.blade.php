<x-layouts.tienda titulo="Página no encontrada">
    <section class="mx-auto flex max-w-3xl flex-col items-center px-4 py-16 text-center sm:px-6">
        <div class="relative">
            <img src="{{ asset('images/ima2.jpg') }}" alt="Kuromi dormida" class="sticker size-64 rotate-[-3deg] object-cover sm:size-72">
            <span class="absolute -right-6 -top-6 rounded-full border-[3px] border-sticker bg-accent px-4 py-2 font-display text-3xl font-bold text-on-accent shadow-sticker">404</span>
            <x-deco tipo="estrella" clase="twinkle absolute -bottom-4 -left-5 size-10 text-[#ffd84d]" />
        </div>
        <h1 class="mt-10 font-display text-4xl font-bold sm:text-5xl">Esta página se quedó dormida</h1>
        <p class="mt-3 max-w-md text-lg font-semibold text-ink-2">Kuromi buscó por todos lados y no la encontró. Mejor vuelve a la tienda.</p>
        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('inicio') }}" class="btn btn-primary">Ir al inicio</a>
            <a href="{{ route('tienda.catalogo') }}" class="btn btn-ghost">Ver catálogo</a>
        </div>
    </section>
</x-layouts.tienda>
