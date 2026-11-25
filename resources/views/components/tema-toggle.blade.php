<button type="button" x-data @click="$store.tema.alternar()"
    {{ $attributes->merge(['class' => 'relative inline-flex size-10 items-center justify-center rounded-full text-ink-2 ring-1 ring-line-strong transition hover:text-ink hover:ring-brand/50']) }}
    :aria-label="$store.tema.oscuro ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'">
    <span class="sr-only">Cambiar tema</span>
    <x-icono nombre="luna" clase="size-[1.15rem] transition duration-500 ease-[var(--ease-spring)]" x-show="!$store.tema.oscuro" />
    <x-icono nombre="sol" clase="size-[1.15rem] transition duration-500 ease-[var(--ease-spring)]" x-show="$store.tema.oscuro" x-cloak />
</button>
