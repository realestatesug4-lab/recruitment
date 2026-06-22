<section class="hero py-12 md:py-16">
    <div class="hero-label inline-flex items-center gap-2 text-sm font-medium text-sage bg-mint/12 border border-mint/20 px-3.5 py-1.5 rounded-full mb-6 uppercase tracking-wide">
        <span class="w-1.5 h-1.5 rounded-full bg-mint animate-pulse"></span>
        {{ $hero['label'] }}
    </div>
    <h1 class="hero-headline font-syne font-extrabold text-5xl md:text-7xl lg:text-8xl leading-[0.95] tracking-tighter text-deep max-w-4xl mb-7">
        {{ $hero['headline'] }} <em class="text-sage not-italic">{{ $hero['highlight'] }}</em><br>{{ $hero['suffix'] }}
    </h1>
    <p class="hero-sub text-text-mid max-w-md leading-relaxed mb-9">
        {{ $hero['description'] }}
    </p>

    {{-- Search Bar --}}
    <x-search-bar />

    {{-- Stats Row --}}
    <div class="stats-row flex items-center gap-5 mt-7">
        @foreach($stats as $stat)
            <div class="stat-item">
                <span class="stat-num font-syne font-bold text-deep text-xl">{{ $stat['value'] }}</span>
                <span class="stat-label text-xs text-text-light">{{ $stat['label'] }}</span>
            </div>
            @unless($loop->last)
                <div class="stat-sep w-px h-9 bg-forest/10"></div>
            @endunless
        @endforeach
    </div>

    {{-- Popular Searches --}}
    <div class="popular-row flex items-center flex-wrap gap-2.5 mt-14">
        <span class="popular-label text-xs font-medium text-text-light">Popular:</span>
        @foreach($popularSearches as $term)
        <a href="{{ route('jobs.index', ['q' => $term]) }}" class="pop-tag text-sm px-3.5 py-1.5 bg-white/60 border border-white/75 rounded-full text-text-mid transition-all duration-200 hover:bg-forest/7 hover:text-forest hover:border-mint/30">
            {{ $term }}
        </a>
        @endforeach
    </div>
</section>
