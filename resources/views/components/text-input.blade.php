@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep shadow-sm outline-none transition placeholder:text-text-light focus:border-mint focus:ring-2 focus:ring-mint/30']) }}>
