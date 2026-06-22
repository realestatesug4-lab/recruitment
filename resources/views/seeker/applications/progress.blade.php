@extends('layouts.app')

@section('title', 'Application Progress - JobsUG')

@section('content')
<div class="page-wrap py-10">
    <div class="mx-auto max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <div class="text-sm font-semibold uppercase tracking-wide text-sage">Application tracking</div>
                <h1 class="mt-2 font-syne text-4xl font-bold text-deep">Your applications</h1>
                <p class="mt-3 max-w-2xl text-text-mid">Track each role from submitted to interview and final decision.</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="w-fit rounded-full bg-white/70 px-5 py-3 text-sm font-semibold text-forest transition hover:bg-white">Browse jobs</a>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            @forelse($applications as $application)
                @php
                    $steps = ['submitted' => 'Applied', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'hired' => 'Hired'];
                    $currentIndex = array_search($application->status->value, array_keys($steps), true);
                    $currentIndex = $currentIndex === false ? 0 : $currentIndex;
                @endphp

                <article class="glass rounded-lg p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="font-syne text-2xl font-bold text-deep">{{ $application->job->title }}</h2>
                            <p class="mt-1 text-sm text-text-mid">{{ $application->job->company->name }} &middot; Applied {{ $application->created_at?->diffForHumans() }}</p>
                        </div>
                        <span class="w-fit rounded-full px-3 py-1 text-xs font-semibold {{ match($application->status->value) {
                            'submitted' => 'bg-blue-100 text-blue-700',
                            'shortlisted' => 'bg-mint/10 text-forest',
                            'interview' => 'bg-purple-100 text-purple-700',
                            'hired' => 'bg-forest/10 text-forest',
                            'rejected' => 'bg-rose-100 text-rose-700',
                            default => 'bg-gray-100 text-text-mid',
                        } }}">
                            {{ str($application->status->value)->replace('-', ' ')->title() }}
                        </span>
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-4">
                        @foreach($steps as $value => $label)
                            @php($index = $loop->index)
                            <div class="rounded-lg p-3 {{ $index <= $currentIndex ? 'bg-mint/15 text-forest' : 'bg-white/60 text-text-light' }}">
                                <div class="text-xs font-semibold uppercase tracking-wide">Step {{ $index + 1 }}</div>
                                <div class="mt-1 text-sm font-semibold">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>

                    @if($application->status->value === 'rejected')
                        <div class="mt-5 rounded-lg bg-rose-50 p-4 text-sm text-rose-700">This application has been closed by the employer.</div>
                    @else
                        <p class="mt-5 text-sm text-text-mid">Updates will appear here when the employer changes your status.</p>
                    @endif

                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('jobs.show', $application->job->slug) }}" class="rounded-full bg-white/70 px-4 py-2 text-sm font-semibold text-forest transition hover:bg-white">View job</a>
                    </div>
                </article>
            @empty
                <div class="glass rounded-lg p-10 text-center lg:col-span-2">
                    <h2 class="font-syne text-2xl font-bold text-deep">No applications yet</h2>
                    <p class="mt-2 text-text-mid">When you apply for a role, it will appear here with status updates.</p>
                    <a href="{{ route('jobs.index') }}" class="mt-5 inline-flex rounded-full bg-mint px-6 py-3 text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">Find jobs</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
