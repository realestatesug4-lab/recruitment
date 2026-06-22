<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white transition hover:bg-sage focus:outline-none focus:ring-2 focus:ring-mint/40 focus:ring-offset-2 disabled:opacity-60']) }}>
    {{ $slot }}
</button>
