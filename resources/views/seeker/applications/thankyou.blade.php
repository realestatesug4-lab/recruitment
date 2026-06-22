@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
    <div class="glass rounded-3xl p-10 text-center shadow-lg">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-mint/10 text-4xl mb-6">✅</div>
        <h1 class="text-4xl font-syne font-bold text-deep mb-4">Application sent!</h1>
        <p class="text-text-mid max-w-2xl mx-auto mb-8">Your application for <strong>{{ $job->title }}</strong> at <strong>{{ $job->company->name }}</strong> is on its way. The hiring team will review it and we’ll notify you when there’s an update.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('jobs.show', $job->slug) }}" class="btn-mint bg-mint text-forest font-semibold px-6 py-3 rounded-full hover:bg-[#6dc99a] transition">View job</a>
            <a href="{{ route('jobs.index') }}" class="rounded-full border border-gray-200 px-6 py-3 text-sm font-medium text-deep hover:bg-gray-50 transition">Browse more roles</a>
        </div>
    </div>
</div>
@endsection
