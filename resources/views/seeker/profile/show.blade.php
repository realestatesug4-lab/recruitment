@extends('layouts.dashboard')

@section('title', 'My Profile — CraneLinks')
@section('page_title', 'My Profile')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto max-w-4xl">
        @if($profile)
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">My profile</h1>
                <a href="{{ route('seeker.profile.edit') }}" class="rounded-lg bg-forest px-4 py-2 text-sm font-semibold text-white hover:bg-sage transition">Edit profile</a>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-100 p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-forest/10 text-lg font-bold text-forest">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $profile->headline ?? 'No headline set' }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 p-6 sm:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Location</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $profile->location ?? 'Not set' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Experience</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ str($profile->experience_level ?? 'not-set')->replace('-', ' ')->title() }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">CV</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            @if($profile->resume_url)
                                <a href="{{ asset('storage/' . $profile->resume_url) }}" target="_blank" class="text-forest hover:text-sage">Download</a>
                            @else
                                Not uploaded
                            @endif
                        </p>
                    </div>
                </div>

                @if($profile->bio)
                    <div class="border-t border-gray-100 p-6">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">About</p>
                        <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $profile->bio }}</p>
                    </div>
                @endif

                @if($profile->skills->isNotEmpty())
                    <div class="border-t border-gray-100 p-6">
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Skills</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($profile->skills as $skill)
                                <span class="rounded-full bg-forest/10 px-3 py-1 text-sm font-medium text-forest">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">No profile yet</h2>
                <p class="mt-1 text-sm text-gray-500">Create your profile to unlock one-click applications and smart matching.</p>
                <a href="{{ route('seeker.profile.create') }}" class="mt-5 inline-flex rounded-lg bg-forest px-5 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">Create profile</a>
            </div>
        @endif
    </div>
</div>
@endsection
