@extends('layouts.app', ['page' => 'home'])

@section('title', 'JobsUG - Uganda\'s Job Marketplace')

@section('content')
<div class="page-wrap">
    @include('partials.hero', [
        'hero' => $viewModel->hero(),
        'stats' => $viewModel->platformStats(),
        'popularSearches' => $viewModel->popularSearches(),
    ])

    <section class="fade-section mx-auto max-w-7xl px-4 sm:px-6 py-8 sm:py-10">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-900/5">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.32em] text-sage mb-3">Sponsored</p>
                    <h2 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">Old Mutual Unit Trust Fund</h2>
                    <p class="mt-3 text-base leading-7 text-slate-600">Invest for future financial security with Old Mutual’s trusted unit trust fund. Designed for long-term growth, it helps older Ugandans preserve capital while generating steady returns.</p>
                </div>
                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                    <a href="https://oldmutual.co.ug" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full bg-sage px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-sage/20 transition hover:bg-sage-dark">Learn more</a>
                    <a href="https://oldmutual.co.ug/contact" target="_blank" rel="noopener" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-50">Contact advisor</a>
                </div>
            </div>

            <div class="mt-8">
                <x-smart-ad-component slug="old-mutual-unit-trust-fund" />
            </div>
        </div>
    </section>

    @include('partials.bento', [
        'jobs' => $viewModel->latestJobs(),
        'featuredCompanies' => $viewModel->featuredCompanies(),
        'categories' => $viewModel->categories(),
    ])

    @include('partials.how-it-works')

    <x-bento.stats-card />

    @include('partials.ai-career-tools')

    @include('partials.testimonials')

    @include('partials.companies-strip', [
        'companies' => $viewModel->trustedCompanies(),
    ])

</div>
@endsection
