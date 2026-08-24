@extends('layouts.dashboard')

@section('title', 'Create Profile — CraneLinks')
@section('page_title', 'Create Profile')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto max-w-3xl">

        {{-- Header --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-forest/10 px-4 py-1.5 text-xs font-semibold text-forest">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" /></svg>
                Profile setup
            </div>
            <h1 class="mt-4 text-2xl font-bold text-gray-900 sm:text-3xl">Build your professional profile</h1>
            <p class="mt-2 text-sm text-gray-500">This takes about 2 minutes. You can always update it later.</p>
        </div>

        {{-- Step indicator --}}
        <div class="mb-8" x-data="{ step: 1 }">
            <div class="flex items-center justify-center gap-2">
                <template x-for="s in 3" :key="s">
                    <div class="flex items-center gap-2">
                        <div :class="step >= s ? 'bg-forest text-white' : 'bg-gray-200 text-gray-500'"
                             class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-bold transition-all">
                            <template x-if="step > s">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </template>
                            <template x-if="step <= s"><span x-text="s"></span></template>
                        </div>
                        <template x-if="s < 3">
                            <div :class="step > s ? 'bg-forest' : 'bg-gray-200'" class="h-0.5 w-12 transition-all"></div>
                        </template>
                    </div>
                </template>
            </div>
            <div class="mt-3 flex justify-center gap-8 text-xs text-gray-500">
                <span :class="step >= 1 ? 'text-forest font-semibold' : ''">Basics</span>
                <span :class="step >= 2 ? 'text-forest font-semibold' : ''">Details</span>
                <span :class="step >= 3 ? 'text-forest font-semibold' : ''">Skills & CV</span>
            </div>

            <form action="{{ route('seeker.profile.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf

                {{-- Step 1: Basics --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                        <h2 class="text-lg font-semibold text-gray-900">Personal summary</h2>
                        <p class="text-sm text-gray-500">Help employers understand who you are at a glance.</p>

                        <div>
                            <label for="headline" class="block text-sm font-medium text-gray-700">Professional headline</label>
                            <input id="headline" name="headline" type="text" value="{{ old('headline') }}"
                                   class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm"
                                   placeholder="e.g. Full-Stack Developer, Marketing Specialist, Accountant">
                            @error('headline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700">About you</label>
                            <textarea id="bio" name="bio" rows="4"
                                      class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm"
                                      placeholder="Brief summary of your experience, strengths, and career goals...">{{ old('bio') }}</textarea>
                            @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Step 2: Details --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                        <h2 class="text-lg font-semibold text-gray-900">Location & experience</h2>
                        <p class="text-sm text-gray-500">This helps match you with relevant roles.</p>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">City / District</label>
                            <input id="location" name="location" type="text" value="{{ old('location', 'Kampala') }}"
                                   class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm"
                                   placeholder="e.g. Kampala, Mbarara, Gulu">
                            @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="experience_level" class="block text-sm font-medium text-gray-700">Experience level</label>
                            <select id="experience_level" name="experience_level"
                                    class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                                @foreach(['entry' => 'Entry level (0–1 years)', 'junior' => 'Junior (1–3 years)', 'mid' => 'Mid-level (3–6 years)', 'senior' => 'Senior (6+ years)'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('experience_level') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('experience_level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Step 3: Skills & CV --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                        <h2 class="text-lg font-semibold text-gray-900">Skills & CV</h2>
                        <p class="text-sm text-gray-500">These power smart job matching and one-click applications.</p>

                        <div>
                            <label for="skills" class="block text-sm font-medium text-gray-700">Key skills</label>
                            <input id="skills" name="skills[]" type="text" value="{{ old('skills.0') }}"
                                   class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm"
                                   placeholder="e.g. Laravel, React, Excel, Project Management">
                            <p class="mt-1 text-xs text-gray-400">Separate skills with commas. Add as many as relevant.</p>
                            @error('skills') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="resume" class="block text-sm font-medium text-gray-700">Upload CV</label>
                            <div class="mt-1.5 flex justify-center rounded-lg border border-dashed border-gray-300 px-6 py-8">
                                <div class="text-center">
                                    <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg>
                                    <div class="mt-2 flex text-sm leading-6 text-gray-500">
                                        <label for="resume" class="relative cursor-pointer rounded-md font-semibold text-forest hover:text-sage">
                                            <span>Upload a file</span>
                                            <input id="resume" name="resume" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-400">PDF, DOC, DOCX</p>
                                </div>
                            </div>
                            @error('resume') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Navigation --}}
                <div class="flex items-center justify-between pt-2">
                    <button type="button" @click="step = Math.max(1, step - 1)" x-show="step > 1"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                        &larr; Previous
                    </button>
                    <a href="{{ route('seeker.dashboard') }}" x-show="step === 1" class="text-sm font-medium text-gray-500 hover:text-gray-700">Skip for now</a>

                    <button type="button" @click="step = Math.min(3, step + 1)" x-show="step < 3"
                            class="rounded-lg bg-forest px-5 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">
                        Continue &rarr;
                    </button>
                    <button type="submit" x-show="step === 3"
                            class="rounded-lg bg-forest px-6 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">
                        Save profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
