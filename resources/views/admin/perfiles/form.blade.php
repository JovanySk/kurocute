@php $editando = $perfil->exists; @endphp
<x-app-layout :titulo="$editando ? 'Editar perfil' : 'Nuevo perfil'">
    <x-slot name="header">
        <div>
            <a href="{{ route('perfiles.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink-3 hover:text-ink"><x-icono nombre="izquierda" clase="size-4" /> Perfiles</a>
            <h1 class="mt-3 font-display text-3xl font-semibold tracking-tight">{{ $editando ? 'Editar perfil' : 'Nuevo perfil' }}</h1>
        </div>
    </x-slot>

    <form method="POST" action="{{ $editando ? route('perfiles.update', $perfil) : route('perfiles.store') }}" class="shell max-w-2xl">
        @csrf
        @if ($editando) @method('PUT') @endif
        <div class="core grid gap-5 p-6 sm:grid-cols-2 sm:p-8">
            <div class="sm:col-span-2">
                <label for="nombre" class="label">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre', $perfil->nombre) }}" required class="field">
                <x-input-error :messages="$errors->get('nombre')" />
            </div>
            <div>
                <label for="fecha_registro" class="label">Fecha de registro</label>
                <input id="fecha_registro" type="date" name="fecha_registro" value="{{ old('fecha_registro', $perfil->fecha_registro?->format('Y-m-d')) }}" required class="field">
                <x-input-error :messages="$errors->get('fecha_registro')" />
            </div>
            <div>
                <label for="estado" class="label">Estado</label>
                <select id="estado" name="estado" class="field">
                    <option value="activo" @selected(old('estado', $perfil->estado) === 'activo')>Activo</option>
                    <option value="inactivo" @selected(old('estado', $perfil->estado) === 'inactivo')>Inactivo</option>
                </select>
                <x-input-error :messages="$errors->get('estado')" />
            </div>
            <div class="sm:col-span-2">
                <label for="descripcion" class="label">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="3" class="field">{{ old('descripcion', $perfil->descripcion) }}</textarea>
                <x-input-error :messages="$errors->get('descripcion')" />
            </div>
            <div class="sm:col-span-2">
                <button class="btn btn-primary">{{ $editando ? 'Guardar cambios' : 'Crear perfil' }}</button>
            </div>
        </div>
    </form>
</x-app-layout>
