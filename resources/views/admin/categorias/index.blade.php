<x-app-layout titulo="Categorías">
    <x-slot name="header">
        <div>
            <p class="eyebrow">Catálogo</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight">Categorías</h1>
            <p class="mt-1.5 text-ink-2">Las secciones que aparecen como filtros en la tienda.</p>
        </div>
    </x-slot>

    <div class="grid gap-4 lg:grid-cols-[1fr_22rem]">
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($categorias as $i => $c)
                <article class="panel group overflow-hidden" x-data="{ editar: false }">
                    <div class="flex items-start gap-4 p-5">
                        <img src="{{ $c->portadaUrl() }}" alt="" class="size-16 shrink-0 rounded-2xl border-2 border-line-strong object-cover">
                        <div class="min-w-0 flex-1">
                            <h2 class="font-semibold">{{ $c->nombre }}</h2>
                            <p class="num text-sm text-ink-3">{{ $c->productos_count }} productos</p>
                            <p class="mt-1 text-sm text-ink-2">{{ $c->descripcion }}</p>
                        </div>
                        @can('administrar')
                            <button type="button" @click="editar = !editar" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2" aria-label="Editar {{ $c->nombre }}"><x-icono nombre="editar" clase="size-4" /></button>
                        @endcan
                    </div>
                    @can('administrar')
                        <div x-show="editar" x-collapse x-cloak class="border-t border-line bg-surface-2 p-5">
                            <form method="POST" action="{{ route('categorias.update', $c) }}" class="space-y-3">
                                @csrf @method('PUT')
                                <input name="nombre" value="{{ $c->nombre }}" class="field" aria-label="Nombre">
                                <input name="descripcion" value="{{ $c->descripcion }}" class="field" aria-label="Descripción">
                                <div class="flex justify-between gap-2">
                                    <button class="btn btn-dark btn-sm">Guardar</button>
                                    <button form="borrar-{{ $c->id }}" class="btn btn-danger btn-sm">Eliminar</button>
                                </div>
                            </form>
                            <form id="borrar-{{ $c->id }}" method="POST" action="{{ route('categorias.destroy', $c) }}" x-data @submit="if (! confirm('¿Eliminar la categoría {{ $c->nombre }}?')) $event.preventDefault()">@csrf @method('DELETE')</form>
                        </div>
                    @endcan
                </article>
            @endforeach
        </div>
        @can('administrar')
            <form method="POST" action="{{ route('categorias.store') }}" class="panel h-fit p-6">
                @csrf
                <h2 class="font-semibold">Nueva categoría</h2>
                <label for="nombre" class="label mt-5">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre') }}" required class="field">
                <x-input-error :messages="$errors->get('nombre')" />
                <x-input-error :messages="$errors->get('slug')" />
                <label for="descripcion" class="label mt-4">Descripción</label>
                <input id="descripcion" name="descripcion" value="{{ old('descripcion') }}" class="field">
                <button class="btn btn-primary mt-6 w-full">Crear categoría</button>
            </form>
        @endcan
    </div>
</x-app-layout>
