@props(['cliente', 'tamano' => 'size-10'])
{{-- Foto del cliente o el avatar genérico original del proyecto. --}}
<img src="{{ $cliente->imagenUrl() ?? asset('images/Usuario.jpg') }}" alt="" {{ $attributes->merge(['class' => "{$tamano} shrink-0 rounded-full border-2 border-line-strong object-cover bg-surface"]) }}>
