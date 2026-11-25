<x-app-layout titulo="Mi cuenta">
    <x-slot name="header">
        <div>
            <p class="eyebrow">Ajustes</p>
            <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight">Mi cuenta</h1>
            <p class="mt-1.5 text-ink-2">Perfil asignado: {{ auth()->user()->perfil?->nombre ?? 'Sin perfil' }}.</p>
        </div>
    </x-slot>

    <div class="grid max-w-3xl gap-4">
        <div class="panel p-6 sm:p-8">@include('profile.partials.update-profile-information-form')</div>
        <div class="panel p-6 sm:p-8">@include('profile.partials.update-password-form')</div>
        <div class="panel p-6 sm:p-8">@include('profile.partials.delete-user-form')</div>
    </div>
</x-app-layout>
