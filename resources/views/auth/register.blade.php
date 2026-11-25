<x-guest-layout titulo="Crear cuenta">
    <p class="eyebrow">Nueva cuenta</p>
    <h1 class="mt-5 font-display text-3xl font-semibold tracking-tight">Crea tu cuenta</h1>
    <p class="mt-2 text-ink-2">Las cuentas nuevas reciben el perfil de Consulta (solo lectura).</p>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>
        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>
        <div>
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Confirma tu contraseña" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>
        <button type="submit" class="btn btn-primary w-full justify-between py-3.5 pl-6 pr-3">Crear cuenta <span class="btn-orb"><x-icono nombre="flecha" clase="size-4" /></span></button>
    </form>
    <p class="mt-6 text-center text-sm text-ink-2">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="link">Inicia sesión</a></p>
</x-guest-layout>
