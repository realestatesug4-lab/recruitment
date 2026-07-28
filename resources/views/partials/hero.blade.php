<section class="hero relative mx-auto w-full max-w-7xl px-3 py-8 sm:px-6 sm:py-14 md:py-20">
    <div class="hero-shell relative overflow-hidden rounded-[22px] border border-electric-sapphire-200/40 bg-cream/82 px-4 py-8 shadow-[0_24px_80px_rgba(2,8,33,0.12)] backdrop-blur sm:rounded-[28px] sm:px-8 sm:py-10 lg:px-12">
        <div class="hero-grain pointer-events-none absolute inset-0 opacity-80"></div>

        <div class="relative z-10 mx-auto max-w-5xl text-center">
            <div class="hero-label mx-auto mb-5 inline-flex w-fit max-w-full items-center gap-2 rounded-full border border-electric-sapphire-200/45 bg-white/70 px-3.5 py-2 text-[10px] font-semibold uppercase tracking-[0.18em] text-sage sm:text-[11px] sm:tracking-[0.24em]">
                <span class="h-1.5 w-1.5 flex-shrink-0 rounded-full bg-amber"></span>
                {{ $hero['label'] }}
            </div>

            <h1 class="hero-headline mx-auto max-w-4xl font-syne text-4xl font-extrabold leading-[1.02] tracking-tight text-deep sm:text-6xl sm:leading-[0.98] lg:text-7xl">
                {{ $hero['headline'] }} <em class="not-italic text-forest">{{ $hero['highlight'] }}</em><br class="hidden sm:block">{{ $hero['suffix'] }}
            </h1>

            <p class="hero-sub mx-auto mt-5 max-w-2xl text-base leading-7 text-text-mid sm:text-lg">
                {{ $hero['description'] }}
            </p>

            <div class="search-section mx-auto mt-8 max-w-4xl">
                <x-search-bar placeholder="Try 'accountant jobs in Kampala' or 'plumber near me'" />

                <div class="resolve-line mx-auto mt-3 flex max-w-md items-center justify-center gap-3 text-xs font-semibold uppercase tracking-[0.18em] text-text-light">
                    <span>Average resolve time</span>
                    <span id="resolver-timer" class="rounded-full bg-amber/15 px-2.5 py-1 text-amber">0.38s</span>
                </div>

                <div class="popular-row mt-5 flex flex-wrap items-center justify-center gap-2">
                    <span class="popular-label w-full text-xs font-semibold uppercase tracking-[0.18em] text-text-light sm:w-auto">Popular</span>
                    @foreach($popularSearches as $term)
                    <a href="{{ route('jobs.index', ['q' => $term]) }}" class="pop-tag rounded-full border border-forest/10 bg-white/70 px-3.5 py-1.5 text-xs font-medium text-text-mid transition-all duration-200 hover:border-mint/40 hover:bg-forest hover:text-white active:scale-95 sm:text-sm">
                        {{ $term }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="resolver-stage relative z-10 mx-auto mt-8 h-[25rem] max-w-5xl overflow-hidden rounded-2xl border border-electric-sapphire-200/35 bg-white/58 p-4 shadow-inner shadow-electric-sapphire-950/5 sm:mt-10 sm:h-64 sm:p-6">
            <svg class="resolver-route absolute inset-0 h-full w-full" viewBox="0 0 900 260" preserveAspectRatio="none" aria-hidden="true">
                <path class="route-shadow" d="M40,160 C 200,50 340,230 500,95 S 720,70 860,130" />
                <path class="route-path" d="M40,160 C 200,50 340,230 500,95 S 720,70 860,130" />
            </svg>

            <div class="resolver-card resolver-card-query absolute left-4 top-5 w-[15rem] rounded-2xl border border-electric-sapphire-200/40 bg-cream/96 p-4 shadow-xl shadow-electric-sapphire-950/10 sm:left-8 sm:w-72">
                <div class="text-[10px] font-bold uppercase tracking-[0.22em] text-amber">Typed</div>
                <div class="resolver-query mt-2 font-syne text-lg font-bold text-deep">accountant jobs in Kampala</div>
                <div class="mt-2 text-xs text-text-light">Plain language, no filter maze</div>
            </div>

            <div class="resolver-card resolver-card-result absolute bottom-5 right-4 w-[16rem] rounded-2xl border border-electric-sapphire-200/30 bg-deep p-4 text-white shadow-xl shadow-electric-sapphire-950/20 sm:right-8 sm:w-80">
                <div class="text-[10px] font-bold uppercase tracking-[0.22em] text-wheat-300">Resolved</div>
                <div class="resolver-title mt-2 font-syne text-lg font-bold">Senior Accountant - Nakawa</div>
                <div class="resolver-meta mt-1 text-xs text-white/68">Fintech Co. | UGX 1.8M-2.4M</div>
            </div>
        </div>

        <div class="relative z-10 mt-8 grid gap-3 sm:grid-cols-3">
            @foreach($stats as $stat)
                <div class="stat-item rounded-2xl border border-electric-sapphire-200/35 bg-white/60 px-5 py-4 text-center shadow-sm shadow-electric-sapphire-950/5">
                    <span class="stat-num block font-syne text-2xl font-bold text-forest" data-count="{{ $stat['value'] }}" data-suffix="{{ $stat['suffix'] ?? '' }}" data-float="{{ ! empty($stat['float']) ? 'true' : 'false' }}">{{ $stat['value'] }}</span>
                    <span class="stat-label mt-1 block text-xs font-medium uppercase tracking-[0.16em] text-text-light">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>
