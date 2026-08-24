@extends('layouts.app', ['page' => 'resources'])

@section('title', 'Resources - Guides, Tips & Insights | CraneLinks')

@section('meta_description', 'Educational articles, career guides, and hiring insights for Ugandan job seekers and employers. Practical, low-data-friendly reads from the CraneLinks team.')

@section('robots', 'index, follow')

@php
    $articles = $viewModel->articles();
    $counts = collect($articles)->groupBy('category')->map->count();
@endphp

@push('head')
{{-- SEO: CollectionPage + ItemList of articles --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "CollectionPage",
    "name": "CraneLinks Resources",
    "description": "Educational articles, career guides, and hiring insights for Ugandan job seekers and employers.",
    "url": "{{ route('resources') }}",
    "mainEntity": {
        "@@type": "ItemList",
        "itemListElement": [
            @foreach($articles as $article)
            {
                "@@type": "ListItem",
                "position": {{ $loop->iteration }},
                "name": "{{ $article['title'] }}",
                "url": "{{ url('/resources/' . $article['slug']) }}"
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
}
</script>
@endpush

@section('content')
<div class="page-wrap py-8 sm:py-12 lg:py-16" x-data="{ tab: 'all' }">

    {{-- ===== Header ===== --}}
    <header class="fade-section mb-8 flex flex-col items-start gap-2 sm:mb-10">
        <span class="page-kicker"><span class="h-1.5 w-1.5 rounded-full bg-amber"></span> Resources</span>
        <h1 class="res-headline">Guides, tips &amp; <span class="text-forest">insights</span></h1>
        <p class="res-lead">
            Practical, low-data-friendly reads to help you land the role, grow your career,
            or hire smarter — written for the Ugandan market.
        </p>
    </header>

    {{-- ===== Tabs ===== --}}
    <nav class="fade-section mb-10" aria-label="Article categories">
        <div class="res-tabs" role="tablist">
            @foreach($viewModel->tabs() as $tab)
                @php $count = $tab['key'] === 'all' ? count($articles) : $counts->get($tab['key'], 0); @endphp
                <button type="button"
                        role="tab"
                        class="res-tab"
                        :class="{ 'res-tab--active': tab === '{{ $tab['key'] }}' }"
                        :aria-selected="tab === '{{ $tab['key'] }}'"
                        @click="tab = '{{ $tab['key'] }}'">
                    {{ $tab['label'] }}
                    <span class="ml-1.5 text-xs opacity-70">{{ $count }}</span>
                </button>
            @endforeach
        </div>
    </nav>

    {{-- ===== Featured + popular reads ===== --}}
    <section class="fade-section mb-12 grid grid-cols-1 gap-5 lg:grid-cols-3">
        @php $featured = $viewModel->featured(); @endphp

        {{-- Featured article --}}
        <article class="bento-card glass res-featured group lg:col-span-2">
            <div class="hero-grain pointer-events-none absolute inset-0 opacity-70" aria-hidden="true"></div>
            <div class="relative z-10">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="res-card-badge badge-green">{{ $featured['category_label'] }}</span>
                    <span class="text-xs font-semibold uppercase tracking-[0.2em] text-amber">Featured</span>
                </div>
                <h2 class="res-featured-title">{{ $featured['title'] }}</h2>
                <p class="res-featured-excerpt">{{ $featured['excerpt'] }}</p>
            </div>
            <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="res-card-meta">
                    <span>{{ $featured['author'] }}</span>
                    <span aria-hidden="true">·</span>
                    <time>{{ $featured['date'] }}</time>
                    <span aria-hidden="true">·</span>
                    <span>{{ $featured['read_time'] }}</span>
                </div>
                {{-- TODO: wire to the article detail route once /resources/{slug} lands --}}
                <a href="#" class="res-featured-cta">Read the guide <span aria-hidden="true">→</span></a>
            </div>
        </article>

        {{-- Popular reads --}}
        <aside class="bento-card glass p-6 sm:p-8">
            <h2 class="res-popular-title">
                <span class="h-px w-5 bg-sage" aria-hidden="true"></span>
                Most read
            </h2>
            <ol class="mt-4 space-y-1">
                @foreach($viewModel->popular() as $item)
                    <li class="res-popular-item">
                        <span class="res-popular-num" aria-hidden="true">{{ $loop->iteration }}</span>
                        <div>
                            {{-- TODO: wire to the article detail route once /resources/{slug} lands --}}
                            <a href="#" class="res-popular-link">{{ $item['title'] }}</a>
                            <p class="res-popular-meta">{{ $item['read_time'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </aside>
    </section>

    {{-- ===== Article grid (filtered by tabs) ===== --}}
    <section class="fade-section mb-14" aria-label="Latest articles">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($articles as $article)
                <article class="bento-card glass res-card group"
                         x-cloak
                         x-show="tab === 'all' || tab === '{{ $article['category'] }}'"
                         x-transition.opacity
                         data-slug="{{ $article['slug'] }}">
                    <span class="res-card-badge {{ $article['badge_class'] }}">{{ $article['category_label'] }}</span>
                    {{-- TODO: wire to the article detail route once /resources/{slug} lands --}}
                    <h3 class="res-card-title"><a href="#">{{ $article['title'] }}</a></h3>
                    <p class="res-card-excerpt">{{ $article['excerpt'] }}</p>
                    <div class="res-card-meta">
                        <time>{{ $article['date'] }}</time>
                        <span aria-hidden="true">·</span>
                        <span>{{ $article['read_time'] }}</span>
                    </div>
                    <div class="res-card-author">
                        <span class="res-card-avatar" aria-hidden="true">{{ strtoupper(substr($article['author'], 0, 1)) }}</span>
                        <span>{{ $article['author'] }}</span>
                        {{-- TODO: wire to the article detail route once /resources/{slug} lands --}}
                        <a href="#" class="res-card-link">Read <span aria-hidden="true">→</span></a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- ===== Newsletter panel ===== --}}
    <section class="fade-section" x-data="{ subscribed: false }">
        <div class="glass-forest relative overflow-hidden rounded-[20px] p-8 text-white sm:rounded-[28px] md:p-12">
            <div class="relative z-10 mx-auto max-w-2xl text-center">
                <span class="page-kicker !bg-white/10 !text-mint">📬 Never miss a guide</span>
                <h2 class="res-newsletter-title mt-4">New guides, straight to your inbox</h2>
                <p class="mx-auto mt-3 max-w-lg text-sm text-white/70 sm:text-base">
                    One short email a week — career moves, hiring trends, and platform tips. No spam, low-data friendly.
                </p>

                <form class="mt-6 flex flex-col gap-3 sm:flex-row"
                      x-show="!subscribed"
                      @submit.prevent="subscribed = true">
                    <label for="res-newsletter-email" class="sr-only">Email address</label>
                    <input id="res-newsletter-email" type="email" required
                           placeholder="Your email address" class="res-newsletter-input">
                    <button type="submit" class="res-newsletter-submit">Subscribe ✦</button>
                </form>

                <p x-cloak x-show="subscribed" class="mx-auto mt-6 max-w-md rounded-2xl border border-mint/30 bg-white/10 px-4 py-3 text-sm font-medium text-white">
                    📬 You're in! Watch your inbox for the next guide.
                </p>
            </div>
            <div class="about-quote-grain pointer-events-none absolute inset-0 opacity-40" aria-hidden="true"></div>
        </div>
    </section>

</div>
@endsection
