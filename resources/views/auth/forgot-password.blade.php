<x-guest-layout titulo="Recuperar contraseña">
    <p class="eyebrow">Recuperar acceso</p>
    <h1 class="mt-5 font-display text-3xl font-semibold tracking-tight">¿Olvidaste tu contraseña?</h1>
    <p class="mt-2 text-ink-2">Escribe tu correo y te enviaremos un enlace para elegir una nueva.</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>
        <button type="submit" class="btn btn-primary w-full">Enviar enlace</button>
    </form>
    <p class="mt-6 text-center text-sm"><a href="{{ route('login') }}" class="link">Volver a iniciar sesión</a></p>
</x-guest-layout>
