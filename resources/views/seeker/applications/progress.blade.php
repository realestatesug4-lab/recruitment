@extends('layouts.dashboard')

@section('title', 'Applications — CraneLinks')
@section('page_title', 'My Applications')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My applications</h1>
            <p class="mt-1 text-sm text-gray-500">Track every role from submission to decision.</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="rounded-lg bg-forest px-4 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">
            Browse jobs
        </a>
    </div>

    @forelse($applications as $application)
        @php
            $steps = ['submitted' => 'Applied', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'hired' => 'Hired'];
            $currentValue = $application->status->value;
            $isRejected = $currentValue === 'rejected';
            $stepKeys = array_keys($steps);
            $currentIndex = array_search($currentValue, $stepKeys, true);
            if ($currentIndex === false) $currentIndex = 0;
        @endphp

        <div class="mb-4 rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
            <div class="p-5 sm:p-6">
                {{-- Header --}}
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('seeker.applications.show', $application->uuid) }}" class="text-lg font-semibold text-gray-900 hover:text-forest transition">
                            {{ $application->job->title ?? 'Role' }}
                        </a>
                        <p class="mt-0.5 text-sm text-gray-500">{{ $application->job->company->name ?? 'Company' }} &middot; Applied {{ $application->created_at?->diffForHumans() }}</p>
                    </div>
                    <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset
                        {{ $isRejected ? 'bg-red-50 text-red-700 ring-red-600/20' : match($currentValue) {
                            'submitted'   => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            'shortlisted' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            'interview'   => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                            'hired'       => 'bg-green-50 text-green-700 ring-green-600/20',
                            default       => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                        } }}">
                        {{ str($currentValue)->replace('-', ' ')->title() }}
                    </span>
                </div>

                {{-- Progress steps --}}
                @if(!$isRejected)
                    <div class="mt-6 flex items-center gap-1">
                        @foreach($steps as $value => $label)
                            @php($index = $loop->index)
                            <div class="flex flex-1 flex-col items-center">
                                <div class="flex w-full items-center">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold
                                        {{ $index <= $currentIndex ? 'bg-forest text-white' : 'bg-gray-200 text-gray-500' }}">
                                        @if($index < $currentIndex)
                                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    @if($index < count($steps) - 1)
                                        <div class="mx-1 h-0.5 flex-1 {{ $index < $currentIndex ? 'bg-forest' : 'bg-gray-200' }}"></div>
                                    @endif
                                </div>
                                <span class="mt-1.5 text-[10px] font-medium {{ $index <= $currentIndex ? 'text-forest' : 'text-gray-400' }}">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Rejected banner --}}
                @if($isRejected)
                    <div class="mt-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">
                        <span class="font-semibold">Not selected.</span> This application was not advanced. Keep applying — new roles are posted daily.
                    </div>
                @endif

                {{-- Actions --}}
                <div class="mt-5 flex flex-wrap gap-2">
                    <a href="{{ route('seeker.applications.show', $application->uuid) }}" class="rounded-lg bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">
                        View details
                    </a>
                    @if($application->job)
                        <a href="{{ route('jobs.show', $application->job->slug) }}" class="rounded-lg bg-gray-100 px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">
                            View job
                        </a>
                    @endif
                    @if($application->match_score)
                        <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                            {{ $application->match_score }}% match
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <h2 class="mt-4 text-lg font-semibold text-gray-900">No applications yet</h2>
            <p class="mt-1 text-sm text-gray-500">When you apply for roles, they will appear here with status updates.</p>
            <a href="{{ route('jobs.index') }}" class="mt-5 inline-flex rounded-lg bg-forest px-5 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">Find jobs</a>
        </div>
    @endforelse
</div>
@endsection
