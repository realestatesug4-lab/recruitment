@extends('layouts.app', ['page' => 'home'])

@section('title', 'CraneLinks - One Click Away')

@section('content')
<div class="page-wrap">
    @include('partials.hero', [
        'hero' => $viewModel->hero(),
        'stats' => $viewModel->platformStats(),
        'popularSearches' => $viewModel->popularSearches(),
    ])

    <section class="quick-strip fade-section mx-auto max-w-7xl px-4 sm:px-6 py-6 sm:py-8">
        <div class="overflow-hidden rounded-2xl border border-forest/10 bg-white/55 px-5 py-4 shadow-sm backdrop-blur">
            <div class="strip-track flex w-max items-center gap-8 text-xs font-semibold uppercase tracking-[0.18em] text-text-mid sm:text-sm">
                <span><b class="text-forest">12,400+</b> live listings</span>
                <span><b class="text-forest">340+</b> verified employers</span>
                <span><b class="text-forest">0.4s</b> average resolve</span>
                <span><b class="text-forest">Low-data</b> by default</span>
                {{-- <span><b class="text-forest">SMS/USSD</b> ready</span> --}}
                <span><b class="text-forest">12,400+</b> live listings</span>
                <span><b class="text-forest">340+</b> verified employers</span>
                <span><b class="text-forest">0.4s</b> average resolve</span>
            </div>
        </div>
    </section>

    @include('partials.bento', [
        'jobs' => $viewModel->latestJobs(),
        'featuredCompanies' => $viewModel->featuredCompanies(),
        'categories' => $viewModel->categories(),
    ])

    @include('partials.how-it-works')

    {{-- <section class="fade-section mx-auto max-w-7xl px-4 sm:px-6 py-8 sm:py-10">
        <div class="sponsored-panel rounded-2xl border border-amber/35 bg-white/70 p-6 shadow-xl shadow-forest/5 backdrop-blur sm:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="max-w-3xl">
                    <p class="mb-3 text-xs font-semibold uppercase tracking-[0.28em] text-amber">Sponsored, clearly marked</p>
                    <h2 class="text-2xl font-semibold tracking-tight text-deep sm:text-3xl">Old Mutual Unit Trust Fund</h2>
                    <p class="mt-3 text-base leading-7 text-text-mid">Invest for future financial security with Old Mutual's trusted unit trust fund. This placement stays outside the apply flow and is labeled before you click.</p>
                </div>
                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                    <a href="https://oldmutual.co.ug" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full bg-forest px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-forest/20 transition hover:bg-sage">Learn more</a>
                    <a href="https://oldmutual.co.ug/contact" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border border-forest/15 bg-white px-6 py-3 text-sm font-semibold text-deep transition hover:bg-cream">Contact advisor</a>
                </div>
            </div>

            <div class="mt-8">
                <x-smart-ad-component slug="old-mutual-unit-trust-fund" />
            </div>
        </div>
    </section> --}}

    <x-bento.stats-card />

    @include('partials.ai-career-tools')

    @include('partials.testimonials')

    {{-- @include('partials.companies-strip', [
        'companies' => $viewModel->trustedCompanies(),
    ]) --}}

</div>
@endsection
