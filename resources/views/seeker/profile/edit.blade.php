@extends('layouts.dashboard')

@section('title', 'Edit Profile — CraneLinks')
@section('page_title', 'Edit Profile')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900">Edit profile</h1>
        <p class="mt-1 text-sm text-gray-500">Update your professional details for better matching.</p>

        <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Headline</label>
                    <input type="text" name="headline" value="{{ old('headline', $profile->headline) }}"
                           class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                    @error('headline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">About you</label>
                    <textarea name="bio" rows="4" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" value="{{ old('location', $profile->location) }}"
                               class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                        @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Experience level</label>
                        <select name="experience_level" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                            @foreach(['entry' => 'Entry level', 'junior' => 'Junior', 'mid' => 'Mid-level', 'senior' => 'Senior'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('experience_level', $profile->experience_level) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('experience_level') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Skills</label>
                    <input type="text" name="skills[]" value="{{ old('skills.0', $profile->skills->pluck('name')->join(', ')) }}"
                           class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm"
                           placeholder="Laravel, React, Project Management">
                    <p class="mt-1 text-xs text-gray-400">Separate with commas.</p>
                    @error('skills') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">CV upload</label>
                    @if($profile->resume_url)
                        <p class="mt-1 text-xs text-emerald-600">Current CV uploaded. Upload a new one to replace.</p>
                    @endif
                    <input type="file" name="resume" class="mt-1.5 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    @error('resume') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('seeker.profile.show') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="rounded-lg bg-forest px-6 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
