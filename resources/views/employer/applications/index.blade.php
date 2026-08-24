@extends('layouts.dashboard')

@section('title', 'Applications — CraneLinks')
@section('page_title', 'Applications')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Applications</h1>
            <p class="mt-1 text-sm text-gray-500">Review candidates across all your active roles.</p>
        </div>
        <a href="{{ route('employer.ats') }}" class="rounded-lg bg-forest px-4 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">ATS Pipeline</a>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
        @if($paginator->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($paginator as $app)
                    <a href="{{ route('employer.applications.show', $app->uuid) }}" class="flex items-center justify-between px-6 py-4 transition hover:bg-gray-50">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-forest/10 text-xs font-bold text-forest">
                                {{ strtoupper(substr($app->seekerProfile->name ?? 'C', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-gray-900">{{ $app->seekerProfile->name ?? 'Candidate' }}</p>
                                <p class="text-xs text-gray-500">{{ $app->job->title ?? 'Role' }} &middot; {{ $app->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-4">
                            @if($app->match_score)
                                <span class="hidden sm:inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">{{ $app->match_score }}%</span>
                            @endif
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset
                                {{ match($app->status->value) {
                                    'submitted'   => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'shortlisted' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'interview'   => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                    'hired'       => 'bg-green-50 text-green-700 ring-green-600/20',
                                    'rejected'    => 'bg-red-50 text-red-700 ring-red-600/20',
                                    default       => 'bg-gray-50 text-gray-700 ring-gray-600/20',
                                } }}">
                                {{ str($app->status->value)->replace('-', ' ')->title() }}
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-sm font-medium text-gray-900">No applications yet</p>
                <p class="mt-1 text-sm text-gray-500">Applications will appear here once candidates apply.</p>
            </div>
        @endif
    </div>

    <div class="mt-6">{{ $paginator->links() }}</div>
</div>
@endsection
