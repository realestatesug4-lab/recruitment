<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
