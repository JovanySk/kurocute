@php
    $mensajes = collect([
        'success' => ['bg-ok-soft text-ok', 'check'],
        'warning' => ['bg-warn-soft text-warn', 'alerta'],
        'error' => ['bg-danger-soft text-danger', 'alerta'],
    ])->filter(fn ($v, $clave) => session()->has($clave));
@endphp
@foreach ($mensajes as $clave => [$clase, $icono])
    <div x-data="{ visible: true }" x-show="visible" x-init="setTimeout(() => visible = false, 6000)"
        x-transition:leave="transition duration-500 ease-[var(--ease-out-soft)]" x-transition:leave-end="opacity-0 -translate-y-2"
        role="status" {{ $attributes->merge(['class' => 'mb-6 flex items-start gap-3 rounded-2xl bg-surface px-4 py-3.5 text-sm shadow-soft ring-1 ring-line']) }}>
        <span class="mt-0.5 inline-flex size-6 shrink-0 items-center justify-center rounded-full {{ $clase }}">
            <x-icono :nombre="$icono" clase="size-4" />
        </span>
        <p class="flex-1 pt-0.5 font-medium text-ink">{{ session($clave) }}</p>
        <button type="button" @click="visible = false" class="text-ink-3 hover:text-ink" aria-label="Cerrar aviso">
            <x-icono nombre="cerrar" clase="size-4" />
        </button>
    </div>
@endforeach
