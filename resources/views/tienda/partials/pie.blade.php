{{-- Pie de página de la tienda: marca, redes (próximamente), cinco columnas y créditos. --}}
@php
    $redes = [
        'Facebook' => '<path d="M14.2 8.2h2.6V4.6h-2.9c-3 0-4.6 1.9-4.6 4.7v2H7v3.6h2.3V21h3.9v-6.1h2.9l.5-3.6h-3.4V9.6c0-.9.4-1.4 1-1.4z" fill="currentColor"/>',
        'Instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.2" cy="6.8" r="1.3" fill="currentColor"/>',
        'TikTok' => '<path d="M16.6 3c.4 2.4 1.9 3.9 4.2 4.1v3.3c-1.6 0-3-.5-4.2-1.3v6.2a6.1 6.1 0 1 1-6.1-6.1c.3 0 .7 0 1 .1v3.4a2.8 2.8 0 1 0 1.9 2.6V3h3.2z" fill="currentColor"/>',
        'WhatsApp' => '<path d="M4 20l1.2-4A8.3 8.3 0 1 1 8.3 19z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9.3 7.6c.3 0 .5 0 .7.5l.8 1.8c.1.3 0 .5-.1.7l-.6.7c.6 1.2 1.6 2.2 2.8 2.8l.7-.6c.2-.2.4-.2.7-.1l1.8.8c.4.2.5.4.5.7 0 1-.9 2-2.1 2-3.5-.1-6.8-3.4-6.9-6.9 0-1.2 1-2.1 1.7-2.4z" fill="currentColor"/>',
    ];
    $enlace = 'inline-flex items-center gap-2 rounded-md text-white/75 transition duration-300 ease-[var(--ease-spring)] hover:translate-x-1 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ff6fb7]';
    $titulo = 'font-display text-lg font-semibold text-[#e0b8ff]';
@endphp
<footer class="mt-24 bg-night text-white">
    <div class="h-3 bg-[repeating-linear-gradient(90deg,#7b4397_0_24px,#ff4fa3_24px_48px)]"></div>

    <div class="mx-auto max-w-6xl px-4 sm:px-6">
        {{-- Marca y redes --}}
        <div class="flex flex-col gap-8 border-b border-white/10 py-12 md:flex-row md:items-end md:justify-between">
            <div class="max-w-md">
                <a href="{{ route('inicio') }}" class="group inline-flex items-center gap-3" aria-label="KuroCute, inicio">
                    <img src="{{ asset('images/ima6.jpg') }}" alt="" class="medalla size-14 !border-white object-[50%_20%] transition duration-500 ease-[var(--ease-spring)] group-hover:-rotate-12">
                    <span class="font-display text-3xl font-bold">Kuro<span class="text-[#ff6fb7]">Cute</span></span>
                </a>
                <p class="mt-4 leading-relaxed text-white/70">Tu tiendita favorita de Kuromi: peluches, figuras, maquillaje y hasta motos. Hecho con mucho cariño en Tantoyuca.</p>
            </div>
            <div>
                <p class="{{ $titulo }}">Síguenos</p>
                <ul class="mt-3 flex flex-wrap gap-3">
                    @foreach ($redes as $red => $icono)
                        <li>
                            <span role="img" aria-label="{{ $red }}: próximamente" title="Próximamente"
                                @class([
                                    'inline-flex size-12 cursor-default items-center justify-center rounded-full border-[2.5px] border-white text-white transition duration-500 ease-[var(--ease-spring)] hover:-translate-y-1 hover:rotate-0',
                                    'bg-[#7b4397] shadow-[3px_3px_0_#ff4fa3] -rotate-6' => $loop->even,
                                    'bg-[#ff4fa3] shadow-[3px_3px_0_#7b4397] rotate-6' => $loop->odd,
                                ])>
                                <svg viewBox="0 0 24 24" class="size-5" aria-hidden="true">{!! $icono !!}</svg>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Columnas de enlaces --}}
        <div class="grid grid-cols-2 gap-x-6 gap-y-10 py-12 sm:grid-cols-3 lg:grid-cols-5">
            <nav aria-labelledby="pie-tienda">
                <p id="pie-tienda" class="{{ $titulo }}">Tienda</p>
                <ul class="mt-4 space-y-2.5">
                    <li><a class="{{ $enlace }}" href="{{ route('inicio') }}">Inicio</a></li>
                    <li><a class="{{ $enlace }}" href="{{ route('tienda.catalogo') }}">Catálogo completo</a></li>
                    <li><a class="{{ $enlace }}" href="{{ route('inicio') }}#favoritos">Favoritos de Kuromi</a></li>
                    <li><a class="{{ $enlace }}" href="{{ route('carrito.index') }}">Mi pedido</a></li>
                </ul>
            </nav>

            <nav aria-labelledby="pie-rincon">
                <p id="pie-rincon" class="{{ $titulo }} inline-flex items-center gap-1.5">Rincón Kuromi <x-deco tipo="corazon" clase="size-4 text-[#ff6fb7]" /></p>
                <ul class="mt-4 space-y-2.5">
                    @foreach ($categoriasPie as $categoria)
                        <li>
                            <a class="{{ $enlace }}" href="{{ route('tienda.catalogo', ['categoria' => $categoria->slug]) }}">
                                <img src="{{ $categoria->portadaUrl() }}" alt="" class="size-7 shrink-0 rounded-full border-2 border-white/80 object-cover">
                                {{ $categoria->nombre }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <nav aria-labelledby="pie-ayuda">
                <p id="pie-ayuda" class="{{ $titulo }}">Ayuda</p>
                <ul class="mt-4 space-y-2.5">
                    <li><a class="{{ $enlace }}" href="{{ route('inicio') }}#como-funciona">Cómo pedir</a></li>
                    <li><a class="{{ $enlace }}" href="{{ route('ayuda.preguntas') }}">Preguntas frecuentes</a></li>
                    <li><a class="{{ $enlace }}" href="{{ route('ayuda.envios') }}">Envíos y entregas</a></li>
                    <li><a class="{{ $enlace }}" href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </nav>

            <nav aria-labelledby="pie-cuenta">
                <p id="pie-cuenta" class="{{ $titulo }}">Mi cuenta</p>
                <ul class="mt-4 space-y-2.5">
                    @auth
                        <li><a class="{{ $enlace }}" href="{{ route('dashboard') }}">Back office</a></li>
                        <li><a class="{{ $enlace }}" href="{{ route('profile.edit') }}">Mi perfil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="{{ $enlace }}">Cerrar sesión</button>
                            </form>
                        </li>
                    @else
                        <li><a class="{{ $enlace }}" href="{{ route('login') }}">Entrar</a></li>
                        <li><a class="{{ $enlace }}" href="{{ route('register') }}">Registrarme</a></li>
                        <li><a class="{{ $enlace }}" href="{{ route('password.request') }}">Recuperar contraseña</a></li>
                    @endauth
                </ul>
            </nav>

            <div class="col-span-2 sm:col-span-1">
                <p class="{{ $titulo }}">Contacto</p>
                <ul class="mt-4 space-y-2.5 text-white/75">
                    <li class="flex items-start gap-2"><x-icono nombre="reloj" clase="mt-0.5 size-4 shrink-0 text-[#ff6fb7]" /> Lunes a sábado, 10:00 a 19:00</li>
                    <li class="flex items-start gap-2"><x-icono nombre="tienda" clase="mt-0.5 size-4 shrink-0 text-[#ff6fb7]" /> Tantoyuca, Veracruz</li>
                    <li><a class="{{ $enlace }} font-bold !text-white" href="{{ route('contacto') }}"><x-icono nombre="correo" clase="size-4 shrink-0 text-[#ff6fb7]" /> Escríbenos</a></li>
                </ul>
            </div>
        </div>

        {{-- Créditos --}}
        <div class="flex flex-col gap-2 border-t border-white/10 py-6 text-sm text-white/60 md:flex-row md:items-center md:justify-between">
            <p>&copy; {{ date('Y') }} KuroCute. Todos los derechos reservados.</p>
            <p>Tienda desarrollada por <span class="font-semibold text-white/85">Jovany</span></p>
            <p class="text-xs text-white/40">Kuromi &copy; Sanrio. Sitio de fans.</p>
        </div>
    </div>
</footer>
