@extends('layouts.dashboard')

@section('title', 'Employer Dashboard — CraneLinks')
@section('page_title', 'Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- Welcome banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 p-8 text-white">
        <div class="relative z-10">
            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">{{ $viewModel->company->name }}</h1>
            <p class="mt-2 text-sm text-gray-300">
                {{ $viewModel->openJobs }} open role(s) &middot; {{ $viewModel->totalApplications }} total applications
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('employer.jobs.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm transition hover:bg-gray-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Post a job
                </a>
                <a href="{{ route('employer.ats') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/15 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/25">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                    ATS Pipeline
                </a>
            </div>
        </div>
        <div class="absolute -right-8 -top-8 h-48 w-48 rounded-full bg-white/5 blur-2xl"></div>
    </div>

    @if($viewModel->company->verification_status !== 'verified')
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
            <div>
                <p class="text-sm font-semibold text-amber-800">Company verification: {{ str($viewModel->company->verification_status)->replace('-', ' ')->title() }}</p>
                <p class="mt-0.5 text-xs text-amber-600">Publishing is available once your company profile has been reviewed.</p>
            </div>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach($viewModel->stats() as $stat)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $stat['label'] }}</p>
                <p class="mt-1.5 text-3xl font-bold text-gray-900">{{ number_format($stat['value']) }}</p>
                <p class="mt-2 text-xs text-gray-500">{{ $stat['hint'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-8 lg:grid-cols-3">
        {{-- Recent applications --}}
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent applications</h2>
                <a href="{{ route('employer.applications.index') }}" class="text-sm font-medium text-forest hover:text-sage">View all &rarr;</a>
            </div>

            @if($viewModel->recentApplicationCards()->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-sm text-gray-500">No applications yet. Post a job to start receiving candidates.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($viewModel->recentApplicationCards() as $app)
                        <div class="flex items-center justify-between px-6 py-4">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-gray-900">{{ $app['candidate'] }}</p>
                                <p class="mt-0.5 text-xs text-gray-500">{{ $app['job'] }} &middot; {{ $app['applied_at'] }}</p>
                            </div>
                            <div class="ml-4 flex items-center gap-2">
                                @if($app['score'])
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">{{ $app['score'] }}%</span>
                                @endif
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">{{ $app['status'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Quick actions sidebar --}}
        <div class="space-y-4">
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Quick actions</h3>
                <div class="mt-3 space-y-2">
                    <a href="{{ route('employer.jobs.create') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Post a job
                    </a>
                    <a href="{{ route('employer.ats') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7" /></svg>
                        ATS Pipeline
                    </a>
                    <a href="{{ route('employer.jobs.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        Manage jobs
                    </a>
                    <a href="{{ route('employer.company.edit') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        Company profile
                    </a>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Company</h3>
                <div class="mt-3 flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg font-bold text-white" style="background: {{ $viewModel->company->color ?? '#1B4332' }}">
                        {{ strtoupper(substr($viewModel->company->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-gray-900">{{ $viewModel->company->name }}</p>
                        <p class="text-xs text-gray-500">{{ $viewModel->company->location ?? 'Uganda' }}</p>
                    </div>
                </div>
                <a href="{{ route('companies.show', $viewModel->company->slug) }}" class="mt-4 block text-center rounded-lg bg-gray-50 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">View public profile</a>
            </div>
        </div>
    </div>
</div>
@endsection
