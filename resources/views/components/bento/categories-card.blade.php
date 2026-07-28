@props(['categories' => []])

<div class="bento-card card-categories glass h-full rounded-xl p-4 sm:rounded-2xl sm:p-5">
    <div class="card-tag mb-3 flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-sage">
        <span class="h-1 w-1 rounded-full bg-sage"></span>
        Category fallback
    </div>

    <div class="card-title mb-4 font-syne font-bold text-deep">Prefer to look around first?</div>

    <div class="categories-grid grid grid-cols-2 gap-2 sm:grid-cols-3 sm:gap-2.5 md:grid-cols-4">
        @foreach($categories as $cat)
            <a
                href="{{ route('jobs.index', ['category' => $cat['name'] ?? null]) }}"
                class="cat-item group flex min-h-[5.5rem] flex-col justify-between rounded-lg border border-forest/10 bg-white/65 p-3 transition-all duration-200 hover:border-mint/40 hover:bg-forest active:scale-[0.97] sm:min-h-28 sm:p-3.5"
            >
                <span class="cat-icon flex h-8 w-8 items-center justify-center rounded-lg border border-mint/25 bg-mint/10 font-syne text-xs font-bold leading-none text-forest group-hover:border-white/20 group-hover:bg-white/10 group-hover:text-mint" aria-hidden="true">{{ $cat['icon'] ?? 'QL' }}</span>

                <span>
                    <span class="cat-name block text-sm font-semibold leading-snug text-deep transition-colors group-hover:text-white">{{ $cat['name'] }}</span>
                    <span class="cat-count mt-1 block text-xs text-text-light transition-colors group-hover:text-white/60">{{ $cat['count'] }} listings</span>
                </span>
            </a>
        @endforeach
    </div>
</div>
