<x-layouts.tienda titulo="Contacto">
    <section class="mx-auto max-w-6xl px-4 pt-14 sm:px-6">
        @include('tienda.ayuda.partials.encabezado', [
            'imagen' => 'images/descarga3.jpg',
            'eyebrow' => 'Contacto',
            'titulo' => 'Escríbenos',
            'texto' => '¿Dudas con un pedido, tu factura o un producto? Mándanos un mensaje y te respondemos a tu correo.',
        ])

        <x-flash class="mt-8" />

        <div class="mt-10 grid gap-8 lg:grid-cols-[1.25fr_0.75fr] lg:items-start">
            <form method="POST" action="{{ route('contacto.enviar') }}" class="reveal core p-6 sm:p-8" novalidate>
                @csrf
                <h2 class="font-display text-xl font-semibold">Tu mensaje</h2>
                <p class="mt-1 text-sm text-ink-2">Todos los campos son obligatorios, salvo el folio.</p>

                <div class="mt-7 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="nombre" class="label">Nombre</label>
                        <input id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="120" autocomplete="name" class="field">
                        <x-input-error :messages="$errors->get('nombre')" />
                    </div>
                    <div>
                        <label for="email" class="label">Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="field" placeholder="tu@correo.com">
                        <x-input-error :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <label for="asunto" class="label">Tema</label>
                        <select id="asunto" name="asunto" required class="field">
                            <option value="" disabled @selected(! old('asunto'))>Elige un tema</option>
                            @foreach ($asuntos as $clave => $nombre)
                                <option value="{{ $clave }}" @selected(old('asunto') === $clave)>{{ $nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('asunto')" />
                    </div>
                    <div>
                        <label for="folio" class="label">Folio del pedido <span class="font-normal text-ink-3">(opcional)</span></label>
                        <input id="folio" name="folio" value="{{ old('folio') }}" maxlength="30" class="field uppercase" placeholder="PED-00000">
                        <x-input-error :messages="$errors->get('folio')" />
                    </div>
                    <div class="sm:col-span-2">
                        <label for="mensaje" class="label">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="5" required minlength="10" maxlength="2000" class="field">{{ old('mensaje') }}</textarea>
                        <x-input-error :messages="$errors->get('mensaje')" />
                    </div>
                    {{-- Campo trampa para bots: oculto para las personas. --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="sitio_web">Sitio web</label>
                        <input id="sitio_web" name="sitio_web" tabindex="-1" autocomplete="off">
                    </div>
                </div>
                <x-input-error :messages="$errors->get('sitio_web')" class="mt-4" />

                <button type="submit" class="btn btn-primary mt-8 w-full justify-between py-3.5 pl-6 pr-3 sm:w-auto sm:min-w-64">
                    Enviar mensaje <span class="btn-orb"><x-icono nombre="enviar" clase="size-4" /></span>
                </button>
            </form>

            <aside class="reveal space-y-5" style="--delay: 120ms">
                <div class="sticker !bg-brand-soft p-6">
                    <h2 class="font-display text-xl font-semibold">Tiendita KuroCute</h2>
                    <ul class="mt-4 space-y-3 font-semibold text-ink-2">
                        <li class="flex items-center gap-3"><span class="inline-flex size-9 shrink-0 items-center justify-center rounded-full border-2 border-line-strong bg-surface text-brand"><x-icono nombre="reloj" clase="size-4" /></span> Lunes a sábado, 10:00 a 19:00</li>
                        <li class="flex items-center gap-3"><span class="inline-flex size-9 shrink-0 items-center justify-center rounded-full border-2 border-line-strong bg-surface text-brand"><x-icono nombre="tienda" clase="size-4" /></span> Tantoyuca, Veracruz</li>
                        <li class="flex items-center gap-3"><span class="inline-flex size-9 shrink-0 items-center justify-center rounded-full border-2 border-line-strong bg-surface text-brand"><x-icono nombre="correo" clase="size-4" /></span> Te respondemos al correo que nos dejes</li>
                    </ul>
                </div>
                <div class="sticker flex items-center gap-4 p-5">
                    <img src="{{ asset('images/ima4.jpg') }}" alt="" class="medalla size-16 shrink-0">
                    <p class="text-sm font-semibold text-ink-2">Antes de escribir, quizá tu duda ya está en <a href="{{ route('ayuda.preguntas') }}" class="link">Preguntas frecuentes</a> o en <a href="{{ route('ayuda.envios') }}" class="link">Envíos y entregas</a>.</p>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.tienda>
