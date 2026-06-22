@extends('layouts.app')

@section('title', $viewModel->name() . ' - JobsUG')

@section('content')
@php($company = $viewModel->summary())

<div class="page-wrap py-12">
    <section class="glass overflow-hidden rounded-lg">
        <div class="grid gap-8 p-6 md:p-8 lg:grid-cols-[0.9fr_1.1fr] lg:p-10">
            <div>
                <div class="flex h-24 w-24 items-center justify-center rounded-lg font-syne text-4xl font-bold text-white" style="background: {{ $company['color'] }}">
                    {{ $company['initial'] }}
                </div>
                <div class="mt-6 flex flex-wrap gap-2">
                    <span class="badge-green rounded-full px-3 py-1 text-xs font-semibold">{{ $company['verification_status'] }}</span>
                    <span class="rounded-full bg-white/70 px-3 py-1 text-xs font-semibold text-text-mid">{{ $company['industry'] }}</span>
                    <span class="rounded-full bg-white/70 px-3 py-1 text-xs font-semibold text-text-mid">{{ $company['location'] }}</span>
                </div>
            </div>

            <div>
                <h1 class="font-syne text-4xl font-bold text-deep md:text-5xl">{{ $company['name'] }}</h1>
                <p class="mt-4 max-w-3xl text-base leading-7 text-text-mid">{{ $company['description'] }}</p>

                <div class="mt-8 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-lg bg-white/60 p-4">
                        <div class="font-syne text-2xl font-bold text-deep">{{ $company['open_roles'] }}</div>
                        <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Open roles</div>
                    </div>
                    <div class="rounded-lg bg-white/60 p-4">
                        <div class="font-syne text-lg font-bold text-deep">{{ $company['size'] }}</div>
                        <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Team size</div>
                    </div>
                    <div class="rounded-lg bg-white/60 p-4">
                        <div class="font-syne text-lg font-bold text-deep">{{ $company['location'] }}</div>
                        <div class="mt-1 text-xs font-semibold uppercase tracking-wide text-text-light">Location</div>
                    </div>
                </div>

                @if($company['website'])
                    <a href="{{ $company['website'] }}" target="_blank" rel="noreferrer" class="mt-6 inline-flex rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white transition hover:bg-sage">
                        Visit website
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="mt-10">
        <div class="mb-5 flex items-end justify-between gap-4">
            <div>
                <h2 class="font-syne text-2xl font-bold text-deep">Open roles</h2>
                <p class="mt-1 text-sm text-text-mid">Latest published jobs from {{ $company['name'] }}.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="hidden text-sm font-semibold text-sage hover:text-forest sm:inline">Browse all jobs &rarr;</a>
        </div>

        <div class="grid gap-3">
            @forelse($viewModel->openRoles() as $job)
                <a href="{{ route('jobs.show', $job['slug']) }}" class="glass flex flex-col gap-3 rounded-lg p-5 transition hover:-translate-y-0.5 hover:shadow-lg sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-semibold text-deep">{{ $job['title'] }}</h3>
                        <p class="mt-1 text-sm text-text-light">{{ $job['location'] }} &middot; {{ $job['posted_at'] }}</p>
                    </div>
                    <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $job['type'] === 'Contract' ? 'badge-amber' : ($job['type'] === 'Remote' ? 'badge-blue' : 'badge-green') }}">
                        {{ $job['type'] }}
                    </span>
                </a>
            @empty
                <div class="glass rounded-lg p-8 text-center">
                    <h3 class="font-syne text-xl font-bold text-deep">No published roles</h3>
                    <p class="mt-2 text-text-mid">This company does not have open jobs right now.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
