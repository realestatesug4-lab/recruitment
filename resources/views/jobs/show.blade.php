@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="glass rounded-3xl p-8 shadow-lg">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex-1">
                <span class="inline-flex rounded-full bg-mint/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-forest">
                    {{ str($job->job_type->value)->replace('-', ' ')->title() }}
                </span>

                <h1 class="mt-4 text-4xl font-syne font-bold text-deep">{{ $job->title }}</h1>

                <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-text-mid">
                    <span>{{ $job->company->name }}</span>
                    <span>{{ $job->location ?? 'Uganda' }}</span>
                    @if($job->salary_min)
                        <span>
                            UGX {{ number_format($job->salary_min) }}
                            @if($job->salary_max)
                                - {{ number_format($job->salary_max) }}
                            @endif
                        </span>
                    @endif
                </div>
            </div>

            <div class="w-full space-y-3 lg:w-64">
                @auth
                    <form action="{{ route('seeker.saved-jobs.toggle', $job->slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-full bg-white px-5 py-3 text-sm font-semibold text-forest transition hover:bg-gray-50">
                            {{ auth()->user()->savedJobs()->where('job_id', $job->id)->exists() ? 'Saved' : 'Save job' }}
                        </button>
                    </form>

                    <a href="{{ route('seeker.applications.create', $job->slug) }}" class="block w-full rounded-full bg-mint px-5 py-3 text-center text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">
                        Apply now
                    </a>
                @endauth

                @guest
                    <a href="{{ route('seeker.applications.create', $job->slug) }}" class="block w-full rounded-full bg-mint px-5 py-3 text-center text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">
                        Sign in to apply
                    </a>
                @endguest
            </div>
        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <h2 class="mb-4 text-xl font-syne font-bold text-deep">About the role</h2>
                <div class="prose prose-sm max-w-none text-text-mid">
                    {!! $job->description !!}
                </div>

                @if($job->skills->isNotEmpty())
                    <h3 class="mb-3 mt-8 text-lg font-syne font-bold text-deep">Required skills</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($job->skills as $skill)
                            <span class="rounded-full bg-forest/10 px-3 py-2 text-sm font-medium text-forest">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="space-y-5 rounded-2xl bg-white/60 p-6">
                <div>
                    <div class="text-xs font-semibold uppercase tracking-widest text-text-light">Company</div>
                    <a href="{{ route('companies.show', $job->company->slug) }}" class="mt-2 block font-semibold text-deep hover:text-forest">
                        {{ $job->company->name }}
                    </a>
                    @if($job->company->industry)
                        <p class="mt-1 text-sm text-text-mid">{{ $job->company->industry }}</p>
                    @endif
                </div>

                <div class="border-t border-gray-200 pt-5 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-text-light">Experience</span>
                        <span class="font-medium text-deep">{{ $job->experience_level?->value ? str($job->experience_level->value)->replace('-', ' ')->title() : 'Not specified' }}</span>
                    </div>
                    <div class="mt-3 flex justify-between gap-4">
                        <span class="text-text-light">Posted</span>
                        <span class="font-medium text-deep">{{ $job->published_at?->diffForHumans() ?? $job->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="mt-3 flex justify-between gap-4">
                        <span class="text-text-light">Applications</span>
                        <span class="font-medium text-deep">{{ $job->applications_count }}</span>
                    </div>
                    @if($job->deadline)
                        <div class="mt-3 flex justify-between gap-4">
                            <span class="text-text-light">Deadline</span>
                            <span class="font-medium text-deep">{{ $job->deadline->format('M j, Y') }}</span>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
