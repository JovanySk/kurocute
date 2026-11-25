@php
    $etapas = [
        ['Nuevo', 'Recibimos tu pedido y te contactamos para acordar el pago.', 'check'],
        ['En proceso', 'Lo empacamos con cuidado (y con mucho cariño).', 'caja'],
        ['Enviado', 'Sale a paquetería y te avisamos por correo.', 'camion'],
        ['Entregado', 'Llega a tus manos y tu factura PDF, a tu correo.', 'corazon'],
    ];
@endphp
<x-layouts.tienda titulo="Envíos y entregas">
    <section class="mx-auto max-w-6xl px-4 pt-14 sm:px-6">
        @include('tienda.ayuda.partials.encabezado', [
            'imagen' => 'images/image1.jpg',
            'eyebrow' => 'Ayuda',
            'titulo' => 'Envíos y entregas',
            'texto' => 'Mandamos tu pedido a todo México o lo recoges en nuestra tiendita de Tantoyuca.',
        ])

        <div class="mt-10 grid gap-6 md:grid-cols-2">
            <article class="reveal sticker p-6 sm:p-8">
                <span class="inline-flex size-12 items-center justify-center rounded-full border-[2.5px] border-sticker bg-brand text-white"><x-icono nombre="camion" clase="size-6" /></span>
                <h2 class="mt-4 font-display text-2xl font-bold">Envío nacional</h2>
                <ul class="mt-3 list-disc space-y-2 pl-5 font-semibold text-ink-2 marker:text-accent">
                    <li>Llega en unas 48 h hábiles después de que confirmamos tu pago.</li>
                    <li>Usamos la dirección que escribiste al confirmar el pedido.</li>
                    <li>Te escribimos al correo del pedido en cuanto sale a envío.</li>
                </ul>
            </article>
            <article class="reveal sticker p-6 sm:p-8" style="--delay: 90ms">
                <span class="inline-flex size-12 items-center justify-center rounded-full border-[2.5px] border-sticker bg-accent text-on-accent"><x-icono nombre="tienda" clase="size-6" /></span>
                <h2 class="mt-4 font-display text-2xl font-bold">Recoger en Tantoyuca</h2>
                <ul class="mt-3 list-disc space-y-2 pl-5 font-semibold text-ink-2 marker:text-accent">
                    <li>Escribe “recojo en tienda” en las notas de tu pedido.</li>
                    <li>Te avisamos cuando esté listo para pasar por él.</li>
                    <li>Lunes a sábado, de 10:00 a 19:00, en Tantoyuca, Veracruz.</li>
                </ul>
            </article>
        </div>

        <div class="reveal sticker mt-6 !bg-brand-soft p-6 sm:p-8">
            <p class="eyebrow">Tu pedido paso a paso</p>
            <ol class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($etapas as $n => [$estado, $texto, $icono])
                    <li class="relative rounded-2xl border-2 border-line-strong bg-surface p-5">
                        <span class="num absolute -top-3 right-4 inline-flex size-8 items-center justify-center rounded-full border-[2.5px] border-sticker bg-accent font-display font-bold text-on-accent">{{ $n + 1 }}</span>
                        <x-icono :nombre="$icono" clase="size-6 text-brand" />
                        <p class="mt-3 font-display text-lg font-semibold">{{ $estado }}</p>
                        <p class="mt-1 text-sm font-semibold text-ink-2">{{ $texto }}</p>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-[1fr_auto] md:items-center">
            <div class="reveal flex items-start gap-4">
                <img src="{{ asset('images/img2.jpg') }}" alt="Moto deportiva verde" class="size-20 shrink-0 rounded-2xl border-[3px] border-sticker object-cover">
                <div>
                    <h2 class="font-display text-xl font-semibold">¿Y las motos?</h2>
                    <p class="mt-1 font-semibold text-ink-2">Coordinamos la entrega contigo, siempre con factura y asesoría de pago.</p>
                </div>
            </div>
            <div class="reveal flex flex-wrap gap-3">
                <a href="{{ route('ayuda.preguntas') }}" class="btn btn-ghost">Más preguntas</a>
                <a href="{{ route('contacto') }}" class="btn btn-primary">Escríbenos</a>
            </div>
        </div>
    </section>
</x-layouts.tienda>
