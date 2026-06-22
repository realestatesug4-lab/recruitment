@extends('layouts.app')

@section('title', 'Employer Jobs - JobsUG')

@section('content')
<div class="page-wrap py-10">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-syne text-4xl font-bold text-deep">Jobs</h1>
            <p class="mt-2 text-text-mid">Manage roles connected to your employer profile.</p>
        </div>
        <a href="{{ route('employer.dashboard') }}" class="rounded-full bg-white/70 px-5 py-3 text-sm font-semibold text-forest transition hover:bg-white">Back to dashboard</a>
    </div>

    <div class="glass overflow-hidden rounded-lg">
        <div class="divide-y divide-white/70">
            @forelse($jobs as $job)
                <div class="flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="font-semibold text-deep">{{ $job->title }}</h2>
                        <p class="mt-1 text-sm text-text-light">{{ $job->location ?? 'Uganda' }} &middot; {{ ucfirst($job->status->value) }} &middot; {{ $job->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('jobs.show', $job->slug) }}" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-forest">Public view</a>
                        <a href="{{ route('employer.jobs.edit', $job->slug) }}" class="rounded-full bg-forest px-4 py-2 text-sm font-semibold text-white">Edit</a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <h2 class="font-syne text-2xl font-bold text-deep">No jobs yet</h2>
                    <p class="mt-2 text-text-mid">Create your first role to start receiving applications.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8">{{ $jobs->links() }}</div>
</div>
@endsection
