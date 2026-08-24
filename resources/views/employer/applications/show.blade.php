@extends('layouts.dashboard')

@section('title', 'Candidate — CraneLinks')
@section('page_title', 'Candidate Detail')

@section('content')
@php($model = $application->application)

<div class="px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('employer.applications.index') }}" class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-forest transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Back to applications
    </a>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_340px]">

        {{-- Main content --}}
        <div class="space-y-6">
            {{-- Candidate header --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-forest/10 text-lg font-bold text-forest">
                            {{ strtoupper(substr($model->seekerProfile->name ?? 'C', 0, 1)) }}
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $model->seekerProfile->name ?? 'Candidate' }}</h1>
                            <p class="text-sm text-gray-500">{{ $model->job->title ?? 'Role' }} &middot; {{ $model->job->company->name ?? '' }}</p>
                            <p class="mt-1 text-xs text-gray-400">Applied {{ $model->created_at?->diffForHumans() }} &middot; {{ $model->seekerProfile->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-inset
                        {{ match($model->status->value) {
                            'submitted'   => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            'shortlisted' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            'interview'   => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                            'hired'       => 'bg-green-50 text-green-700 ring-green-600/20',
                            'rejected'    => 'bg-red-50 text-red-700 ring-red-600/20',
                            default       => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                        } }}">
                        {{ str($model->status->value)->replace('-', ' ')->title() }}
                    </span>
                </div>
            </div>

            {{-- Cover letter --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">Cover letter</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $model->cover_letter ?: 'No cover letter provided.' }}</p>
            </div>

            {{-- Status history --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">Activity timeline</h2>
                <div class="mt-4 space-y-0">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-forest text-white">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                            </div>
                            <div class="w-0.5 flex-1 bg-gray-200"></div>
                        </div>
                        <div class="pb-4">
                            <p class="text-sm font-medium text-gray-900">Application submitted</p>
                            <p class="text-xs text-gray-500">{{ $model->created_at?->format('M d, Y \a\t g:ia') }}</p>
                        </div>
                    </div>

                    @forelse($model->statusHistory as $h)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full
                                    {{ $h->new_status === 'rejected' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4" /></svg>
                                </div>
                                @if(!$loop->last)<div class="w-0.5 flex-1 bg-gray-200"></div>@endif
                            </div>
                            <div class="{{ $loop->last ? '' : 'pb-4' }}">
                                <p class="text-sm font-medium text-gray-900">
                                    Moved to <span class="capitalize">{{ str($h->new_status)->replace('-', ' ') }}</span>
                                    @if($h->changedBy) <span class="text-gray-400">by {{ $h->changedBy->name }}</span> @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ $h->created_at?->format('M d, Y \a\t g:ia') }}</p>
                                @if($h->note)
                                    <p class="mt-1 rounded bg-gray-50 px-2 py-1 text-xs text-gray-600 italic">{{ $h->note }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 pl-11">No status changes yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar: ATS actions --}}
        <div class="space-y-4">
            {{-- Move status --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Move candidate</h3>
                <form method="POST" action="{{ route('employer.ats.update-status', $model->uuid) }}" class="mt-4 space-y-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-medium text-gray-600">New status</label>
                        <select name="status" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:border-forest focus:ring-forest">
                            @foreach(\App\Domain\Applications\Enums\ApplicationStatus::cases() as $s)
                                @if($s->value !== 'draft')
                                    <option value="{{ $s->value }}" @selected($s === $model->status)>
                                        {{ str($s->value)->replace('-', ' ')->title() }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Note (optional)</label>
                        <textarea name="note" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 text-sm focus:border-forest focus:ring-forest" placeholder="e.g. Scheduled phone screen for Monday"></textarea>
                    </div>
                    <button type="submit" class="w-full rounded-lg bg-forest py-2 text-sm font-semibold text-white hover:bg-sage transition">Update status</button>
                </form>
            </div>

            {{-- Candidate stats --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-900">Candidate info</h3>
                <dl class="mt-3 space-y-2.5 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Match score</dt>
                        <dd class="font-semibold text-gray-900">{{ $model->match_score ? $model->match_score . '%' : '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Applied</dt>
                        <dd class="font-semibold text-gray-900">{{ $model->created_at?->format('M d, Y') ?? '—' }}</dd>
                    </div>
                    @if($model->resume_path)
                        <div>
                            <a href="{{ asset('storage/' . $model->resume_path) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-semibold text-forest hover:text-sage">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                Download CV
                            </a>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
