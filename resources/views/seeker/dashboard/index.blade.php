@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-forest/5 via-white to-mint/5">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-12">
            <h1 class="text-4xl font-syne font-bold text-deep mb-2">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-text-light text-lg">Your job search dashboard</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid gap-6 md:grid-cols-3 mb-12">
            <div class="glass rounded-2xl p-6 border border-mint/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-light font-medium uppercase tracking-wide">Applications</p>
                        <p class="text-3xl font-syne font-bold text-deep mt-2">{{ $applicationStats['total'] ?? 0 }}</p>
                        <p class="text-xs text-sage mt-2">{{ $applicationStats['shortlisted'] ?? 0 }} shortlisted</p>
                    </div>
                    <svg class="w-12 h-12 text-mint/20" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                </div>
            </div>

            <div class="glass rounded-2xl p-6 border border-forest/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-light font-medium uppercase tracking-wide">Saved Jobs</p>
                        <p class="text-3xl font-syne font-bold text-deep mt-2">{{ $savedJobs->total() }}</p>
                        <p class="text-xs text-forest mt-2">Bookmarked for later</p>
                    </div>
                    <svg class="w-12 h-12 text-forest/20" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                </div>
            </div>

            <div class="glass rounded-2xl p-6 border border-sage/10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-text-light font-medium uppercase tracking-wide">Profile</p>
                        <p class="text-3xl font-syne font-bold text-deep mt-2">{{ $profile ? '100%' : '0%' }}</p>
                        <p class="text-xs text-sage mt-2">
                            @if($profile)
                                Complete
                            @else
                                <a href="{{ route('seeker.profile.create') }}" class="text-forest hover:underline">Complete profile</a>
                            @endif
                        </p>
                    </div>
                    <svg class="w-12 h-12 text-sage/20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                </div>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Left Column --}}
            <div class="lg:col-span-2">
                {{-- Recent Applications --}}
                <div class="glass rounded-3xl p-8 border border-gray-100 mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-syne font-bold text-deep">Recent Applications</h2>
                        <a href="{{ route('seeker.applications.progress') }}" class="text-forest hover:text-forest/80 text-sm font-semibold">View all</a>
                    </div>

                    @if($applications->isEmpty())
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/></svg>
                            <p class="text-text-light">No applications yet</p>
                            <a href="{{ route('jobs.index') }}" class="text-forest hover:text-forest/80 font-semibold mt-2 inline-block">Browse jobs</a>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($applications->take(5) as $application)
                                <div class="flex items-start justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition">
                                    <div class="flex-1">
                                        <p class="font-semibold text-deep">{{ $application->job->title }}</p>
                                        <p class="text-sm text-text-light mt-1">{{ $application->job->company->name }}</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                                @if($application->status === 'submitted') bg-blue-100 text-blue-700
                                                @elseif($application->status === 'shortlisted') bg-green-100 text-green-700
                                                @elseif($application->status === 'interview') bg-purple-100 text-purple-700
                                                @elseif($application->status === 'hired') bg-emerald-100 text-emerald-700
                                                @elseif($application->status === 'rejected') bg-red-100 text-red-700
                                                @else bg-gray-100 text-gray-700
                                                @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                            <span class="text-xs text-text-light">{{ $application->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('jobs.show', $application->job->slug) }}" class="text-forest hover:text-forest/80 font-semibold">View →</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right Column --}}
            <div>
                {{-- Profile Quick View --}}
                @if($profile)
                    <div class="glass rounded-3xl p-8 border border-gray-100 mb-8">
                        <h3 class="text-lg font-syne font-bold text-deep mb-4">Profile</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-text-light uppercase font-semibold">Headline</p>
                                <p class="font-semibold text-deep">{{ $profile->headline ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-light uppercase font-semibold">Experience</p>
                                <p class="font-semibold text-deep">{{ $profile->experience_level ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-text-light uppercase font-semibold">Location</p>
                                <p class="font-semibold text-deep">{{ $profile->location ?? '—' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('seeker.profile.edit') }}" class="mt-4 w-full block text-center bg-forest text-white py-2 rounded-lg hover:bg-forest/90 transition font-semibold text-sm">
                            Edit profile
                        </a>
                    </div>
                @else
                    <div class="glass rounded-3xl p-8 border border-mint/20 bg-mint/5">
                        <h3 class="text-lg font-syne font-bold text-deep mb-2">Complete Your Profile</h3>
                        <p class="text-sm text-text-light mb-4">Set up your profile to unlock better job recommendations and get discovered by employers.</p>
                        <a href="{{ route('seeker.profile.create') }}" class="w-full block text-center bg-forest text-white py-2 rounded-lg hover:bg-forest/90 transition font-semibold text-sm">
                            Get started
                        </a>
                    </div>
                @endif

                {{-- Quick Actions --}}
                <div class="space-y-3">
                    <a href="{{ route('jobs.index') }}" class="flex items-center justify-between p-4 rounded-xl bg-white hover:bg-gray-50 transition border border-gray-200">
                        <span class="font-semibold text-deep">Browse jobs</span>
                        <svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('seeker.applications.progress') }}" class="flex items-center justify-between p-4 rounded-xl bg-white hover:bg-gray-50 transition border border-gray-200">
                        <span class="font-semibold text-deep">All applications</span>
                        <svg class="w-5 h-5 text-forest" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Saved Jobs Section --}}
        @if($savedJobs->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-syne font-bold text-deep mb-6">Saved Jobs</h2>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($savedJobs as $saved)
                    <x-job-card :job="$saved->job" />
                @endforeach
            </div>

            @if($savedJobs->hasMorePages())
            <div class="mt-8 text-center">
                <a href="#" class="text-forest hover:text-forest/80 font-semibold">View all saved jobs →</a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
