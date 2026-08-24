@extends('layouts.app', ['page' => 'about'])

@section('title', 'About Us - CraneLinks')

@section('meta_description', 'CraneLinks bridges talent, trust and technology across Uganda. Meet the team building a fast, fair and frictionless recruitment and advertising platform.')

@php
    $stats = [
        ['value' => '1K+', 'label' => 'active job links', 'color' => 'text-amber'],
        ['value' => '50+', 'label' => 'partner companies', 'color' => 'text-forest'],
    ];

    $pillars = [
        ['icon' => '⚡', 'title' => 'Blazing speed', 'description' => 'Instant ad delivery, sub-second link resolution — because in Uganda, every second counts.'],
        ['icon' => '🔗', 'title' => 'Smart connectivity', 'description' => 'Our AI-driven mesh links job seekers to relevant products and opportunities, seamlessly.'],
        ['icon' => '🛡️', 'title' => 'Trust & transparency', 'description' => 'Every link is verified, every ad is authentic. We prioritise user safety and data privacy.'],
        ['icon' => '🌍', 'title' => 'Local roots, global reach', 'description' => 'Built for Ugandan businesses, backed by world-class infrastructure that scales.'],
    ];

    $values = [
        ['title' => '🤝 Integrity & transparency', 'body' => 'We believe in honest communication. Every ad, every link, every recommendation is built on a foundation of trust. No hidden agendas, no misleading metrics.'],
        ['title' => '⚡ Innovation & agility', 'body' => 'We move fast, iterate faster, and never stop learning. Our tech stack is future-ready, and our mindset is rooted in continuous improvement.'],
        ['title' => '🌱 Community & impact', 'body' => 'We are here to uplift Ugandan talent and enterprise. Every connection we facilitate contributes to economic growth, job creation, and digital inclusion.'],
        ['title' => '🎯 Excellence & reliability', 'body' => 'From uptime to user experience, we hold ourselves to the highest standards. Our platform is dependable, fast, and designed for real-world use.'],
    ];

    $contacts = [
        ['icon' => '📧', 'label' => 'hello@cranelinks.ug', 'href' => 'mailto:hello@cranelinks.ug'],
        ['icon' => '📞', 'label' => '+256 700 123 456', 'href' => 'tel:+256700123456'],
        ['icon' => '📍', 'label' => 'Kampala, Uganda · Innovation Hub'],
    ];
@endphp

@section('content')
<div class="page-wrap py-8 sm:py-12 lg:py-16">

    {{-- ===== Header ===== --}}
    <header class="fade-section mb-10 flex flex-col items-start gap-2 sm:mb-14">
        <span class="page-kicker"><span class="h-1.5 w-1.5 rounded-full bg-amber"></span> About us</span>
        <h1 class="about-headline">Crane<span class="text-amber">Links</span></h1>
        <p class="about-lead">Bridging talent, trust &amp; technology across Uganda.</p>
    </header>

    {{-- ===== Our story + quick stats (bento) ===== --}}
    <section class="fade-section mb-14 grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="bento-card glass flex flex-col p-6 sm:p-8 md:col-span-2">
            <span class="about-card-label">Who we are</span>
            <h2 class="mt-4 font-syne text-2xl font-bold text-deep">Our story</h2>
            <p class="mt-3 flex-1 leading-relaxed text-text-mid">
                We are a tight-knit team of tech enthusiasts, engineers, and creatives on a mission to transform
                Uganda's digital landscape. From the bustling streets of Kampala to emerging tech hubs, we saw the
                need for a recruitment and advertising platform that actually <span class="font-semibold text-deep">connects</span>
                — fast, fair, and frictionless.
            </p>
            <p class="mt-3 leading-relaxed text-text-mid">
                <span class="font-semibold text-forest">CraneLinks</span> was born to give job seekers, employers,
                and advertisers a trusted home. We combine African ingenuity with modern web performance, delivering
                quick links to opportunities, products, and services that drive real impact.
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
                <span class="about-chip">🇺🇬 Uganda</span>
                <span class="about-chip">⚡ Speed-first</span>
                <span class="about-chip">🔗 Trusted links</span>
            </div>
        </div>

        <div class="bento-card glass flex flex-col justify-center p-6 sm:p-8">
            @foreach($stats as $stat)
                <div @class(['mt-5' => !$loop->first])>
                    <div class="about-stat-num {{ $stat['color'] }}">{{ $stat['value'] }}</div>
                    <div class="about-stat-label">{{ $stat['label'] }}</div>
                </div>
            @endforeach
            <div class="my-4 h-px bg-white/60"></div>
            <p class="text-xs font-medium text-sage">⭐ 4.9 / 5 user trust</p>
        </div>
    </section>

    {{-- ===== Why CraneLinks ===== --}}
    <section class="fade-section mb-14">
        <div class="mb-6">
            <span class="page-kicker"><span class="h-1.5 w-1.5 rounded-full bg-amber"></span> Why CraneLinks</span>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($pillars as $pillar)
                <div class="bento-card glass flex flex-col items-start p-5 sm:p-6">
                    <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-mint/12 text-2xl" aria-hidden="true">{{ $pillar['icon'] }}</div>
                    <h3 class="font-syne text-lg font-bold text-deep">{{ $pillar['title'] }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-text-mid">{{ $pillar['description'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ===== Core values (accordion) ===== --}}
    <section class="fade-section mb-14" x-data="{ open: 0 }">
        <div class="mb-6">
            <span class="page-kicker"><span class="h-1.5 w-1.5 rounded-full bg-amber"></span> Our core values</span>
        </div>
        <div class="page-panel glass p-6 md:p-8">
            <div class="space-y-1">
                @foreach($values as $value)
                    <div class="about-value-item">
                        <button type="button"
                                class="about-value-toggle"
                                :aria-expanded="open === {{ $loop->index }}"
                                aria-controls="about-value-{{ $loop->index }}"
                                @click="open = open === {{ $loop->index }} ? -1 : {{ $loop->index }}">
                            <span>{{ $value['title'] }}</span>
                            <span class="about-value-icon" aria-hidden="true">+</span>
                        </button>
                        <div id="about-value-{{ $loop->index }}" x-cloak x-show="open === {{ $loop->index }}" x-collapse>
                            <p class="about-value-body">{{ $value['body'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===== Quote panel (parallax) ===== --}}
    <section class="fade-section mb-14">
        <div id="about-parallax" class="about-parallax glass-forest relative overflow-hidden rounded-[20px] p-8 text-white sm:rounded-[28px] md:p-12">
            <div class="relative z-10 max-w-2xl">
                <blockquote class="font-syne text-3xl font-bold leading-tight md:text-4xl">
                    “Connecting Uganda's future, one link at a time.”
                </blockquote>
                <p class="mt-3 max-w-lg text-base text-white/70">
                    We don't just serve ads — we build bridges between ambition and opportunity.
                </p>
                <div class="mt-6 flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-xl backdrop-blur" aria-hidden="true">🕊️</span>
                    <span class="text-sm font-medium text-white/80">CraneLinks · since 2026</span>
                </div>
            </div>
            <div class="about-quote-grain pointer-events-none absolute inset-0 opacity-40" aria-hidden="true"></div>
        </div>
    </section>

    {{-- ===== Reach out + newsletter (bento) ===== --}}
    <section class="fade-section grid grid-cols-1 gap-5 md:grid-cols-2">
        {{-- Contact card --}}
        <div class="bento-card glass flex flex-col p-6 sm:p-8">
            <span class="about-card-label">📬 Reach out</span>
            <h3 class="mt-3 font-syne text-2xl font-bold text-deep">Let's connect</h3>
            <div class="mt-5 space-y-3">
                @foreach($contacts as $contact)
                    <div class="about-contact-row">
                        <span class="text-xl" aria-hidden="true">{{ $contact['icon'] }}</span>
                        @isset($contact['href'])
                            <a href="{{ $contact['href'] }}" class="transition hover:text-forest">{{ $contact['label'] }}</a>
                        @else
                            <span>{{ $contact['label'] }}</span>
                        @endisset
                    </div>
                @endforeach
            </div>
            <div class="mt-5 flex flex-wrap gap-2">
                <span class="about-chip about-chip--sm">LinkedIn</span>
                <span class="about-chip about-chip--sm">Twitter</span>
                <span class="about-chip about-chip--sm">Instagram</span>
            </div>
        </div>

        {{-- Newsletter card --}}
        <div class="bento-card glass flex flex-col p-6 sm:p-8" x-data="{ subscribed: false }">
            <span class="about-card-label about-card-label--amber">🚀 Be part of the journey</span>
            <h3 class="mt-3 font-syne text-2xl font-bold text-deep">Join our newsletter</h3>
            <p class="mt-2 text-sm text-text-mid">Get job alerts, product updates, and insider tech news from Uganda.</p>

            <form class="mt-5 flex flex-col gap-3 sm:flex-row"
                  x-show="!subscribed"
                  @submit.prevent="subscribed = true">
                <label for="about-newsletter-email" class="sr-only">Email address</label>
                <input id="about-newsletter-email" type="email" required
                       placeholder="Your email address" class="about-input">
                <button type="submit" class="about-submit">Subscribe ✦</button>
            </form>

            <p x-cloak x-show="subscribed" class="mt-5 rounded-2xl border border-forest/15 bg-mint/10 px-4 py-3 text-sm font-medium text-forest">
                📬 Thanks for subscribing! You're now part of the CraneLinks community.
            </p>

            <p class="mt-3 text-[10px] uppercase tracking-[0.18em] text-text-light">No spam. Unsubscribe anytime.</p>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    // Subtle parallax on the quote panel (desktop only, motion-safe)
    (function () {
        const panel = document.getElementById('about-parallax');
        if (!panel || window.innerWidth <= 768) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        window.addEventListener('scroll', function () {
            const rect = panel.getBoundingClientRect();
            const offset = (rect.top + rect.height / 2 - window.innerHeight / 2) * 0.016;
            panel.style.transform = 'translateY(' + offset + 'px)';
        }, { passive: true });
    })();
</script>
@endpush
