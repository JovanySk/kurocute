<x-app-layout titulo="Perfiles">
    <x-slot name="header">
        <div>
            <p class="eyebrow">Ajustes</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight">Perfiles de usuario</h1>
            <p class="mt-1.5 text-ink-2">El perfil Administrador puede crear, editar y eliminar. El resto solo consulta.</p>
        </div>
        @can('administrar')
            <a href="{{ route('perfiles.create') }}" class="btn btn-primary self-start py-3 pl-5 pr-2.5 sm:self-auto">Nuevo perfil <span class="btn-orb"><x-icono nombre="mas" clase="size-4" /></span></a>
        @endcan
    </x-slot>

    <div class="panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="tabla">
                <thead><tr><th>Perfil</th><th>Descripción</th><th>Registro</th><th class="!text-right">Usuarios</th><th>Estado</th><th><span class="sr-only">Acciones</span></th></tr></thead>
                <tbody>
                    @foreach ($perfiles as $perfil)
                        <tr>
                            <td class="font-semibold"><span class="inline-flex items-center gap-2"><x-icono nombre="escudo" clase="size-4 text-brand" /> {{ $perfil->nombre }}</span></td>
                            <td class="max-w-md text-ink-2">{{ $perfil->descripcion }}</td>
                            <td class="num whitespace-nowrap text-ink-2">{{ $perfil->fecha_registro?->format('d/m/Y') }}</td>
                            <td class="num text-right">{{ $perfil->users_count }}</td>
                            <td><span class="badge {{ $perfil->estado === 'activo' ? 'badge-ok' : 'badge-muted' }}">{{ ucfirst($perfil->estado) }}</span></td>
                            <td>
                                @can('administrar')
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('perfiles.edit', $perfil) }}" class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-surface-2 hover:text-ink" aria-label="Editar {{ $perfil->nombre }}"><x-icono nombre="editar" clase="size-[1.1rem]" /></a>
                                        @if ($perfil->nombre !== \App\Models\Perfil::ADMINISTRADOR)
                                            <form method="POST" action="{{ route('perfiles.destroy', $perfil) }}" x-data @submit="if (! confirm('¿Eliminar el perfil {{ $perfil->nombre }}?')) $event.preventDefault()">
                                                @csrf @method('DELETE')
                                                <button class="inline-flex size-9 items-center justify-center rounded-full text-ink-2 hover:bg-danger-soft hover:text-danger" aria-label="Eliminar {{ $perfil->nombre }}"><x-icono nombre="borrar" clase="size-[1.1rem]" /></button>
                                            </form>
                                        @endif
                                    </div>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t border-line p-4">{{ $perfiles->links() }}</div>
    </div>
</x-app-layout>
