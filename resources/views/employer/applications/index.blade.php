@extends('layouts.app')

@section('title', 'Employer Applications - JobsUG')

@section('content')
<div class="page-wrap py-10">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="font-syne text-4xl font-bold text-deep">Applications</h1>
            <p class="mt-2 text-text-mid">Review candidates across your active roles.</p>
        </div>
        <a href="{{ route('employer.dashboard') }}" class="rounded-full bg-white/70 px-5 py-3 text-sm font-semibold text-forest transition hover:bg-white">Back to dashboard</a>
    </div>

    <div class="glass overflow-hidden rounded-lg">
        <div class="divide-y divide-white/70">
            @forelse($paginator as $application)
                <div class="flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="font-semibold text-deep">{{ $application->seekerProfile?->name ?? 'Candidate' }}</h2>
                        <p class="mt-1 text-sm text-text-light">{{ $application->job?->title ?? 'Role' }} &middot; {{ $application->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        @if($application->match_score)
                            <span class="rounded-full bg-mint/10 px-3 py-1 text-xs font-semibold text-sage">{{ $application->match_score }}% match</span>
                        @endif
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-text-mid">{{ ucfirst($application->status->value) }}</span>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <h2 class="font-syne text-2xl font-bold text-deep">No applications yet</h2>
                    <p class="mt-2 text-text-mid">Applications will appear here once candidates apply.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8">{{ $paginator->links() }}</div>
</div>
@endsection
