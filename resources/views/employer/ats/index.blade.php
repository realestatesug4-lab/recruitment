@extends('layouts.dashboard')

@section('title', 'ATS Pipeline — CraneLinks')
@section('page_title', 'ATS Pipeline')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">ATS Pipeline</h1>
            <p class="mt-1 text-sm text-gray-500">Drag candidates through your hiring stages. Move them with the status dropdown.</p>
        </div>
        <a href="{{ route('employer.applications.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">List view</a>
    </div>

    <div class="flex gap-4 overflow-x-auto pb-4">
        @foreach($columns as $statusValue => $applications)
            @php
                $statusLabel = str($statusValue)->replace('-', ' ')->title();
                $statusColor = match($statusValue) {
                    'submitted'   => 'border-t-blue-500',
                    'shortlisted' => 'border-t-emerald-500',
                    'interview'   => 'border-t-purple-500',
                    'hired'       => 'border-t-green-500',
                    'rejected'    => 'border-t-red-500',
                    default       => 'border-t-gray-300',
                };
            @endphp

            <div class="w-72 shrink-0">
                {{-- Column header --}}
                <div class="rounded-t-xl border border-b-0 border-gray-200 bg-gray-50 px-4 py-3 {{ $statusColor }} border-t-2">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-gray-900">{{ $statusLabel }}</h2>
                        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-200 text-xs font-bold text-gray-600">{{ $applications->count() }}</span>
                    </div>
                </div>

                {{-- Cards --}}
                <div class="space-y-2 rounded-b-xl border border-gray-200 bg-gray-50/50 p-2 min-h-[200px]">
                    @forelse($applications as $app)
                        <div class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm transition hover:shadow-md">
                            {{-- Candidate info --}}
                            <div class="flex items-start gap-2.5">
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-forest/10 text-xs font-bold text-forest">
                                    {{ strtoupper(substr($app->seekerProfile->name ?? 'C', 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('employer.applications.show', $app->uuid) }}" class="text-sm font-semibold text-gray-900 hover:text-forest transition">
                                        {{ $app->seekerProfile->name ?? 'Candidate' }}
                                    </a>
                                    <p class="text-xs text-gray-500 truncate">{{ $app->job->title ?? 'Role' }}</p>
                                </div>
                            </div>

                            {{-- Score + applied --}}
                            <div class="mt-2.5 flex items-center gap-2">
                                @if($app->match_score)
                                    <span class="rounded bg-emerald-50 px-1.5 py-0.5 text-[10px] font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">{{ $app->match_score }}%</span>
                                @endif
                                <span class="text-[10px] text-gray-400">{{ $app->created_at?->diffForHumans() }}</span>
                            </div>

                            {{-- Status changer --}}
                            <form method="POST" action="{{ route('employer.ats.update-status', $app->uuid) }}" class="mt-3">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="block w-full rounded border-gray-200 px-2 py-1.5 text-xs text-gray-700 focus:border-forest focus:ring-forest">
                                    @foreach($statuses as $s)
                                        @if($s->value !== 'draft')
                                            <option value="{{ $s->value }}" @selected($s->value === $statusValue)>
                                                {{ str($s->value)->replace('-', ' ')->title() }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    @empty
                        <div class="flex h-24 items-center justify-center rounded-lg border border-dashed border-gray-200 text-xs text-gray-400">
                            No candidates
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
