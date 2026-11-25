<x-guest-layout titulo="Verifica tu correo">
    <p class="eyebrow">Un paso más</p>
    <h1 class="mt-5 font-display text-3xl font-semibold tracking-tight">Verifica tu correo</h1>
    <p class="mt-2 text-ink-2">Te enviamos un enlace de verificación. Si no lo recibiste, te mandamos otro.</p>

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status class="mt-6" status="Enviamos un nuevo enlace de verificación a tu correo." />
    @endif

    <div class="mt-8 flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Reenviar enlace</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost">Cerrar sesión</button>
        </form>
    </div>
</x-guest-layout>
