@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-2xl bg-ok-soft px-4 py-3 text-sm font-medium text-ok']) }}>
        {{ $status }}
    </div>
@endif
