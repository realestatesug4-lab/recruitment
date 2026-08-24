@extends('layouts.dashboard')

@section('title', 'Application Details — CraneLinks')
@section('page_title', 'Application Details')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('seeker.applications.progress') }}" class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-forest transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            Back to applications
        </a>

        <div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm">
            {{-- Header --}}
            <div class="border-b border-gray-100 p-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $application->job->title ?? 'Role' }}</h1>
                        <p class="mt-1 text-sm text-gray-500">{{ $application->job->company->name ?? 'Company' }}</p>
                    </div>
                    <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset
                        {{ match($application->status->value) {
                            'submitted'   => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            'shortlisted' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            'interview'   => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                            'hired'       => 'bg-green-50 text-green-700 ring-green-600/20',
                            'rejected'    => 'bg-red-50 text-red-700 ring-red-600/20',
                            default       => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                        } }}">
                        {{ str($application->status->value)->replace('-', ' ')->title() }}
                    </span>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid gap-4 border-b border-gray-100 p-6 sm:grid-cols-3">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Applied</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $application->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Match score</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $application->match_score ? $application->match_score . '%' : 'Pending' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Location</p>
                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $application->job->location ?? 'Not specified' }}</p>
                </div>
            </div>

            {{-- Status timeline --}}
            <div class="p-6">
                <h2 class="text-sm font-semibold text-gray-900">Status timeline</h2>

                <div class="mt-4 space-y-0">
                    {{-- Initial submission --}}
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-forest text-white">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                            </div>
                            <div class="w-0.5 flex-1 bg-gray-200"></div>
                        </div>
                        <div class="pb-6">
                            <p class="text-sm font-semibold text-gray-900">Application submitted</p>
                            <p class="text-xs text-gray-500">{{ $application->created_at?->diffForHumans() ?? 'Recently' }}</p>
                        </div>
                    </div>

                    {{-- Status changes --}}
                    @forelse($application->statusHistory as $history)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full
                                    {{ $history->new_status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                    @if($history->new_status === 'rejected')
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    @else
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" /></svg>
                                    @endif
                                </div>
                                @if(!$loop->last)<div class="w-0.5 flex-1 bg-gray-200"></div>@endif
                            </div>
                            <div class="{{ $loop->last ? '' : 'pb-6' }}">
                                <p class="text-sm font-semibold text-gray-900">
                                    Moved to <span class="capitalize">{{ str($history->new_status)->replace('-', ' ') }}</span>
                                </p>
                                <p class="text-xs text-gray-500">{{ $history->created_at?->diffForHumans() }}</p>
                                @if($history->note)
                                    <p class="mt-1.5 rounded-lg bg-gray-50 px-3 py-2 text-sm text-gray-600 italic">{{ $history->note }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Waiting for employer review. Updates will appear here.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Cover letter --}}
            @if($application->cover_letter)
                <div class="border-t border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900">Your cover letter</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $application->cover_letter }}</p>
                </div>
            @endif

            {{-- Actions --}}
            <div class="border-t border-gray-100 p-6">
                <div class="flex flex-wrap gap-3">
                    @if($application->job)
                        <a href="{{ route('jobs.show', $application->job->slug) }}" class="rounded-lg bg-forest px-4 py-2 text-sm font-semibold text-white hover:bg-sage transition">View job listing</a>
                    @endif
                    @if($application->resume_path)
                        <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Download CV</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
