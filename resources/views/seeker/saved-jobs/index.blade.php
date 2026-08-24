@extends('layouts.dashboard')

@section('title', 'Saved Jobs — CraneLinks')
@section('page_title', 'Saved Jobs')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Saved jobs</h1>
            <p class="mt-1 text-sm text-gray-500">Roles you bookmarked for later. Apply when you are ready.</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="rounded-lg bg-forest px-4 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">Browse more jobs</a>
    </div>

    @if($savedJobs->count() > 0)
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($savedJobs as $saved)
                @php $job = $saved->job; @endphp
                <div class="group rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md hover:-translate-y-0.5">
                    <div class="p-5">
                        {{-- Company avatar + title --}}
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-forest/10 text-sm font-bold text-forest">
                                {{ strtoupper(substr($job->company->name ?? 'C', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('jobs.show', $job->slug) }}" class="text-sm font-semibold text-gray-900 hover:text-forest transition group-hover:text-forest">
                                    {{ $job->title }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $job->company->name ?? 'Company' }}</p>
                            </div>
                        </div>

                        {{-- Meta --}}
                        <div class="mt-3 flex flex-wrap gap-2 text-xs text-gray-500">
                            @if($job->location)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    {{ $job->location }}
                                </span>
                            @endif
                            @if($job->job_type)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006a41.916 41.916 0 01-4.024-.396M12 2.25c-2.353 0-4.672.156-6.934.46-.988.132-1.726.97-1.726 1.97v4.289c0 .94.644 1.749 1.553 1.97a42.103 42.103 0 005.107.87m0-9.569v9.57" /></svg>
                                    {{ str($job->job_type->value)->replace('-', ' ')->title() }}
                                </span>
                            @endif
                            @if($job->salary_min || $job->salary_max)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    @if($job->salary_min && $job->salary_max)
                                        {{ number_format($job->salary_min) }} – {{ number_format($job->salary_max) }}
                                    @else
                                        {{ number_format($job->salary_min ?? $job->salary_max) }}
                                    @endif
                                </span>
                            @endif
                        </div>

                        {{-- Skills --}}
                        @if($job->skills && $job->skills->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1">
                                @foreach($job->skills->take(4) as $skill)
                                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-600">{{ $skill->name }}</span>
                                @endforeach
                                @if($job->skills->count() > 4)
                                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-400">+{{ $job->skills->count() - 4 }}</span>
                                @endif
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('seeker.applications.create', $job->slug) }}" class="flex-1 rounded-lg bg-forest py-2 text-center text-xs font-semibold text-white hover:bg-sage transition">Apply now</a>
                            <form method="POST" action="{{ route('seeker.saved-jobs.toggle', $job->slug) }}">
                                @csrf
                                <button type="submit" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-red-500 hover:bg-red-50 transition" title="Remove">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $savedJobs->links() }}</div>
    @else
        <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
            </svg>
            <h2 class="mt-4 text-lg font-semibold text-gray-900">No saved jobs yet</h2>
            <p class="mt-1 text-sm text-gray-500">Bookmark roles you are interested in and apply when ready.</p>
            <a href="{{ route('jobs.index') }}" class="mt-5 inline-flex rounded-lg bg-forest px-5 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">Browse jobs</a>
        </div>
    @endif
</div>
@endsection
