@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="glass rounded-3xl p-8 shadow-lg">
        <h1 class="text-3xl font-syne font-bold text-deep mb-4">Update your seeker profile</h1>

        <form action="{{ route('seeker.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-text-mid">Headline</label>
                <input type="text" name="headline" value="{{ old('headline', $profile->headline) }}" class="mt-2 block w-full rounded-3xl border border-gray-200 bg-white px-4 py-3 text-sm text-deep shadow-sm focus:border-forest focus:ring-forest/30" />
                @error('headline')<p class="text-sm text-rose-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-text-mid">Bio</label>
                <textarea name="bio" rows="5" class="mt-2 block w-full rounded-3xl border border-gray-200 bg-white px-4 py-3 text-sm text-deep shadow-sm focus:border-forest focus:ring-forest/30">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio')<p class="text-sm text-rose-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-text-mid">Location</label>
                    <input type="text" name="location" value="{{ old('location', $profile->location) }}" class="mt-2 block w-full rounded-3xl border border-gray-200 bg-white px-4 py-3 text-sm text-deep shadow-sm focus:border-forest focus:ring-forest/30" />
                    @error('location')<p class="text-sm text-rose-600 mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-mid">Experience level</label>
                    <input type="text" name="experience_level" value="{{ old('experience_level', $profile->experience_level) }}" class="mt-2 block w-full rounded-3xl border border-gray-200 bg-white px-4 py-3 text-sm text-deep shadow-sm focus:border-forest focus:ring-forest/30" />
                    @error('experience_level')<p class="text-sm text-rose-600 mt-2">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-text-mid">Skills</label>
                <input type="text" name="skills[]" value="{{ old('skills.0', $profile->skills->pluck('name')->join(', ')) }}" class="mt-2 block w-full rounded-3xl border border-gray-200 bg-white px-4 py-3 text-sm text-deep shadow-sm focus:border-forest focus:ring-forest/30" placeholder="Comma-separated skills" />
                <p class="text-xs text-text-light mt-2">Enter skills separated by commas. Example: Laravel, Product Management, Python.</p>
                @error('skills')<p class="text-sm text-rose-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-text-mid">Resume</label>
                <input type="file" name="resume" class="mt-2 block w-full text-sm text-text-mid" />
                @error('resume')<p class="text-sm text-rose-600 mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 border-t border-gray-200">
                <button type="submit" class="btn-mint bg-mint text-forest font-semibold px-6 py-3 rounded-full hover:bg-[#6dc99a] transition">Save profile</button>
            </div>
        </form>
    </div>
</div>
@endsection
