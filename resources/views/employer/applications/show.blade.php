@extends('layouts.app')

@section('title', 'Application - JobsUG')

@section('content')
@php($model = $application->application)

<div class="page-wrap py-10">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('employer.applications.index') }}" class="text-sm font-semibold text-sage hover:text-forest">&larr; Back to applications</a>

        <section class="glass mt-6 rounded-lg p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="font-syne text-3xl font-bold text-deep">{{ $model->seekerProfile?->name ?? 'Candidate' }}</h1>
                    <p class="mt-2 text-text-mid">{{ $model->job?->title ?? 'Role' }}</p>
                </div>
                <span class="w-fit rounded-full bg-white px-3 py-1 text-xs font-semibold text-text-mid">{{ ucfirst($model->status->value) }}</span>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-3">
                <div class="rounded-lg bg-white/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-text-light">Applied</div>
                    <div class="mt-2 font-semibold text-deep">{{ $model->created_at?->diffForHumans() ?? 'Recently' }}</div>
                </div>
                <div class="rounded-lg bg-white/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-text-light">Match score</div>
                    <div class="mt-2 font-semibold text-deep">{{ $model->match_score ? $model->match_score . '%' : 'Not scored' }}</div>
                </div>
                <div class="rounded-lg bg-white/60 p-4">
                    <div class="text-xs font-semibold uppercase tracking-wide text-text-light">Email</div>
                    <div class="mt-2 truncate font-semibold text-deep">{{ $model->seekerProfile?->email ?? 'Not listed' }}</div>
                </div>
            </div>

            <div class="mt-6">
                <h2 class="font-syne text-xl font-bold text-deep">Cover letter</h2>
                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-text-mid">{{ $model->cover_letter ?: 'No cover letter provided.' }}</p>
            </div>
        </section>
    </div>
</div>
@endsection
