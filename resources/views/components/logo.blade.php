@props(['tamano' => 'size-9', 'texto' => true])
{{-- Marca original de KuroCute: media luna con estrella dentro de un sello redondeado. --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <svg class="{{ $tamano }} shrink-0" viewBox="0 0 40 40" aria-hidden="true">
        <defs>
            <linearGradient id="kc-sello" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#2a1740"/>
                <stop offset="1" stop-color="#120a1b"/>
            </linearGradient>
        </defs>
        <rect width="40" height="40" rx="12" fill="url(#kc-sello)"/>
        <rect x=".5" y=".5" width="39" height="39" rx="11.5" fill="none" stroke="#fff" stroke-opacity=".12"/>
        <path d="M24.6 10.2a10 10 0 1 0 6.1 15.6A8.2 8.2 0 0 1 24.6 10.2z" fill="#c9b2f5"/>
        <path d="M27.5 9.5c.4 2.6 1.4 3.6 4 4-2.6.4-3.6 1.4-4 4-.4-2.6-1.4-3.6-4-4 2.6-.4 3.6-1.4 4-4z" fill="#f06aa8"/>
    </svg>
    @if ($texto)
        <span class="font-display text-[1.05rem] font-semibold tracking-tight text-ink">kuro<span class="text-accent">cute</span></span>
    @endif
</span>
