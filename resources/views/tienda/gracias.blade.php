<x-layouts.tienda titulo="Pedido confirmado">
    <section class="mx-auto max-w-2xl px-4 pt-16 text-center sm:px-6">
        <span class="reveal inline-flex size-20 items-center justify-center rounded-full bg-accent text-on-accent shadow-lift"><x-icono nombre="check" clase="size-9" /></span>
        <h1 class="reveal mt-8 font-display text-4xl font-semibold tracking-tight sm:text-5xl" style="--delay: 80ms">Gracias, {{ strtok($pedido->cliente->nombre, ' ') }}.</h1>
        <p class="reveal mt-4 text-lg text-ink-2" style="--delay: 160ms">Recibimos tu pedido <span class="num font-semibold text-ink">{{ $pedido->folio }}</span>. Te escribiremos a {{ $pedido->cliente->email }} cuando salga a envío y te mandaremos tu factura en PDF.</p>

        <div class="reveal shell mt-10 text-left" style="--delay: 240ms">
            <div class="core p-6">
                <ul class="divide-y divide-line">
                    @foreach ($pedido->items as $item)
                        <li class="flex justify-between gap-4 py-3 text-sm">
                            <span><span class="num text-ink-3">{{ $item->cantidad }} x</span> {{ $item->nombre }}</span>
                            <span class="num font-semibold">${{ number_format($item->subtotal, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3 flex items-baseline justify-between border-t border-line pt-4">
                    <span class="text-sm text-ink-2">Total</span>
                    <span class="num font-display text-2xl font-semibold">${{ number_format($pedido->total, 2) }}</span>
                </div>
            </div>
        </div>
        <a href="{{ route('tienda.catalogo') }}" class="btn btn-dark mt-10">Seguir explorando</a>
    </section>
</x-layouts.tienda>
