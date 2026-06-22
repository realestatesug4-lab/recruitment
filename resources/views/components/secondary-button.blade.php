<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full bg-white/75 px-5 py-3 text-sm font-semibold text-forest transition hover:bg-white focus:outline-none focus:ring-2 focus:ring-mint/40 focus:ring-offset-2 disabled:opacity-60']) }}>
    {{ $slot }}
</button>
