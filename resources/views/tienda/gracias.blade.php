<x-layouts.tienda titulo="Pedido confirmado">
    <section class="mx-auto max-w-2xl px-4 pt-14 text-center sm:px-6">
        <img src="{{ asset('images/ima6.jpg') }}" alt="Kuromi guiñando el ojo" class="reveal medalla mx-auto size-36 object-[50%_20%] shadow-sticker">
        <h1 class="reveal mt-7 font-display text-5xl font-bold tracking-tight" style="--delay: 80ms">¡Gracias, {{ strtok($pedido->cliente->nombre, ' ') }}!</h1>
        <p class="reveal mt-4 text-lg font-semibold text-ink-2" style="--delay: 160ms">Recibimos tu pedido <span class="num font-extrabold text-brand">{{ $pedido->folio }}</span>. Te escribiremos a {{ $pedido->cliente->email }} cuando salga a envío y te mandaremos tu factura en PDF.</p>

        <div class="reveal sticker mt-9 p-6 text-left" style="--delay: 240ms">
            <ul class="divide-y-2 divide-dashed divide-line">
                @foreach ($pedido->items as $item)
                    <li class="flex justify-between gap-4 py-3 font-semibold">
                        <span><span class="num text-ink-3">{{ $item->cantidad }} x</span> {{ $item->nombre }}</span>
                        <span class="num font-extrabold">${{ number_format($item->subtotal, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <div class="mt-3 flex items-baseline justify-between border-t-[3px] border-dashed border-line-strong pt-4">
                <span class="font-bold text-ink-2">Total</span>
                <span class="num font-display text-3xl font-bold text-accent-strong">${{ number_format($pedido->total, 2) }}</span>
            </div>
        </div>
        <a href="{{ route('tienda.catalogo') }}" class="btn btn-dark mt-9">Seguir explorando</a>
    </section>
</x-layouts.tienda>
