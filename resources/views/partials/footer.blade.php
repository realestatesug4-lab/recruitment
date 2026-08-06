<div class="relative z-10 mt-20 rounded-t-2xl bg-deep pt-14 pb-8 text-white/50">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-7 grid grid-cols-1 gap-12 border-b border-white/10 pb-10 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                 <!-- Logo -->
                    <div class="h-11 w-11 rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-gray-200 transition-transform duration-300 group-hover:scale-105">
                        <img
                            src="{{ asset('images/cranelinks.png') }}"
                            alt="QuickLinks Logo"
                            class="h-full w-full object-cover"
                        >
                    </div>

                    <!-- Brand -->
                    <div class="flex flex-col leading-none">
                        <span class="font-syne text-xl font-extrabold tracking-tight text-forest">
                            CraneLinks
                        </span>
                        <span class="text-xs uppercase tracking-[0.25em] text-gray-500">
                            One Click Away
                        </span>
                    </div>
                </a>
                <p class="mb-5 max-w-64 text-sm leading-relaxed">
                    Uganda's fast, low-data discovery layer for jobs, shops, services, and local opportunity.
                </p>
                <div class="flex gap-2.5">
                    @foreach(['in', 'x', 'fb'] as $social)
                    <a href="#" class="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-xs text-white/50 transition-all duration-200 hover:bg-white/15 hover:text-white/80">
                        {{ $social }}
                    </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="mb-4 font-syne text-sm font-semibold uppercase tracking-wide text-white/70">For Searchers</h4>
                <ul class="space-y-2.5">
                    @foreach(['Search Jobs', 'Browse Categories', 'SMS/USSD Access', 'AI Career Tools', 'Career Resources'] as $link)
                    <li><a href="#" class="text-sm text-white/40 transition-colors duration-200 hover:text-white/80">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-syne text-sm font-semibold uppercase tracking-wide text-white/70">For Business</h4>
                <ul class="space-y-2.5">
                    @foreach(['Post a Listing', 'Advertiser Dashboard', 'Company Page', 'SME Pricing', 'Enterprise'] as $link)
                    <li><a href="#" class="text-sm text-white/40 transition-colors duration-200 hover:text-white/80">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="mb-4 font-syne text-sm font-semibold uppercase tracking-wide text-white/70">Company</h4>
                <ul class="space-y-2.5">
                    @foreach(['About Us', 'Blog', 'Trust & Safety', 'Privacy Policy', 'Contact'] as $link)
                    <li><a href="#" class="text-sm text-white/40 transition-colors duration-200 hover:text-white/80">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 text-xs md:flex-row">
            <span>&copy; 2024 quicklinks.com. A project of Imagine Dreams Africa.</span>
            <div class="flex gap-5">
                <a href="#" class="text-white/30 transition-colors hover:text-white/60">Privacy</a>
                <a href="#" class="text-white/30 transition-colors hover:text-white/60">Terms</a>
                <a href="#" class="text-white/30 transition-colors hover:text-white/60">Sitemap</a>
            </div>
        </div>
    </div>
</div>
