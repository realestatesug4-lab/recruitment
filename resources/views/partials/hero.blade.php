
{{-- <section class="hero py-12 md:py-16">
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

    {{-- <x-search-bar /> --}}

    {{-- Stats Row
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

    {{-- Popular Searches
    <div class="popular-row flex items-center flex-wrap gap-2.5 mt-14">
        <span class="popular-label text-xs font-medium text-text-light">Popular:</span>
        @foreach($popularSearches as $term)
        <a href="{{ route('jobs.index', ['q' => $term]) }}" class="pop-tag text-sm px-3.5 py-1.5 bg-white/60 border border-white/75 rounded-full text-text-mid transition-all duration-200 hover:bg-forest/7 hover:text-forest hover:border-mint/30">
            {{ $term }}
        </a>
        @endforeach
    </div>
</section>
--}}

<section class="hero relative w-full max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12 md:py-20 overflow-hidden">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-20 items-center">

        {{-- LEFT COLUMN: Text & Data --}}
        <div class="hero-content flex flex-col z-10">
            <div class="hero-label inline-flex items-center gap-2 text-xs sm:text-sm font-medium text-sage bg-mint/12 border border-mint/20 px-3 py-1.5 rounded-full mb-4 sm:mb-6 uppercase tracking-wide w-fit">
                <span class="w-1.5 h-1.5 rounded-full bg-mint animate-pulse"></span>
                {{ $hero['label'] }}
            </div>

            <h1 class="hero-headline font-syne font-extrabold text-4xl sm:text-5xl md:text-7xl lg:text-8xl leading-[1] sm:leading-[0.95] tracking-tighter text-deep max-w-2xl mb-5 sm:mb-7">
                {{ $hero['headline'] }} <em class="text-sage not-italic">{{ $hero['highlight'] }}</em><br>{{ $hero['suffix'] }}
            </h1>

            <p class="hero-sub text-sm sm:text-base text-text-mid max-w-md leading-relaxed mb-6 sm:mb-9">
                {{ $hero['description'] }}
            </p>

            {{-- Stats Row --}}
            <div class="stats-row flex items-center gap-3 sm:gap-5 mt-1 sm:mt-3 flex-wrap">
                @foreach($stats as $stat)
                    <div class="stat-item">
                        <span class="stat-num font-syne font-bold text-deep text-lg sm:text-xl" data-count="{{ $stat['value'] }}" data-suffix="{{ $stat['suffix'] ?? '' }}">{{ $stat['value'] }}</span>
                        <span class="stat-label text-xs text-text-light block">{{ $stat['label'] }}</span>
                    </div>
                    @unless($loop->last)
                        <div class="stat-sep w-px h-8 sm:h-9 bg-forest/10 hidden sm:block"></div>
                    @endunless
                @endforeach
            </div>
        </div>

        {{-- RIGHT COLUMN: Bento visuals --}}
        <div class="hero-visuals relative h-[260px] sm:h-[340px] lg:h-[500px] w-full flex items-center justify-center order-first lg:order-last">
            <div class="absolute w-48 sm:w-72 h-48 sm:h-72 bg-sage/10 rounded-full blur-3xl -z-10 animate-pulse-slow"></div>

            <div class="bento-container relative w-full max-w-[480px] h-full flex flex-col gap-3 sm:gap-4 lg:gap-6">

                {{-- Bento Card 1 (Top - Large) --}}
                <div class="bento-card bento-card-1 flex-1 relative w-full rounded-3xl bg-white/70 backdrop-blur-xl border border-white/50 shadow-[0_8px_32px_rgba(0,0,0,0.05)] p-6 overflow-hidden flex flex-col justify-end">
                    <img src="images/stats.jpg" alt="Analytics" class="absolute inset-0 w-full h-full object-cover opacity-80 mix-blend-multiply">
                    <div class="relative z-10 bg-white/60 backdrop-blur-sm p-3 rounded-xl w-max shadow-sm border border-white/40">
                        <div class="text-xs font-bold text-forest">+43% Revenue</div>
                    </div>
                </div>

                {{-- Bottom Row (Split into 2 smaller cards) --}}
                <div class="flex flex-row h-[40%] gap-4 lg:gap-6">

                    {{-- Bento Card 2 (Bottom Left) --}}
                    <div class="bento-card bento-card-2 flex-1 rounded-3xl bg-forest/90 backdrop-blur-md border border-white/10 shadow-xl p-4 flex flex-col justify-between relative overflow-hidden">
                         <img src="images/stats.jpg" alt="Analytics" class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-multiply">
                        <div class="flex flex-col">
                            <span class="text-xs text-white/50 uppercase tracking-wider">Balance</span>
                            <span class="text-2xl font-bold text-white font-syne">$25,000</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-sage/20 flex items-center justify-center text-white border border-white/10">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>

                    {{-- Bento Card 3 (Bottom Right) --}}
                    <div class="bento-card bento-card-3 flex-1 rounded-3xl bg-[#e7e5e4]/90 backdrop-blur-md border border-white/40 shadow-xl p-4 flex flex-col justify-between relative overflow-hidden">
                         <img src="images/stats.jpg" alt="Analytics" class="absolute inset-0 w-full h-full object-cover opacity-90 mix-blend-multiply">
                        <div class="flex items-center gap-2 mb-1">
                            <div class="w-6 h-6 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-deep">Success</span>
                        </div>
                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-[10px] text-text-mid">Sent to Jack</span>
                            <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden border-2 border-white">
                                <img src="https://i.pravatar.cc/150?u=jack" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Floating Decorative Elements for depth --}}
            <div class="float-el-1 absolute -top-4 -right-4 w-16 h-16 rounded-full bg-white/30 backdrop-blur-xl border border-white/40 shadow-lg"></div>
            <div class="float-el-2 absolute bottom-4 -left-6 w-12 h-12 rounded-full bg-sage/20 backdrop-blur-lg border border-white/20 shadow-lg"></div>
        </div>
    </div>

    <div class="search-section mt-6 sm:mt-8 lg:mt-10">
        <div class="glass-amber rounded-xl sm:rounded-2xl p-4 sm:p-6 mx-0">
             {{-- Search Bar --}}
            <x-search-bar />

            {{-- Popular Searches --}}
            <div class="popular-row flex items-center flex-wrap gap-2 sm:gap-2.5 mt-6 sm:mt-8 lg:mt-10">
                <span class="popular-label text-xs font-medium text-text-light w-full sm:w-auto mb-1 sm:mb-0">Popular:</span>
                @foreach($popularSearches as $term)
                <a href="{{ route('jobs.index', ['q' => $term]) }}" class="pop-tag text-xs sm:text-sm px-3 sm:px-3.5 py-1.5 bg-mint/15 border border-mint/25 rounded-full text-text-mid transition-all duration-200 hover:bg-forest hover:text-white hover:border-mint/30 active:scale-95">
                    {{ $term }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

</section>

