@props(['estado'])
@php
    $clase = match ($estado?->clave) {
        'pagada' => 'badge-ok',
        'pendiente' => 'badge-warn',
        'vencida' => 'badge-danger',
        default => 'badge-muted',
    };
@endphp
<span {{ $attributes->merge(['class' => "badge {$clase}"]) }}>{{ $estado?->nombre ?? 'Sin estado' }}</span>
