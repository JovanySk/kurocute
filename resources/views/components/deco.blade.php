@props(['tipo' => 'estrella', 'clase' => 'size-6'])
{{-- Garabatos decorativos: estrella, corazón, brillo y calavera con moño. --}}
@php
    $dibujos = [
        'estrella' => '<path d="M12 2.5l2.6 5.6 6.1.7-4.5 4.2 1.2 6-5.4-3-5.4 3 1.2-6L3.3 8.8l6.1-.7z" fill="currentColor" stroke="var(--sticker)" stroke-width="1.6" stroke-linejoin="round"/>',
        'corazon' => '<path d="M12 20.5S3.5 15.4 3.5 9.3A4.6 4.6 0 0 1 12 6.6a4.6 4.6 0 0 1 8.5 2.7c0 6.1-8.5 11.2-8.5 11.2z" fill="currentColor" stroke="var(--sticker)" stroke-width="1.6" stroke-linejoin="round"/>',
        'brillo' => '<path d="M12 2c.8 5.4 2.8 7.4 8 8.2-5.2.8-7.2 2.8-8 8.3-.8-5.5-2.8-7.5-8-8.3 5.2-.8 7.2-2.8 8-8.2z" fill="currentColor"/>',
        'calavera' => '<path d="M5 6.5 2 4.5v5l3-2M19 6.5l3-2v5l-3-2" fill="#ff4fa3" stroke="var(--sticker)" stroke-width="1.3" stroke-linejoin="round"/><circle cx="12" cy="7.5" r="1.6" fill="#ff4fa3" stroke="var(--sticker)" stroke-width="1.2"/><path d="M12 9.5c-4 0-6.5 2.6-6.5 6 0 2 1.2 3.2 2.8 3.6V21h7.4v-1.9c1.6-.4 2.8-1.6 2.8-3.6 0-3.4-2.5-6-6.5-6z" fill="currentColor" stroke="var(--sticker)" stroke-width="1.5" stroke-linejoin="round"/><circle cx="9.6" cy="15.4" r="1.4" fill="var(--sticker)"/><circle cx="14.4" cy="15.4" r="1.4" fill="var(--sticker)"/>',
    ];
@endphp
<svg {{ $attributes->merge(['class' => $clase]) }} viewBox="0 0 24 24" aria-hidden="true">{!! $dibujos[$tipo] ?? $dibujos['estrella'] !!}</svg>
