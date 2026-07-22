@props(['categories' => []])

<div class="bento-card card-categories glass h-full rounded-xl sm:rounded-2xl p-4 sm:p-5">
    <div class="card-tag text-xs font-semibold uppercase tracking-wide text-sage mb-3 flex items-center gap-1.5">
        <span class="w-1 h-1 rounded-full bg-sage"></span>
        Browse by Sector
    </div>

    <div class="card-title font-syne font-bold text-deep mb-4">What are you looking for?</div>

    <div class="categories-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-2.5">
        @foreach($categories as $cat)
            <a
                href="{{ route('jobs.index', ['category' => $cat['name'] ?? null]) }}"
                class="cat-item min-h-[5.5rem] sm:min-h-28 bg-mint/80 border border-white/70 rounded-lg p-3 sm:p-3.5 flex flex-col justify-between transition-all duration-200 hover:bg-forest hover:border-mint/30 active:scale-[0.97]"
            >
                <span class="cat-icon text-xl leading-none" aria-hidden="true">{{ $cat['icon'] ?? '•' }}</span>

                <span>
                    <span class="cat-name text-sm font-semibold text-amber block leading-snug">{{ $cat['name'] }}</span>
                    <span class="cat-count text-xs text-text-light block mt-1">{{ $cat['count'] }} jobs</span>
                </span>
            </a>
        @endforeach
    </div>
</div>
