@extends('layouts.dashboard')

@section('title', 'Company Profile — CraneLinks')
@section('page_title', 'Company Profile')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mx-auto max-w-3xl">
        <h1 class="text-2xl font-bold text-gray-900">Company profile</h1>
        <p class="mt-1 text-sm text-gray-500">Keep your company information current so candidates know who you are.</p>

        <form action="{{ route('employer.company.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <h2 class="text-lg font-semibold text-gray-900">Basic information</h2>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Company name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $company->name) }}" required
                           class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700">Industry</label>
                        <input id="industry" name="industry" type="text" value="{{ old('industry', $company->industry) }}"
                               class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input id="location" name="location" type="text" value="{{ old('location', $company->location) }}"
                               class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">{{ old('description', $company->description) }}</textarea>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                        <input id="website" name="website" type="url" value="{{ old('website', $company->website) }}"
                               class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm"
                               placeholder="https://">
                    </div>
                    <div>
                        <label for="size" class="block text-sm font-medium text-gray-700">Team size</label>
                        <select id="size" name="size" class="mt-1.5 block w-full rounded-lg border-gray-300 shadow-sm focus:border-forest focus:ring-forest sm:text-sm">
                            @foreach(['1-10' => '1–10', '11-50' => '11–50', '51-200' => '51–200', '201-500' => '201–500', '500+' => '500+'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('size', $company->size) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Company logo</label>
                    @if($company->logo_url)
                        <div class="mt-1.5 mb-2 flex items-center gap-3">
                            <img src="{{ asset('storage/' . $company->logo_url) }}" alt="Current logo" class="h-12 w-12 rounded-lg object-cover ring-1 ring-gray-200">
                            <span class="text-xs text-gray-500">Current logo</span>
                        </div>
                    @endif
                    <input id="logo" name="logo" type="file" accept="image/*"
                           class="mt-1.5 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                    @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('employer.dashboard') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="rounded-lg bg-forest px-6 py-2.5 text-sm font-semibold text-white hover:bg-sage transition">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
