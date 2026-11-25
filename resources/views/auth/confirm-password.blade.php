<x-guest-layout titulo="Confirmar contraseña">
    <p class="eyebrow">Zona segura</p>
    <h1 class="mt-5 font-display text-3xl font-semibold tracking-tight">Confirma tu contraseña</h1>
    <p class="mt-2 text-ink-2">Por seguridad, confirma tu contraseña antes de continuar.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <button type="submit" class="btn btn-primary w-full">Confirmar</button>
    </form>
</x-guest-layout>
