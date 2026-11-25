<x-guest-layout titulo="Iniciar sesión">
    <div class="flex items-center gap-3">
        <p class="eyebrow">Bienvenida de vuelta</p>
        <img src="{{ asset('images/estampita-kuromi.png') }}" alt="" width="287" height="320" class="estampita -my-4">
    </div>
    <h1 class="mt-5 font-display text-3xl font-semibold tracking-tight">Inicia sesión</h1>
    <p class="mt-2 text-ink-2">Entra al back office para gestionar pedidos, clientes y facturas.</p>

    <x-auth-session-status class="mt-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>
        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" value="Contraseña" />
                @if (Route::has('password.request'))
                    <a class="mb-1.5 text-xs font-semibold text-brand hover:underline" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
            </div>
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>
        <label for="remember_me" class="flex items-center gap-2.5 text-sm text-ink-2">
            <input id="remember_me" type="checkbox" name="remember" class="size-4 rounded border-line-strong text-accent focus:ring-accent">
            Mantener sesión iniciada
        </label>
        <button type="submit" class="btn btn-primary w-full justify-between py-3.5 pl-6 pr-3">Entrar <span class="btn-orb"><x-icono nombre="flecha" clase="size-4" /></span></button>
    </form>

    <div class="mt-8 rounded-2xl bg-surface-2 p-4 text-sm text-ink-2 ring-1 ring-line">
        <p class="font-semibold text-ink">Cuentas de demostración</p>
        <p class="mt-1">Administrador: <span class="num">admin@example.com</span> &middot; Consulta: <span class="num">consulta@example.com</span> &middot; contraseña <span class="num">password</span></p>
    </div>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-ink-2">¿No tienes cuenta? <a href="{{ route('register') }}" class="link">Regístrate</a></p>
    @endif
</x-guest-layout>
