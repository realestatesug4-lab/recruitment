@extends('layouts.app')

@section('title', 'Complete Profile - JobsUG')

@section('content')
<div class="page-wrap py-10">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 grid gap-5 lg:grid-cols-[1fr_280px] lg:items-end">
            <div>
                <div class="text-sm font-semibold uppercase tracking-wide text-sage">Profile setup</div>
                <h1 class="mt-2 font-syne text-4xl font-bold text-deep">Build your reusable application profile</h1>
                <p class="mt-3 max-w-2xl text-text-mid">Add what you have now. You can apply even if the profile is not perfect, then improve it over time.</p>
            </div>
            <div class="rounded-lg bg-white/70 p-4 shadow-sm">
                <div class="text-sm font-semibold text-deep">Completion starts here</div>
                <div class="mt-3 h-2 rounded-full bg-gray-100">
                    <div class="h-2 w-1/4 rounded-full bg-mint"></div>
                </div>
            </div>
        </div>

        <form action="{{ route('seeker.profile.store') }}" method="POST" enctype="multipart/form-data" class="glass rounded-lg p-6">
            @csrf

            <div class="grid gap-6">
                <section>
                    <div class="mb-4 flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mint text-sm font-bold text-forest">1</span>
                        <div>
                            <h2 class="font-syne text-2xl font-bold text-deep">Personal summary</h2>
                            <p class="text-sm text-text-mid">A short headline helps employers understand your fit quickly.</p>
                        </div>
                    </div>

                    <div class="grid gap-5">
                        <div>
                            <x-input-label for="headline" value="Headline" />
                            <x-text-input id="headline" name="headline" class="mt-2" value="{{ old('headline') }}" placeholder="Laravel developer, accountant, sales associate..." />
                            <x-input-error :messages="$errors->get('headline')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="bio" value="Profile summary" />
                            <textarea id="bio" name="bio" rows="5" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep shadow-sm outline-none transition focus:border-mint focus:ring-2 focus:ring-mint/30">{{ old('bio') }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                        </div>
                    </div>
                </section>

                <section class="grid gap-5 md:grid-cols-2">
                    <div>
                        <x-input-label for="location" value="District or city" />
                        <x-text-input id="location" name="location" class="mt-2" value="{{ old('location', 'Kampala') }}" />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="experience_level" value="Experience level" />
                        <select id="experience_level" name="experience_level" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                            @foreach(['entry' => 'Entry level', 'junior' => 'Junior', 'mid' => 'Mid level', 'senior' => 'Senior'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('experience_level') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('experience_level')" class="mt-2" />
                    </div>
                </section>

                <section class="grid gap-5 md:grid-cols-2">
                    <div>
                        <x-input-label for="skills" value="Skills" />
                        <x-text-input id="skills" name="skills[]" class="mt-2" value="{{ old('skills.0') }}" placeholder="Laravel, React, Excel" />
                        <p class="mt-2 text-xs text-text-light">Separate skills with commas.</p>
                        <x-input-error :messages="$errors->get('skills')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="resume" value="CV upload" />
                        <input id="resume" type="file" name="resume" class="mt-2 block w-full text-sm text-text-mid" />
                        <p class="mt-2 text-xs text-text-light">PDF, DOC, or DOCX. You can add it later if needed.</p>
                        <x-input-error :messages="$errors->get('resume')" class="mt-2" />
                    </div>
                </section>
            </div>

            <div class="mt-6 flex flex-col gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('seeker.dashboard') }}" class="text-sm font-semibold text-text-mid hover:text-forest">Skip for now</a>
                <button type="submit" class="rounded-full bg-mint px-6 py-3 text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">
                    Save profile
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
