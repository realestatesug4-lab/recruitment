@extends('layouts.app')

@section('title', 'Companies - JobsUG')

@section('content')
<div class="page-wrap py-12">
    <section class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full bg-mint/10 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-sage">
                <span class="h-2 w-2 rounded-full bg-mint"></span>
                Employer directory
            </div>
            <h1 class="mt-5 max-w-3xl font-syne text-4xl font-bold text-deep md:text-5xl">
                Discover companies hiring across Uganda.
            </h1>
            <p class="mt-4 max-w-2xl text-base leading-7 text-text-mid">
                Browse verified employers, open roles, industries, and locations from the JobsUG database.
            </p>
        </div>

        <div class="grid grid-cols-3 gap-3">
            <div class="glass rounded-lg p-4">
                <div class="font-syne text-2xl font-bold text-deep">{{ number_format($viewModel->totalCompanies()) }}</div>
                <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Companies</div>
            </div>
            <div class="glass rounded-lg p-4">
                <div class="font-syne text-2xl font-bold text-deep">{{ number_format($viewModel->openRoles()) }}</div>
                <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Open roles</div>
            </div>
            <div class="glass rounded-lg p-4">
                <div class="font-syne text-2xl font-bold text-deep">{{ number_format($viewModel->industries()) }}</div>
                <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Industries</div>
            </div>
        </div>
    </section>

    <section class="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($viewModel->cards() as $company)
            <a href="{{ route('companies.show', $company['slug']) }}" class="glass group rounded-lg p-5 transition duration-200 hover:-translate-y-1 hover:shadow-xl">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg font-syne text-xl font-bold text-white" style="background: {{ $company['color'] }}">
                        {{ $company['initial'] }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="truncate font-syne text-lg font-bold text-deep">{{ $company['name'] }}</h2>
                            @if($company['is_verified'])
                                <span class="rounded-full bg-mint/10 px-2 py-0.5 text-[11px] font-semibold text-sage">Verified</span>
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-text-light">{{ $company['industry'] }} &middot; {{ $company['location'] }}</p>
                    </div>
                </div>

                <p class="mt-4 min-h-12 text-sm leading-6 text-text-mid">{{ $company['description'] }}</p>

                <div class="mt-5 flex items-center justify-between border-t border-white/70 pt-4">
                    <span class="text-sm font-semibold text-forest">{{ $company['open_roles'] }} open roles</span>
                    <span class="text-sm font-semibold text-sage transition group-hover:translate-x-1">View profile &rarr;</span>
                </div>
            </a>
        @empty
            <div class="glass rounded-lg p-8 text-center md:col-span-2 xl:col-span-3">
                <h2 class="font-syne text-2xl font-bold text-deep">No companies yet</h2>
                <p class="mt-2 text-text-mid">Seed or create companies to populate this directory.</p>
            </div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $paginator->links() }}
    </div>
</div>
@endsection
