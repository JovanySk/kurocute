@props(['producto'])
@if ($producto->imagenUrl())
    <img src="{{ $producto->imagenUrl() }}" alt="{{ $producto->nombre }}" loading="lazy" {{ $attributes->merge(['class' => 'size-full object-cover']) }}>
@else
    <svg viewBox="0 0 400 400" preserveAspectRatio="xMidYMid slice" role="img" aria-label="Ilustración de {{ $producto->nombre }}" {{ $attributes->merge(['class' => 'size-full']) }}>
        <use href="#kc-arte-{{ $producto->variante() }}"/>
    </svg>
@endif
