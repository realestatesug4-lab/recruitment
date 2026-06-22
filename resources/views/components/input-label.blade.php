@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-deep']) }}>
    {{ $value ?? $slot }}
</label>
