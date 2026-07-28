<div class="relative z-10 mt-20 rounded-t-2xl bg-deep pt-14 pb-8 text-white/50">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-7 grid grid-cols-1 gap-12 border-b border-white/10 pb-10 md:grid-cols-2 lg:grid-cols-4">
            <div>
                <div class="footer-brand mb-3 flex items-center gap-2 font-syne text-2xl font-extrabold text-white/90">
                    <svg class="h-6 w-6 text-amber" viewBox="0 0 26 26" fill="none" aria-hidden="true">
                        <path d="M3 20 L11 6 L16 14 L23 4" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="23" cy="4" r="2.4" fill="#ff9900"/>
                    </svg>
                    quicklinks.com
                </div>
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
