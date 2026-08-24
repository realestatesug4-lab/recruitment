@extends('layouts.dashboard')

@section('title', 'Dashboard — CraneLinks')
@section('page_title', 'Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- ── Welcome banner ── --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-forest via-sage to-forest p-8 text-white">
        <div class="relative z-10">
            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Welcome back, {{ $viewModel->user->name }}</h1>
            <p class="mt-2 max-w-xl text-sm text-white/80">
                @if($viewModel->hasProfile())
                    Your profile is {{ $viewModel->profileCompletion() }}% complete. {{ $viewModel->applications->count() > 0 ? 'You have ' . $viewModel->applications->count() . ' active application(s).' : 'Start applying to roles that match your skills.' }}
                @else
                    Complete your profile to unlock smarter job matching and one-click applications.
                @endif
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2.5 text-sm font-semibold text-forest shadow-sm transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    Browse jobs
                </a>
                @if(!$viewModel->hasProfile())
                    <a href="{{ route('seeker.profile.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-white/20 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/30">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Create profile
                    </a>
                @endif
            </div>
        </div>
        <div class="absolute -right-6 -top-6 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-10 right-20 h-32 w-32 rounded-full bg-white/5 blur-xl"></div>
    </div>

    {{-- ── Stats grid ── --}}
    <div class="grid gap-4 sm:grid-cols-3">
        @foreach($viewModel->stats() as $stat)
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $stat['label'] }}</p>
                        <p class="mt-1.5 text-3xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                    </div>
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl
                        {{ match($stat['color']) {
                            'blue'    => 'bg-blue-50 text-blue-600',
                            'amber'   => 'bg-amber-50 text-amber-600',
                            'emerald' => 'bg-emerald-50 text-emerald-600',
                            default   => 'bg-gray-50 text-gray-600',
                        } }}">
                        @if($stat['icon'] === 'document')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        @elseif($stat['icon'] === 'bookmark')
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" /></svg>
                        @else
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        @endif
                    </div>
                </div>
                <p class="mt-3 text-xs text-gray-500">{{ $stat['hint'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-8 lg:grid-cols-3">

        {{-- ── Main column ── --}}
        <div class="space-y-8 lg:col-span-2">

            {{-- Recent applications --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recent applications</h2>
                    @if($viewModel->applications->count() > 0)
                        <a href="{{ route('seeker.applications.progress') }}" class="text-sm font-medium text-forest hover:text-sage">View all &rarr;</a>
                    @endif
                </div>

                @if($viewModel->recentApplications()->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        <p class="mt-4 text-sm font-medium text-gray-900">No applications yet</p>
                        <p class="mt-1 text-sm text-gray-500">Start applying to roles that match your skills.</p>
                        <a href="{{ route('jobs.index') }}" class="mt-4 inline-flex rounded-lg bg-forest px-4 py-2 text-sm font-semibold text-white hover:bg-sage">Browse jobs</a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($viewModel->recentApplications() as $app)
                            <a href="{{ $app['url'] }}" class="flex items-center justify-between px-6 py-4 transition hover:bg-gray-50">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-gray-900">{{ $app['title'] }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $app['company'] }} &middot; {{ $app['when'] }}</p>
                                </div>
                                <div class="ml-4 flex items-center gap-3">
                                    @if($app['score'])
                                        <span class="hidden sm:inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">{{ $app['score'] }}%</span>
                                    @endif
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $viewModel->statusLabelClass($app['status']) }}">{{ $app['status'] }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Recommended jobs --}}
            @if($viewModel->recommendedJobs->isNotEmpty())
                <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Recommended for you</h2>
                            <p class="text-xs text-gray-500">Based on your profile skills</p>
                        </div>
                        <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-forest hover:text-sage">See all &rarr;</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($viewModel->recommendedJobs as $job)
                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('jobs.show', $job->slug) }}" class="text-sm font-semibold text-gray-900 hover:text-forest transition">{{ $job->title }}</a>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $job->company->name ?? 'Company' }} &middot; {{ $job->location ?? 'Remote' }}</p>
                                    @if($job->skills->isNotEmpty())
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach($job->skills->take(3) as $skill)
                                                <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-600">{{ $skill->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex items-center gap-2">
                                    <form method="POST" action="{{ route('seeker.saved-jobs.toggle', $job->slug) }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-amber-500 transition" title="Save job">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" /></svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('seeker.applications.create', $job->slug) }}" class="rounded-lg bg-forest px-3 py-1.5 text-xs font-semibold text-white hover:bg-sage transition">Apply</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Sidebar ── --}}
        <div class="space-y-6">

            {{-- Profile completion --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Profile strength</h3>
                    <span class="rounded-full bg-forest/10 px-2.5 py-0.5 text-xs font-bold text-forest">{{ $viewModel->profileCompletion() }}%</span>
                </div>
                <div class="mt-3 h-2 rounded-full bg-gray-100">
                    <div class="h-2 rounded-full bg-gradient-to-r from-forest to-sage transition-all duration-500" style="width: {{ $viewModel->profileCompletion() }}%"></div>
                </div>

                @if($viewModel->hasProfile())
                    <dl class="mt-4 space-y-2.5 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 {{ $viewModel->profile->headline ? 'text-emerald-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="{{ $viewModel->profile->headline ? 'text-gray-700' : 'text-gray-400' }}">Headline</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 {{ $viewModel->profile->bio ? 'text-emerald-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="{{ $viewModel->profile->bio ? 'text-gray-700' : 'text-gray-400' }}">Summary</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 {{ $viewModel->profile->location ? 'text-emerald-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="{{ $viewModel->profile->location ? 'text-gray-700' : 'text-gray-400' }}">Location</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 {{ $viewModel->profile->resume_url ? 'text-emerald-500' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span class="{{ $viewModel->profile->resume_url ? 'text-gray-700' : 'text-gray-400' }}">CV uploaded</span>
                        </div>
                    </dl>
                    <a href="{{ route('seeker.profile.edit') }}" class="mt-4 block w-full rounded-lg bg-gray-50 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">Edit profile</a>
                @else
                    <div class="mt-4 rounded-lg bg-amber-50 p-4 text-center">
                        <p class="text-sm font-medium text-amber-800">Your profile is empty</p>
                        <p class="mt-1 text-xs text-amber-600">Employers need context to evaluate your fit.</p>
                        <a href="{{ route('seeker.profile.create') }}" class="mt-3 block w-full rounded-lg bg-forest py-2.5 text-center text-sm font-semibold text-white hover:bg-sage transition">Create profile</a>
                    </div>
                @endif
            </div>

            {{-- Quick actions --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Quick actions</h3>
                <div class="mt-3 space-y-2">
                    <a href="{{ route('jobs.index') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Browse jobs
                    </a>
                    <a href="{{ route('seeker.applications.progress') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        My applications
                    </a>
                    <a href="{{ route('seeker.saved-jobs') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" /></svg>
                        Saved jobs
                    </a>
                    <a href="{{ route('seeker.ai-tools') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                        AI Career Tools
                    </a>
                </div>
            </div>

            {{-- Saved jobs preview --}}
            @if($viewModel->savedJobs->count() > 0)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Saved jobs</h3>
                        <a href="{{ route('seeker.saved-jobs') }}" class="text-xs font-medium text-forest hover:text-sage">View all</a>
                    </div>
                    <div class="mt-3 space-y-2.5">
                        @foreach($viewModel->savedJobs->take(3) as $saved)
                            <a href="{{ route('jobs.show', $saved->job->slug) }}" class="block rounded-lg px-3 py-2 transition hover:bg-gray-50">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $saved->job->title }}</p>
                                <p class="text-xs text-gray-500">{{ $saved->job->company->name ?? 'Company' }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
