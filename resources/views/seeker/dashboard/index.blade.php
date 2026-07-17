@extends('layouts.app')

@section('title', 'Job Seeker Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-forest/5 via-white to-mint/5">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="mb-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full bg-mint/10 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-sage">Seeker dashboard</p>
                <h1 class="mt-4 text-4xl font-syne font-bold text-deep">Welcome back, {{ $viewModel->user->name }}</h1>
                <p class="mt-2 text-lg text-text-mid">Your personalized job search hub with recommendations, progress, and saved roles.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('jobs.index') }}" class="rounded-full bg-white/90 px-5 py-3 text-sm font-semibold text-forest shadow-sm shadow-forest/5 transition hover:bg-white">Browse jobs</a>
                <a href="{{ route('seeker.applications.progress') }}" class="rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white transition hover:bg-sage">Application progress</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                    @csrf
                    <button type="submit" class="rounded-full border border-gray-200 bg-white/80 px-5 py-3 text-sm font-semibold text-text-mid transition hover:bg-white">Logout</button>
                </form>
            </div>
        </div>

        <section class="grid gap-6 md:grid-cols-3 mb-10">
            @foreach($viewModel->stats() as $stat)
                <article class="glass rounded-3xl border border-mint/10 p-6 shadow-sm shadow-forest/5">
                    <div class="flex items-center justify-between gap-4">
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-wide text-text-light">{{ $stat['label'] }}</p>
                            <p class="text-4xl font-syne font-bold text-deep">{{ number_format((float) ($stat['value'] ?? 0)) }}</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-3xl bg-forest/10 text-forest text-xl font-bold">{{ str($stat['label'])->substr(0,1) }}</div>
                    </div>
                    <p class="mt-4 text-sm text-sage">{{ $stat['hint'] }}</p>
                </article>
            @endforeach
        </section>

        <div class="grid gap-8 lg:grid-cols-[1.7fr_1fr]">
            <section class="glass rounded-3xl border border-mint/10 p-8 shadow-sm shadow-forest/5">
                <div class="flex items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-syne font-bold text-deep">Recent applications</h2>
                        <p class="mt-1 text-sm text-text-mid">Track your latest submissions and follow ups.</p>
                    </div>
                    <a href="{{ route('seeker.applications.progress') }}" class="text-sm font-semibold text-forest hover:text-forest/80">See all</a>
                </div>

                @if($viewModel->recentApplications()->isEmpty())
                    <div class="rounded-3xl border border-dashed border-mint/20 bg-white/70 p-12 text-center text-text-mid">
                        <p class="font-semibold text-deep">No applications yet</p>
                        <p class="mt-3">Start by browsing jobs and submitting your first application.</p>
                        <a href="{{ route('jobs.index') }}" class="mt-5 inline-flex rounded-full bg-forest px-5 py-3 text-sm font-semibold text-white transition hover:bg-sage">Browse jobs</a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($viewModel->recentApplications() as $application)
                            <article class="rounded-3xl border border-mint/10 bg-white/80 p-5 shadow-sm shadow-forest/5 transition hover:-translate-y-0.5 hover:shadow-md">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-lg font-semibold text-deep">{{ $application['title'] }}</p>
                                        <p class="mt-1 text-sm text-text-mid">{{ $application['company'] }}</p>
                                    </div>
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $viewModel->statusLabelClass($application['status']) }}">{{ $application['status'] }}</span>
                                </div>
                                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-sm text-text-light">Applied {{ $application['when'] }}</p>
                                    <a href="{{ $application['url'] }}" class="inline-flex items-center gap-2 rounded-full bg-forest px-4 py-2 text-sm font-semibold text-white transition hover:bg-sage">Review job</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            <aside class="space-y-6">
                <section class="glass rounded-3xl border border-mint/10 p-6 shadow-sm shadow-forest/5">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="text-xl font-syne font-bold text-deep">Profile health</h3>
                            <p class="mt-1 text-sm text-text-mid">Complete your profile to improve matching and visibility.</p>
                        </div>
                        <span class="rounded-full bg-mint/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-sage">{{ $viewModel->profileCompletion() }}%</span>
                    </div>

                    @if($viewModel->hasProfile())
                        <dl class="space-y-4 text-sm text-text-mid">
                            <div>
                                <dt class="font-semibold text-deep">Headline</dt>
                                <dd class="mt-1">{{ $viewModel->profile->headline ?? 'Not set yet' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-deep">Location</dt>
                                <dd class="mt-1">{{ $viewModel->profile->location ?? 'Not set yet' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold text-deep">Experience level</dt>
                                <dd class="mt-1">{{ $viewModel->profile->experience_level ?? 'Not set yet' }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('seeker.profile.edit') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-forest px-4 py-3 text-sm font-semibold text-white transition hover:bg-sage">Edit profile</a>
                    @else
                        <div class="rounded-3xl border border-mint/20 bg-mint/5 p-6 text-center">
                            <p class="font-semibold text-deep">Start your professional story</p>
                            <p class="mt-2 text-sm text-text-mid">Complete your profile so employers can find you faster.</p>
                            <a href="{{ route('seeker.profile.create') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-forest px-4 py-3 text-sm font-semibold text-white transition hover:bg-sage">Complete profile</a>
                        </div>
                    @endif
                </section>

                <section class="glass rounded-3xl border border-mint/10 p-6 shadow-sm shadow-forest/5">
                    <h3 class="text-xl font-syne font-bold text-deep">Quick actions</h3>
                    <div class="mt-5 space-y-3">
                        @foreach($viewModel->quickActions() as $action)
                            <a href="{{ $action['url'] }}" class="flex items-center justify-between rounded-3xl border border-gray-200 bg-white/90 px-4 py-4 text-sm font-semibold text-deep transition hover:bg-gray-50">
                                <span>{{ $action['label'] }}</span>
                                <span class="text-forest">{{ $action['icon'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            </aside>
        </div>

        @if($viewModel->savedJobs->count() > 0)
            <section class="mt-10">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-syne font-bold text-deep">Saved jobs</h2>
                        <p class="mt-1 text-sm text-text-mid">Good matches you saved for later.</p>
                    </div>
                    <a href="{{ route('jobs.index') }}" class="text-sm font-semibold text-forest hover:text-forest/80">Browse more</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($viewModel->savedJobs as $saved)
                        <x-job-card :job="$saved->job" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
