@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-lg bg-mint/10 px-4 py-3 text-sm font-semibold text-sage']) }}>
        {{ $status }}
    </div>
@endif
