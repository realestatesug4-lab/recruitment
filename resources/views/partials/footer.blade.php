<footer class="relative z-10 bg-deep text-white/50 pt-14 pb-8 mt-20 rounded-t-2xl">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 pb-10 border-b border-white/10 mb-7">
            {{-- Brand Column --}}
            <div>
                <div class="footer-brand font-syne font-extrabold text-2xl text-white/90 flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-mint inline-block"></span>
                    JobsUG
                </div>
                <p class="text-sm leading-relaxed mb-5 max-w-64">
                    Uganda's most trusted job marketplace. Connecting talent with opportunity since 2024.
                </p>
                <div class="flex gap-2.5">
                    @foreach(['in', '𝕏', 'fb'] as $social)
                    <a href="#" class="w-8 h-8 bg-white/7 rounded-full flex items-center justify-center text-white/50 text-xs hover:bg-white/15 hover:text-white/80 transition-all duration-200">
                        {{ $social }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- For Seekers --}}
            <div>
                <h4 class="font-syne font-semibold text-sm text-white/70 uppercase tracking-wide mb-4">For Seekers</h4>
                <ul class="space-y-2.5">
                    @foreach(['Browse Jobs', 'Companies', 'Salary Guide', 'AI Career Tools', 'Career Resources'] as $link)
                    <li><a href="#" class="text-sm text-white/40 hover:text-white/80 transition-colors duration-200">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- For Employers --}}
            <div>
                <h4 class="font-syne font-semibold text-sm text-white/70 uppercase tracking-wide mb-4">For Employers</h4>
                <ul class="space-y-2.5">
                    @foreach(['Post a Job', 'Search CVs', 'Company Page', 'Pricing', 'Enterprise'] as $link)
                    <li><a href="#" class="text-sm text-white/40 hover:text-white/80 transition-colors duration-200">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="font-syne font-semibold text-sm text-white/70 uppercase tracking-wide mb-4">Company</h4>
                <ul class="space-y-2.5">
                    @foreach(['About Us', 'Blog', 'Privacy Policy', 'Terms of Use', 'Contact'] as $link)
                    <li><a href="#" class="text-sm text-white/40 hover:text-white/80 transition-colors duration-200">{{ $link }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <span>© 2024 JobsUG. All rights reserved.</span>
            <div class="flex gap-5">
                <a href="#" class="text-white/30 hover:text-white/60 transition-colors">Privacy</a>
                <a href="#" class="text-white/30 hover:text-white/60 transition-colors">Terms</a>
                <a href="#" class="text-white/30 hover:text-white/60 transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
