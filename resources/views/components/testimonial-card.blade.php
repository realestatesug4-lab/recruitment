@props(['quote', 'name', 'role', 'initial', 'bgColor'])

<div class="testi-item py-4 md:py-0 md:px-5 md:border-r border-forest/8 last:border-r-0">
    <div class="testi-quote text-sm text-text-mid leading-relaxed mb-4 italic">"{{ $quote }}"</div>
    <div class="testi-author flex items-center gap-2.5">
        <div class="testi-avatar w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0" style="background: {{ $bgColor }}">{{ $initial }}</div>
        <div>
            <div class="testi-name text-sm font-semibold text-deep">{{ $name }}</div>
            <div class="testi-role text-xs text-text-light">{{ $role }}</div>
        </div>
    </div>
</div>
