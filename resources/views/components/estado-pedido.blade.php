@props(['pedido'])
@php
    $clase = match ($pedido->estado) {
        'nuevo' => 'badge-accent',
        'en_proceso' => 'badge-brand',
        'enviado' => 'badge-warn',
        'entregado' => 'badge-ok',
        default => 'badge-muted',
    };
@endphp
<span {{ $attributes->merge(['class' => "badge {$clase}"]) }}>{{ $pedido->estadoNombre() }}</span>
