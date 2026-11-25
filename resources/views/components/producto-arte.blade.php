@props(['producto', 'ajuste' => 'object-contain p-3'])
{{-- Foto real del producto sobre fondo lavanda (las fotos originales son pequeñas o con fondo blanco). --}}
<div {{ $attributes->merge(['class' => 'foto size-full']) }}>
    <img src="{{ $producto->fotoUrl() }}" alt="{{ $producto->nombre }}" loading="lazy" class="size-full {{ $ajuste }} mix-blend-multiply dark:mix-blend-normal">
</div>
