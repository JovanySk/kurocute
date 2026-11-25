<x-layouts.tienda titulo="Confirmar pedido">
    <section class="mx-auto max-w-6xl px-4 pt-14 sm:px-6">
        <p class="eyebrow">Último paso</p>
        <div class="mt-3 flex items-center gap-4"><img src="{{ asset('images/ima3.jpg') }}" alt="" class="medalla size-16 sm:size-20"><h1 class="font-display text-4xl font-bold tracking-tight sm:text-5xl">Confirma tu pedido</h1></div>

        <x-flash class="mt-8" />

        @if ($partidas->isEmpty())
            <div class="shell mt-10">
                <div class="core flex flex-col items-center px-6 py-20 text-center">
                    <img src="{{ asset('images/ima5.jpg') }}" alt="Kuromi dormida" class="medalla size-36">
                    <p class="mt-5 font-display text-xl font-semibold">Tu pedido está vacío</p>
                    <p class="mt-1 text-ink-2">Agrega productos desde el catálogo para continuar.</p>
                    <a href="{{ route('tienda.catalogo') }}" class="btn btn-dark mt-6">Ir al catálogo</a>
                </div>
            </div>
        @else
            <div class="mt-10 grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
                <form method="POST" action="{{ route('carrito.confirmar') }}" class="order-2 lg:order-1">
                    @csrf
                    <div class="core p-6 sm:p-8">
                        <h2 class="font-display text-xl font-semibold">Tus datos</h2>
                        <p class="mt-1 text-sm text-ink-2">Los usamos para el envío y para emitir tu factura.</p>
                        <div class="mt-7 grid gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="nombre" class="label">Nombre completo</label>
                                <input id="nombre" name="nombre" value="{{ old('nombre') }}" required autocomplete="name" class="field">
                                <x-input-error :messages="$errors->get('nombre')" />
                            </div>
                            <div>
                                <label for="email" class="label">Correo electrónico</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" class="field" placeholder="tu@correo.com">
                                <x-input-error :messages="$errors->get('email')" />
                            </div>
                            <div>
                                <label for="telefono" class="label">Teléfono</label>
                                <input id="telefono" name="telefono" value="{{ old('telefono') }}" required autocomplete="tel" class="field">
                                <x-input-error :messages="$errors->get('telefono')" />
                            </div>
                            <div class="sm:col-span-2">
                                <label for="direccion" class="label">Dirección de envío</label>
                                <input id="direccion" name="direccion" value="{{ old('direccion') }}" required autocomplete="street-address" class="field">
                                <x-input-error :messages="$errors->get('direccion')" />
                            </div>
                            <div>
                                <label for="rfc" class="label">RFC <span class="font-normal text-ink-3">(opcional, para facturar)</span></label>
                                <input id="rfc" name="rfc" value="{{ old('rfc') }}" maxlength="13" class="field uppercase">
                                <x-input-error :messages="$errors->get('rfc')" />
                            </div>
                            <div class="sm:col-span-2">
                                <label for="notas" class="label">Notas <span class="font-normal text-ink-3">(opcional)</span></label>
                                <textarea id="notas" name="notas" rows="3" class="field">{{ old('notas') }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-8 w-full justify-between py-3.5 pl-6 pr-3 sm:w-auto sm:min-w-72">
                            Confirmar pedido <span class="btn-orb"><x-icono nombre="enviar" clase="size-4" /></span>
                        </button>
                    </div>
                </form>

                <aside class="order-1 lg:order-2">
                    <div class="panel p-6 lg:sticky lg:top-28">
                        <h2 class="font-display text-lg font-semibold">Resumen</h2>
                        <ul class="mt-5 divide-y divide-line">
                            @foreach ($partidas as $p)
                                <li class="flex items-center gap-4 py-3.5">
                                    <div class="size-14 shrink-0 overflow-hidden rounded-xl border-2 border-line-strong"><x-producto-arte :producto="$p['producto']" ajuste="object-contain p-1" /></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold">{{ $p['producto']->nombre }}</p>
                                        <p class="num text-xs text-ink-3">{{ $p['cantidad'] }} x ${{ number_format($p['producto']->precio, 2) }}</p>
                                    </div>
                                    <p class="num text-sm font-semibold">${{ number_format($p['subtotal'], 2) }}</p>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4 flex items-baseline justify-between border-t border-line pt-4">
                            <span class="text-sm text-ink-2">Total (IVA incluido)</span>
                            <span class="num font-display text-2xl font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <button type="button" x-data @click="$store.carrito.abrir()" class="link mt-4 text-sm">Editar cantidades</button>
                    </div>
                </aside>
            </div>
        @endif
    </section>
</x-layouts.tienda>
