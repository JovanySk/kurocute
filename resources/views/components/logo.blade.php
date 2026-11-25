@props(['tamano' => 'size-10', 'texto' => true])
{{-- Marca: recorte circular de una ilustración de Kuromi del proyecto + logotipo "KuroCute". --}}
<span {{ $attributes->merge(['class' => 'group inline-flex items-center gap-2.5']) }}>
    <img src="{{ asset('images/ima6.jpg') }}" alt="" class="medalla {{ $tamano }} shrink-0 object-[50%_20%] transition duration-500 ease-[var(--ease-spring)] group-hover:-rotate-12">
    @if ($texto)
        <span class="font-display text-[1.45rem] font-bold leading-none tracking-tight text-ink">Kuro<span class="text-accent">Cute</span></span>
    @endif
</span>
