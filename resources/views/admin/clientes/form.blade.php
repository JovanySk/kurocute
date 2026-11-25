@php $editando = $cliente->exists; @endphp
<x-app-layout :titulo="$editando ? 'Editar cliente' : 'Nuevo cliente'">
    <x-slot name="header">
        <div>
            <a href="{{ $editando ? route('clientes.show', $cliente) : route('clientes.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink-3 hover:text-ink"><x-icono nombre="izquierda" clase="size-4" /> Volver</a>
            <h1 class="mt-3 font-display text-3xl font-semibold tracking-tight">{{ $editando ? 'Editar cliente' : 'Nuevo cliente' }}</h1>
        </div>
    </x-slot>

    <form method="POST" enctype="multipart/form-data" action="{{ $editando ? route('clientes.update', $cliente) : route('clientes.store') }}" class="grid gap-4 lg:grid-cols-[1fr_20rem]">
        @csrf
        @if ($editando) @method('PUT') @endif

        <section class="shell">
            <div class="core grid gap-5 p-6 sm:grid-cols-2 sm:p-8">
                <div class="sm:col-span-2">
                    <label for="nombre" class="label">Nombre o razón social</label>
                    <input id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required class="field">
                    <x-input-error :messages="$errors->get('nombre')" />
                </div>
                <div>
                    <label for="rfc" class="label">RFC</label>
                    <input id="rfc" name="rfc" value="{{ old('rfc', $cliente->rfc) }}" required maxlength="13" class="field num uppercase" placeholder="GOMA850101AB1">
                    <x-input-error :messages="$errors->get('rfc')" />
                </div>
                <div>
                    <label for="telefono" class="label">Teléfono</label>
                    <input id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}" required class="field num" placeholder="55 1234 5678">
                    <x-input-error :messages="$errors->get('telefono')" />
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="label">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $cliente->email) }}" required class="field" placeholder="cliente@example.com">
                    <x-input-error :messages="$errors->get('email')" />
                </div>
                <div class="sm:col-span-2">
                    <label for="direccion" class="label">Dirección fiscal</label>
                    <input id="direccion" name="direccion" value="{{ old('direccion', $cliente->direccion) }}" required class="field">
                    <x-input-error :messages="$errors->get('direccion')" />
                </div>
                <div class="sm:col-span-2">
                    <label for="descripcion" class="label">Notas internas <span class="font-normal text-ink-3">(opcional)</span></label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="field">{{ old('descripcion', $cliente->descripcion) }}</textarea>
                    <x-input-error :messages="$errors->get('descripcion')" />
                </div>
            </div>
        </section>

        <aside class="space-y-4">
            <section class="panel p-6" x-data="vistaImagen(@js($cliente->imagenUrl()))">
                <p class="label">Foto o logotipo</p>
                <label for="imagen" class="group mt-2 flex aspect-square cursor-pointer flex-col items-center justify-center overflow-hidden rounded-3xl bg-surface-2 text-center border-2 border-dashed border-line-strong transition hover:border-brand/60">
                    <template x-if="url"><img :src="url" alt="Vista previa" class="size-full object-cover transition duration-700 group-hover:scale-105"></template>
                    <template x-if="!url">
                        <span class="flex flex-col items-center gap-2 px-6 text-sm text-ink-3">
                            <span class="inline-flex size-12 items-center justify-center rounded-full bg-brand-soft text-brand"><x-icono nombre="subir" clase="size-5" /></span>
                            Arrastra o elige una imagen
                            <span class="text-xs">JPG, PNG o WEBP, máx. 2 MB</span>
                        </span>
                    </template>
                </label>
                <input id="imagen" name="imagen" type="file" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="elegir($event)">
                <p class="mt-3 truncate text-xs text-ink-3" x-show="nombre" x-text="nombre"></p>
                <x-input-error :messages="$errors->get('imagen')" />
                @if ($editando && $cliente->imagen)
                    <label class="mt-3 flex items-center gap-2 text-sm text-ink-2"><input type="checkbox" name="quitar_imagen" value="1" class="rounded border-line-strong text-accent focus:ring-accent"> Quitar imagen actual</label>
                @endif
            </section>
            <button type="submit" class="btn btn-primary w-full justify-between py-3.5 pl-6 pr-3">
                {{ $editando ? 'Guardar cambios' : 'Crear cliente' }} <span class="btn-orb"><x-icono nombre="check" clase="size-4" /></span>
            </button>
        </aside>
    </form>
</x-app-layout>
