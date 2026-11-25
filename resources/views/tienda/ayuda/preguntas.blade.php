@php
    $preguntas = [
        ['¿Necesito crear una cuenta para comprar?', 'No. Agregas tus productos al pedido, confirmas tu nombre, correo, teléfono y dirección, y listo. Usamos esos datos para el envío y para tu factura.'],
        ['¿Cómo hago mi pedido?', 'Arma tu pedido desde el <a class="link" href="'.route('tienda.catalogo').'">catálogo</a> o desde la ficha de cada producto, abre “Mi pedido” y confirma tus datos. Al terminar verás tu folio, por ejemplo PED-00046. Aquí está el <a class="link" href="'.route('inicio').'#como-funciona">paso a paso</a>.'],
        ['¿Cómo pago?', 'No cobramos en la página. Después de confirmar te contactamos por correo o teléfono para acordar el pago: transferencia, tarjeta de crédito o débito, PayPal o efectivo si recoges en Tantoyuca. La forma de pago aparece en tu factura.'],
        ['¿Me dan factura?', 'Sí. Escribe tu RFC al confirmar el pedido y la emitimos con tus datos. Te llega por correo en PDF (y el XML cuando lo tenemos). Si olvidaste el RFC, <a class="link" href="'.route('contacto').'">escríbenos</a> con tu folio.'],
        ['¿Cómo sé en qué va mi pedido?', 'Cada pedido pasa por cuatro estados: Nuevo, En proceso, Enviado y Entregado. Te escribimos al correo que dejaste cuando sale a envío. Si tienes dudas, mándanos tu folio desde <a class="link" href="'.route('contacto').'">Contacto</a>.'],
        ['¿Puedo recogerlo en Tantoyuca?', 'Claro. Escríbelo en “Notas” al confirmar tu pedido y te avisamos cuando esté listo. Puedes pasar de lunes a sábado, de 10:00 a 19:00. Más detalles en <a class="link" href="'.route('ayuda.envios').'">Envíos y entregas</a>.'],
        ['¿Puedo cambiar o cancelar mi pedido?', 'Sí, mientras no haya salido a envío. Escríbenos desde <a class="link" href="'.route('contacto').'">Contacto</a> con tu folio y lo ajustamos.'],
        ['¿Qué pasa si un producto está agotado?', 'No se puede agregar al pedido hasta que vuelva. En cada ficha verás si está disponible o cuántas piezas quedan, y las cantidades de tu pedido se ajustan al stock.'],
        ['¿Venden por mayoreo?', 'Sí. Si tienes una tienda o papelería, surtimos <a class="link" href="'.route('tienda.catalogo', ['categoria' => 'kuromi']).'">peluches</a>, <a class="link" href="'.route('tienda.catalogo', ['categoria' => 'figuras']).'">figuras</a> y <a class="link" href="'.route('tienda.catalogo', ['categoria' => 'laminas-y-stickers']).'">stickers</a> de Kuromi con factura. <a class="link" href="'.route('contacto').'">Escríbenos</a> con lo que necesitas y te mandamos precios.'],
    ];
@endphp
<x-layouts.tienda titulo="Preguntas frecuentes">
    <section class="mx-auto max-w-6xl px-4 pt-14 sm:px-6">
        @include('tienda.ayuda.partials.encabezado', [
            'imagen' => 'images/ima3.jpg',
            'eyebrow' => 'Ayuda',
            'titulo' => 'Preguntas frecuentes',
            'texto' => 'Lo que más nos preguntan antes de hacer un pedido, contestado aquí mismo.',
        ])

        <div class="mt-10 grid gap-8 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
            <div class="space-y-4" x-data="{ abierta: 0 }">
                @foreach ($preguntas as $i => [$pregunta, $respuesta])
                    <div class="reveal sticker overflow-hidden" style="--delay: {{ min($i, 5) * 60 }}ms">
                        <h2>
                            <button type="button" class="flex w-full items-center justify-between gap-4 p-5 text-left font-display text-lg font-semibold sm:px-6"
                                @click="abierta = abierta === {{ $i }} ? null : {{ $i }}" :aria-expanded="(abierta === {{ $i }}).toString()" aria-controls="respuesta-{{ $i }}">
                                {{ $pregunta }}
                                <span class="inline-flex size-9 shrink-0 items-center justify-center rounded-full border-[2.5px] border-sticker bg-accent text-on-accent transition duration-500 ease-[var(--ease-spring)]" :class="abierta === {{ $i }} && 'rotate-45'">
                                    <x-icono nombre="mas" clase="size-4" />
                                </span>
                            </button>
                        </h2>
                        <div id="respuesta-{{ $i }}" x-show="abierta === {{ $i }}" x-collapse @if ($i !== 0) x-cloak @endif>
                            <p class="border-t-2 border-dashed border-line px-5 pb-5 pt-4 font-semibold leading-relaxed text-ink-2 sm:px-6">{!! $respuesta !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="reveal lg:sticky lg:top-28" style="--delay: 160ms">
                <div class="sticker !bg-brand-soft p-6 text-center">
                    <img src="{{ asset('images/ima5.jpg') }}" alt="Kuromi dormida" class="medalla mx-auto size-28">
                    <p class="mt-4 font-display text-xl font-semibold">¿No encontraste tu respuesta?</p>
                    <p class="mt-1 font-semibold text-ink-2">Escríbenos y te contestamos en horario de tienda.</p>
                    <a href="{{ route('contacto') }}" class="btn btn-primary mt-5">Escríbenos <x-icono nombre="enviar" clase="size-4" /></a>
                </div>
            </aside>
        </div>
    </section>
</x-layouts.tienda>
