@props(['cliente', 'tamano' => 'size-10 text-sm'])
@if ($cliente->imagenUrl())
    <img src="{{ $cliente->imagenUrl() }}" alt="" {{ $attributes->merge(['class' => "{$tamano} shrink-0 rounded-full object-cover ring-1 ring-line"]) }}>
@else
    <span {{ $attributes->merge(['class' => "{$tamano} inline-flex shrink-0 items-center justify-center rounded-full bg-brand-soft font-semibold text-brand-strong"]) }}>{{ $cliente->iniciales() }}</span>
@endif
