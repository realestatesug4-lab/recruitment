@extends('layouts.app')

@section('title', 'Employer Dashboard - JobsUG')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-forest/5 via-white to-mint/5 py-10">
    <section class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <div class="inline-flex items-center gap-2 rounded-full bg-mint/10 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-sage">
                Employer portal
            </div>
            <h1 class="mt-4 font-syne text-4xl font-bold text-deep">Hiring dashboard</h1>
            <p class="mt-2 text-text-mid">{{ $viewModel->company->name }} &middot; {{ $viewModel->company->industry ?? 'Employer workspace' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('employer.jobs.index') }}" class="rounded-full bg-white/70 px-5 py-3 text-sm font-semibold text-forest transition hover:bg-white">Manage jobs</a>
            <a href="{{ route('employer.applications.index') }}" class="rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white transition hover:bg-sage">Review applications</a>
        </div>
    </section>

    @if($viewModel->company->verification_status !== 'verified')
        <section class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="font-semibold text-amber-900">Company verification is {{ str($viewModel->company->verification_status)->replace('-', ' ') }}</div>
                    <p class="mt-1 text-sm text-amber-800">You can prepare draft jobs now. Publishing should wait until the company profile has been reviewed.</p>
                </div>
                <span class="inline-flex w-fit rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-700">
                    Trust review
                </span>
            </div>
        </section>
    @endif

    <section class="mt-8 grid gap-4 md:grid-cols-3">
        @foreach($viewModel->stats() as $stat)
            <div class="glass rounded-lg p-6">
                <div class="text-sm font-semibold uppercase tracking-wide text-text-light">{{ $stat['label'] }}</div>
                <div class="mt-3 font-syne text-4xl font-bold text-deep">{{ number_format($stat['value']) }}</div>
                <div class="mt-2 text-sm text-sage">{{ $stat['hint'] }}</div>
            </div>
        @endforeach
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-[1.7fr_1fr]">
        <div class="glass rounded-lg p-6">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="font-syne text-2xl font-bold text-deep">Recent applications</h2>
                <a href="{{ route('employer.applications.index') }}" class="text-sm font-semibold text-sage hover:text-forest">View all</a>
            </div>

            <div class="space-y-3">
                @forelse($viewModel->recentApplicationCards() as $application)
                    <div class="rounded-lg bg-white/60 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="font-semibold text-deep">{{ $application['candidate'] }}</div>
                                <div class="mt-1 text-sm text-text-light">{{ $application['job'] }} &middot; {{ $application['applied_at'] }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($application['score'])
                                    <span class="rounded-full bg-mint/10 px-3 py-1 text-xs font-semibold text-sage">{{ $application['score'] }}% match</span>
                                @endif
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-text-mid">{{ $application['status'] }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg bg-white/60 p-8 text-center text-text-mid">No applications yet.</div>
                @endforelse
            </div>
        </div>

        <aside class="glass rounded-lg p-6">
            <h2 class="font-syne text-xl font-bold text-deep">Company profile</h2>
            <div class="mt-5 flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-lg font-syne text-xl font-bold text-white" style="background: {{ $viewModel->company->color ?? '#1B4332' }}">
                    {{ strtoupper(substr($viewModel->company->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-deep">{{ $viewModel->company->name }}</div>
                    <div class="text-sm text-text-light">{{ $viewModel->company->location ?? 'Uganda' }}</div>
                </div>
            </div>
            <p class="mt-5 text-sm leading-6 text-text-mid">{{ $viewModel->company->description ?? 'Keep your company profile fresh so candidates understand your team.' }}</p>
            <a href="{{ route('companies.show', $viewModel->company->slug) }}" class="mt-5 inline-flex text-sm font-semibold text-sage hover:text-forest">View public profile &rarr;</a>
        </aside>
    </section>
</div>
@endsection
