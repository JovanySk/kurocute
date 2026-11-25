@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginación" class="flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-sm text-ink-3">
            Mostrando <span class="num font-semibold text-ink">{{ $paginator->firstItem() }}</span> a
            <span class="num font-semibold text-ink">{{ $paginator->lastItem() }}</span> de
            <span class="num font-semibold text-ink">{{ $paginator->total() }}</span>
        </p>
        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="inline-flex size-9 items-center justify-center rounded-full text-ink-3 opacity-40"><x-icono nombre="izquierda" clase="size-4" /></span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex size-9 items-center justify-center rounded-full ring-1 ring-line-strong hover:bg-surface-2" aria-label="Anterior"><x-icono nombre="izquierda" clase="size-4" /></a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-ink-3">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="num inline-flex size-9 items-center justify-center rounded-full bg-ink text-sm font-semibold text-bg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="num inline-flex size-9 items-center justify-center rounded-full text-sm text-ink-2 hover:bg-surface-2 hover:text-ink">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex size-9 items-center justify-center rounded-full ring-1 ring-line-strong hover:bg-surface-2" aria-label="Siguiente"><x-icono nombre="siguiente" clase="size-4" /></a>
            @else
                <span class="inline-flex size-9 items-center justify-center rounded-full text-ink-3 opacity-40"><x-icono nombre="siguiente" clase="size-4" /></span>
            @endif
        </div>
    </nav>
@endif
