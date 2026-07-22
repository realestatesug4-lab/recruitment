<header class="sticky top-0 z-50 px-3 pt-3 sm:px-4 sm:pt-4 md:px-6" x-data="{ open: false }" @keydown.escape.window="open = false">
    <nav class="nav-glass rounded-xl flex items-center justify-between px-4 py-3 sm:px-6 sm:py-3.5 transition-all duration-300">
        <a href="{{ route('home') }}" class="font-syne font-extrabold text-lg sm:text-xl text-forest tracking-tight flex items-center gap-2 min-w-0">
            <span class="w-2 h-2 rounded-full bg-mint inline-block flex-shrink-0"></span>
            JobsUG
        </a>

        <ul class="hidden md:flex gap-1 lg:gap-2 list-none">
            @foreach([
                ['Find a Job', route('jobs.index')],
                ['Companies', route('companies.index')],
                ['About Us', route('about')],
                ['Resources', route('resources')],
            ] as [$label, $href])
                <li>
                    <a href="{{ $href }}" class="text-sm font-medium text-text-mid px-3 lg:px-4 py-2 rounded-full transition hover:bg-forest/7 hover:text-forest">
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="hidden md:flex items-center gap-2">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-medium text-text-mid px-4 py-2 rounded-full border border-forest/15 hover:bg-forest/6 hover:border-forest/25 transition">
                    Sign in
                </a>
                <a href="{{ route('register') }}" class="text-sm font-medium text-white px-4 py-2.5 rounded-full bg-forest hover:bg-sage transition active:scale-[0.98]">
                    Post a Job &rarr;
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-white px-4 py-2.5 rounded-full bg-forest hover:bg-sage transition active:scale-[0.98]">
                    Dashboard
                </a>
            @endguest
        </div>

        {{-- Mobile: auth shortcut + hamburger --}}
        <div class="flex md:hidden items-center gap-2">
            @guest
                <a href="{{ route('login') }}" class="text-xs font-medium text-text-mid px-3 py-2 rounded-full border border-forest/15">
                    Sign in
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="text-xs font-medium text-white px-3 py-2 rounded-full bg-forest">
                    Dashboard
                </a>
            @endguest
            <button
                type="button"
                @click="open = !open"
                :aria-expanded="open"
                aria-controls="mobile-nav-drawer"
                aria-label="Toggle navigation menu"
                class="inline-flex items-center justify-center w-10 h-10 rounded-full border border-forest/15 text-forest hover:bg-forest/6 transition active:scale-95"
            >
                <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Slide-down mobile drawer --}}
    <div
        id="mobile-nav-drawer"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        @click.outside="open = false"
        class="md:hidden mt-2 nav-glass rounded-xl overflow-hidden shadow-lg"
    >
        <ul class="py-2">
            @foreach([
                ['Find a Job', route('jobs.index')],
                ['Companies', route('companies.index')],
                ['About Us', route('about')],
                ['Resources', route('resources')],
            ] as [$label, $href])
                <li>
                    <a
                        href="{{ $href }}"
                        @click="open = false"
                        class="flex items-center px-5 py-3.5 text-sm font-medium text-text-mid hover:bg-forest/6 hover:text-forest transition active:bg-forest/10"
                    >
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="border-t border-forest/10 px-4 py-4 flex flex-col gap-2">
            @guest
                <a href="{{ route('register') }}" @click="open = false" class="w-full text-center text-sm font-semibold text-white px-5 py-3 rounded-full bg-forest hover:bg-sage transition active:scale-[0.98]">
                    Post a Job &rarr;
                </a>
            @endguest
        </div>
    </div>
</header>
