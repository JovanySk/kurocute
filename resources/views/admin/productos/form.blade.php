@php $editando = $producto->exists; @endphp
<x-app-layout :titulo="$editando ? 'Editar producto' : 'Nuevo producto'">
    <x-slot name="header">
        <div>
            <a href="{{ route('productos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink-3 hover:text-ink"><x-icono nombre="izquierda" clase="size-4" /> Productos</a>
            <h1 class="mt-3 font-display text-3xl font-semibold tracking-tight">{{ $editando ? $producto->nombre : 'Nuevo producto' }}</h1>
        </div>
    </x-slot>

    <form method="POST" enctype="multipart/form-data" action="{{ $editando ? route('productos.update', $producto) : route('productos.store') }}" class="grid gap-4 lg:grid-cols-[1fr_20rem]">
        @csrf
        @if ($editando) @method('PUT') @endif
        <section class="shell">
            <div class="core grid gap-5 p-6 sm:grid-cols-2 sm:p-8">
                <div class="sm:col-span-2">
                    <label for="nombre" class="label">Nombre</label>
                    <input id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required class="field">
                    <x-input-error :messages="$errors->get('nombre')" />
                </div>
                <div>
                    <label for="categoria_id" class="label">Categoría</label>
                    <select id="categoria_id" name="categoria_id" class="field">
                        <option value="">Sin categoría</option>
                        @foreach ($categorias as $c)
                            <option value="{{ $c->id }}" @selected(old('categoria_id', $producto->categoria_id) == $c->id)>{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('categoria_id')" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="precio" class="label">Precio (IVA incl.)</label>
                        <input id="precio" name="precio" type="number" step="0.01" min="1" value="{{ old('precio', $producto->precio) }}" required class="field num">
                        <x-input-error :messages="$errors->get('precio')" />
                    </div>
                    <div>
                        <label for="stock" class="label">Stock</label>
                        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $producto->stock) }}" required class="field num">
                        <x-input-error :messages="$errors->get('stock')" />
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="descripcion" class="label">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="field">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    <x-input-error :messages="$errors->get('descripcion')" />
                </div>
                <div class="flex flex-wrap gap-6 sm:col-span-2">
                    <label class="flex items-center gap-2.5 text-sm"><input type="checkbox" name="activo" value="1" @checked(old('activo', $producto->activo)) class="size-5 rounded-md border-line-strong text-accent focus:ring-accent"> Visible en la tienda</label>
                    <label class="flex items-center gap-2.5 text-sm"><input type="checkbox" name="destacado" value="1" @checked(old('destacado', $producto->destacado)) class="size-5 rounded-md border-line-strong text-accent focus:ring-accent"> Mostrar en favoritos</label>
                </div>
            </div>
        </section>
        <aside class="space-y-4">
            <section class="panel p-6" x-data="vistaImagen(@js($producto->imagenUrl()))">
                <p class="label">Imagen</p>
                <label for="imagen" class="group mt-2 flex aspect-square cursor-pointer items-center justify-center overflow-hidden rounded-3xl border-2 border-dashed border-line-strong bg-surface-2 transition hover:border-brand/60">
                    <template x-if="url"><img :src="url" alt="Vista previa" class="size-full object-cover"></template>
                    <template x-if="!url">
                        @if ($editando)
                            <div class="relative size-full"><x-producto-arte :producto="$producto" /><span class="absolute inset-x-3 bottom-3 rounded-full bg-ink/80 py-2 text-center text-xs font-semibold text-bg">Foto actual. Clic para cambiarla.</span></div>
                        @else
                            <span class="flex flex-col items-center gap-2 px-6 text-center text-sm text-ink-3"><span class="inline-flex size-12 items-center justify-center rounded-full bg-brand-soft text-brand"><x-icono nombre="subir" clase="size-5" /></span>Sube una foto del producto</span>
                        @endif
                    </template>
                </label>
                <input id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="elegir($event)">
                <x-input-error :messages="$errors->get('imagen')" />
            </section>
            <button type="submit" class="btn btn-primary w-full justify-between py-3.5 pl-6 pr-3">{{ $editando ? 'Guardar cambios' : 'Crear producto' }} <span class="btn-orb"><x-icono nombre="check" clase="size-4" /></span></button>
        </aside>
    </form>
    @if ($editando)
        <form method="POST" action="{{ route('productos.destroy', $producto) }}" class="mt-6" x-data @submit="if (! confirm('¿Eliminar {{ $producto->nombre }}?')) $event.preventDefault()">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm"><x-icono nombre="borrar" clase="size-4" /> Eliminar producto</button>
        </form>
    @endif
</x-app-layout>
