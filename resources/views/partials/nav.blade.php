<nav
    class="sticky top-4 z-50 mx-6 rounded-xl nav-glass flex items-center justify-between px-7 py-3.5 transition-all duration-300"
    x-data="{ open: false }"
>
    <a href="{{ route('home') }}" class="font-syne font-extrabold text-xl text-forest tracking-tight flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-mint inline-block"></span>
        JobsUG
    </a>

    <ul class="hidden md:flex gap-2 list-none">
        @foreach([
            ['Find a Job', route('jobs.index')],
            ['Companies', route('companies.index')],
            ['About Us', route('about')],
            ['Resources', route('resources')],
        ] as [$label, $href])
            <li>
                <a href="{{ $href }}" class="text-sm font-medium text-text-mid px-4 py-2 rounded-full transition hover:bg-forest/7 hover:text-forest">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="flex items-center gap-2.5">
        @guest
            <a href="{{ route('login') }}" class="text-sm font-medium text-text-mid px-5 py-2 rounded-full border border-forest/15 hover:bg-forest/6 hover:border-forest/25 transition">
                Sign in
            </a>
            <a href="{{ route('register') }}" class="text-sm font-medium text-white px-5 py-2 rounded-full bg-forest hover:bg-sage transition hover:-translate-y-px hover:shadow-lg">
                Post a Job &rarr;
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-white px-5 py-2 rounded-full bg-forest hover:bg-sage transition">
                Dashboard
            </a>
        @endguest
    </div>
</nav>
