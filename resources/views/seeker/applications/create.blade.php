@extends('layouts.app')

@section('title', 'Apply - JobsUG')

@section('content')
<div class="page-wrap py-10">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 grid gap-5 lg:grid-cols-[1fr_280px] lg:items-end">
            <div>
                <div class="text-sm font-semibold uppercase tracking-wide text-sage">One-click application</div>
                <h1 class="mt-2 font-syne text-4xl font-bold text-deep">Apply for {{ $job->title }}</h1>
                <p class="mt-3 max-w-2xl text-text-mid">Review your saved profile, add a short note if useful, and submit. Missing profile details will not block you.</p>
            </div>
            <div class="rounded-lg bg-white/70 p-4 shadow-sm">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-semibold text-deep">Profile</span>
                    <span class="font-bold text-forest">{{ $completion }}%</span>
                </div>
                <div class="mt-3 h-2 rounded-full bg-gray-100">
                    <div class="h-2 rounded-full bg-mint" style="width: {{ $completion }}%"></div>
                </div>
            </div>
        </div>

        @if($existingApplication && $existingApplication->status !== \App\Domain\Applications\Enums\ApplicationStatus::DRAFT)
            <div class="glass rounded-lg p-6">
                <h2 class="font-syne text-2xl font-bold text-deep">Application already submitted</h2>
                <p class="mt-2 text-text-mid">You have already applied for this job. Track its status from your applications page.</p>
                <a href="{{ route('seeker.applications.progress') }}" class="mt-5 inline-flex rounded-full bg-mint px-6 py-3 text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">View progress</a>
            </div>
        @else
            <form action="{{ route('seeker.applications.store', $job->slug) }}" method="POST" enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-[1fr_320px]">
                @csrf

                <div class="space-y-6">
                    <section class="glass rounded-lg p-6">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mint text-sm font-bold text-forest">1</span>
                            <div>
                                <h2 class="font-syne text-2xl font-bold text-deep">Review profile</h2>
                                <p class="text-sm text-text-mid">This is what the employer sees first.</p>
                            </div>
                        </div>

                        @if($profile)
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="rounded-lg bg-white/70 p-4">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-text-light">Name</div>
                                    <div class="mt-1 font-semibold text-deep">{{ auth()->user()->name }}</div>
                                </div>
                                <div class="rounded-lg bg-white/70 p-4">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-text-light">Headline</div>
                                    <div class="mt-1 font-semibold text-deep">{{ $profile->headline ?: 'Not added yet' }}</div>
                                </div>
                                <div class="rounded-lg bg-white/70 p-4">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-text-light">Location</div>
                                    <div class="mt-1 font-semibold text-deep">{{ $profile->location ?: 'Not added yet' }}</div>
                                </div>
                                <div class="rounded-lg bg-white/70 p-4">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-text-light">Experience</div>
                                    <div class="mt-1 font-semibold text-deep">{{ $profile->experience_level ?: 'Not added yet' }}</div>
                                </div>
                            </div>

                            @if($profile->skills->isNotEmpty())
                                <div class="mt-5 flex flex-wrap gap-2">
                                    @foreach($profile->skills as $skill)
                                        <span class="rounded-full bg-forest/10 px-3 py-2 text-sm font-medium text-forest">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="rounded-lg bg-white/70 p-5">
                                <p class="text-text-mid">You have not created a profile yet. You can still apply now and complete it later.</p>
                                <a href="{{ route('seeker.profile.create') }}" class="mt-4 inline-flex text-sm font-semibold text-forest hover:text-sage">Create profile</a>
                            </div>
                        @endif
                    </section>

                    <section class="glass rounded-lg p-6">
                        <div class="mb-5 flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mint text-sm font-bold text-forest">2</span>
                            <div>
                                <h2 class="font-syne text-2xl font-bold text-deep">Add optional context</h2>
                                <p class="text-sm text-text-mid">Keep it short. Your profile does most of the work.</p>
                            </div>
                        </div>

                        <div class="grid gap-5">
                            <div>
                                <x-input-label for="cover_letter" value="Short note" />
                                <textarea id="cover_letter" name="cover_letter" rows="5" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep shadow-sm outline-none transition focus:border-mint focus:ring-2 focus:ring-mint/30" placeholder="Optional: mention availability, strongest fit, or why this role interests you.">{{ old('cover_letter') }}</textarea>
                                <x-input-error :messages="$errors->get('cover_letter')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="resume" value="CV override" />
                                <input id="resume" type="file" name="resume" class="mt-2 block w-full text-sm text-text-mid" />
                                <p class="mt-2 text-xs text-text-light">
                                    @if($profile?->resume_url)
                                        Your saved CV will be used unless you upload a different one.
                                    @else
                                        No saved CV found. Upload one now or submit without it.
                                    @endif
                                </p>
                                <x-input-error :messages="$errors->get('resume')" class="mt-2" />
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-4">
                    <div class="glass rounded-lg p-5">
                        <h2 class="font-syne text-xl font-bold text-deep">Application check</h2>
                        @if(count($missingItems))
                            <p class="mt-2 text-sm text-text-mid">Missing profile details:</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($missingItems as $item)
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ $item }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="mt-2 text-sm text-text-mid">Your profile has the core details employers expect.</p>
                        @endif
                    </div>

                    <button type="submit" class="w-full rounded-full bg-mint px-6 py-3 text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">
                        Submit application
                    </button>

                    <a href="{{ route('jobs.show', $job->slug) }}" class="block text-center text-sm font-semibold text-text-mid hover:text-forest">Back to job</a>
                </aside>
            </form>
        @endif
    </div>
</div>
@endsection
