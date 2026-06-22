@extends('layouts.app')

@section('title', 'Edit Job - JobsUG')

@section('content')
@php($model = $job->job)

<div class="page-wrap py-10">
    <div class="mx-auto max-w-3xl">
        <div class="mb-8">
            <h1 class="font-syne text-4xl font-bold text-deep">Edit job</h1>
            <p class="mt-2 text-text-mid">{{ $model->title }}</p>
        </div>

        <form method="POST" action="{{ route('employer.jobs.update', $model->slug) }}" class="glass rounded-lg p-6">
            @csrf
            @method('PUT')

            <div class="grid gap-5">
                <div>
                    <x-input-label for="title" value="Job title" />
                    <x-text-input id="title" name="title" class="mt-2" value="{{ old('title', $model->title) }}" required />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="7" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep shadow-sm outline-none transition focus:border-mint focus:ring-2 focus:ring-mint/30" required>{{ old('description', $model->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                            @foreach(['full-time' => 'Full-time', 'contract' => 'Contract', 'remote' => 'Remote'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('type', $model->job_type->value) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="experience_level" value="Experience" />
                        <select id="experience_level" name="experience_level" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                            @foreach(['junior' => 'Junior', 'mid' => 'Mid', 'senior' => 'Senior'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('experience_level', $model->experience_level->value) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div>
                        <x-input-label for="location" value="Location" />
                        <x-text-input id="location" name="location" class="mt-2" value="{{ old('location', $model->location) }}" />
                    </div>
                    <div>
                        <x-input-label for="salary_min" value="Salary min" />
                        <x-text-input id="salary_min" name="salary_min" type="number" class="mt-2" value="{{ old('salary_min', $model->salary_min) }}" />
                    </div>
                    <div>
                        <x-input-label for="salary_max" value="Salary max" />
                        <x-text-input id="salary_max" name="salary_max" type="number" class="mt-2" value="{{ old('salary_max', $model->salary_max) }}" />
                    </div>
                </div>

                <div>
                    <x-input-label for="deadline" value="Deadline" />
                    <x-text-input id="deadline" name="deadline" type="date" class="mt-2" value="{{ old('deadline', $model->deadline?->format('Y-m-d')) }}" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('employer.jobs.index') }}" class="rounded-full bg-white/70 px-5 py-3 text-sm font-semibold text-forest">Cancel</a>
                <x-primary-button>Save changes</x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
