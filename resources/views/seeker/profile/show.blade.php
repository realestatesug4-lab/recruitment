@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="glass rounded-3xl p-8 shadow-lg">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <h1 class="text-3xl font-syne font-bold text-deep mb-4">Your seeker profile</h1>
                <p class="text-text-mid max-w-2xl">Manage your career brand, keep your resume up to date, and get matched to roles faster.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('seeker.profile.edit') }}" class="btn-mint bg-mint text-forest font-semibold px-5 py-3 rounded-full hover:bg-[#6dc99a] transition">Edit profile</a>
            </div>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-3">
            <div class="glass rounded-3xl p-6">
                <div class="text-text-light uppercase text-xs tracking-[0.2em] mb-3">Headline</div>
                <div class="text-deep font-semibold">{{ $profile->headline }}</div>
            </div>
            <div class="glass rounded-3xl p-6">
                <div class="text-text-light uppercase text-xs tracking-[0.2em] mb-3">Location</div>
                <div class="text-deep font-semibold">{{ $profile->location ?? 'Not set' }}</div>
            </div>
            <div class="glass rounded-3xl p-6">
                <div class="text-text-light uppercase text-xs tracking-[0.2em] mb-3">Experience</div>
                <div class="text-deep font-semibold">{{ $profile->experience_level ?? 'Not set' }}</div>
            </div>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            <div class="glass rounded-3xl p-6">
                <div class="text-text-light uppercase text-xs tracking-[0.2em] mb-3">About you</div>
                <p class="text-text-mid leading-relaxed">{{ $profile->bio }}</p>
            </div>
            <div class="glass rounded-3xl p-6">
                <div class="text-text-light uppercase text-xs tracking-[0.2em] mb-3">Skills</div>
                <div class="flex flex-wrap gap-2">
                    @foreach($profile->skills as $skill)
                        <span class="px-3 py-2 rounded-full bg-forest/10 text-forest text-sm">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
