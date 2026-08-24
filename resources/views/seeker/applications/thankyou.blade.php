@extends('layouts.dashboard')

@section('title', 'Application Submitted — CraneLinks')
@section('page_title', 'Application Submitted')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-16">
    <div class="mx-auto max-w-xl text-center">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
            <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <h1 class="mt-6 text-2xl font-bold text-gray-900">Application submitted!</h1>
        <p class="mt-2 text-sm text-gray-500">
            Your application for <span class="font-semibold text-gray-900">{{ $job->title }}</span> at
            <span class="font-semibold text-gray-900">{{ $job->company->name ?? 'the company' }}</span>
            has been sent. We will notify you when the hiring team updates your status.
        </p>

        <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('seeker.applications.progress') }}" class="rounded-lg bg-forest px-5 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">View my applications</a>
            <a href="{{ route('jobs.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Browse more jobs</a>
        </div>
    </div>
</div>
@endsection
