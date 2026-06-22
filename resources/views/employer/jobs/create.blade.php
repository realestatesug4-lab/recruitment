@extends('layouts.app')

@section('title', 'Create Job - JobsUG')

@section('content')
<div class="page-wrap py-10">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 grid gap-5 lg:grid-cols-[1fr_300px] lg:items-end">
            <div>
                <div class="text-sm font-semibold uppercase tracking-wide text-sage">Job posting wizard</div>
                <h1 class="mt-2 font-syne text-4xl font-bold text-deep">Create a job draft</h1>
                <p class="mt-3 max-w-2xl text-text-mid">Break the role into clean sections so candidates can understand the opportunity quickly on mobile.</p>
            </div>
            <div class="rounded-lg bg-white/70 p-4 text-sm shadow-sm">
                <div class="font-semibold text-deep">{{ $company->name }}</div>
                <div class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $company->verification_status === 'verified' ? 'bg-mint/20 text-forest' : 'bg-amber-100 text-amber-700' }}">
                    {{ str($company->verification_status)->replace('-', ' ')->title() }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('employer.jobs.store') }}" class="grid gap-6 lg:grid-cols-[1fr_300px]">
            @csrf

            <div class="space-y-6">
                <section class="glass rounded-lg p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mint text-sm font-bold text-forest">1</span>
                        <div>
                            <h2 class="font-syne text-2xl font-bold text-deep">Basic information</h2>
                            <p class="text-sm text-text-mid">Start with the details candidates scan first.</p>
                        </div>
                    </div>

                    <div class="grid gap-5">
                        <div>
                            <x-input-label for="title" value="Job title" />
                            <x-text-input id="title" name="title" class="mt-2" value="{{ old('title') }}" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <x-input-label for="type" value="Employment type" />
                                <select id="type" name="type" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                                    <option value="full-time" @selected(old('type') === 'full-time')>Full-time</option>
                                    <option value="contract" @selected(old('type') === 'contract')>Contract</option>
                                    <option value="remote" @selected(old('type') === 'remote')>Remote</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="experience_level" value="Experience" />
                                <select id="experience_level" name="experience_level" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                                    <option value="junior" @selected(old('experience_level') === 'junior')>Junior</option>
                                    <option value="mid" @selected(old('experience_level') === 'mid')>Mid</option>
                                    <option value="senior" @selected(old('experience_level') === 'senior')>Senior</option>
                                </select>
                                <x-input-error :messages="$errors->get('experience_level')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="categories" value="Category" />
                            <select id="categories" name="categories[]" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                                <option value="">Choose a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(collect(old('categories', []))->contains($category->id))>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('categories')" class="mt-2" />
                        </div>
                    </div>
                </section>

                <section class="glass rounded-lg p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mint text-sm font-bold text-forest">2</span>
                        <div>
                            <h2 class="font-syne text-2xl font-bold text-deep">Description and skills</h2>
                            <p class="text-sm text-text-mid">Include responsibilities, requirements, and outcomes.</p>
                        </div>
                    </div>

                    <div class="grid gap-5">
                        <div>
                            <x-input-label for="description" value="Job description" />
                            <textarea id="description" name="description" rows="8" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep shadow-sm outline-none transition focus:border-mint focus:ring-2 focus:ring-mint/30" required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label value="Required skills" />
                            <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($skills as $skill)
                                    <label class="flex items-center gap-2 rounded-lg bg-white/70 px-3 py-2 text-sm text-deep">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}" @checked(collect(old('skills', []))->contains($skill->id)) class="rounded border-gray-300 text-forest focus:ring-mint">
                                        <span>{{ $skill->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('skills')" class="mt-2" />
                        </div>
                    </div>
                </section>

                <section class="glass rounded-lg p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mint text-sm font-bold text-forest">3</span>
                        <div>
                            <h2 class="font-syne text-2xl font-bold text-deep">Salary, location, deadline</h2>
                            <p class="text-sm text-text-mid">Salary is optional, but clarity improves application quality.</p>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="location" value="Location" />
                            <x-text-input id="location" name="location" class="mt-2" value="{{ old('location', 'Kampala') }}" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="deadline" value="Application deadline" />
                            <x-text-input id="deadline" name="deadline" type="date" class="mt-2" value="{{ old('deadline') }}" />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="salary_min" value="Salary min UGX" />
                            <x-text-input id="salary_min" name="salary_min" type="number" class="mt-2" value="{{ old('salary_min') }}" />
                            <x-input-error :messages="$errors->get('salary_min')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="salary_max" value="Salary max UGX" />
                            <x-text-input id="salary_max" name="salary_max" type="number" class="mt-2" value="{{ old('salary_max') }}" />
                            <x-input-error :messages="$errors->get('salary_max')" class="mt-2" />
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-4">
                <div class="glass rounded-lg p-5">
                    <h2 class="font-syne text-xl font-bold text-deep">Draft checklist</h2>
                    <div class="mt-4 space-y-3 text-sm text-text-mid">
                        <div class="flex gap-3"><span class="font-bold text-sage">1</span><span>Use a clear title and one category.</span></div>
                        <div class="flex gap-3"><span class="font-bold text-sage">2</span><span>Add skills applicants can self-check.</span></div>
                        <div class="flex gap-3"><span class="font-bold text-sage">3</span><span>Preview before publishing after verification.</span></div>
                    </div>
                </div>

                @if($company->verification_status !== 'verified')
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        This job will be saved as a draft while company verification is pending.
                    </div>
                @endif

                <button type="submit" class="w-full rounded-full bg-mint px-6 py-3 text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">
                    Create draft
                </button>

                <a href="{{ route('employer.jobs.index') }}" class="block text-center text-sm font-semibold text-text-mid hover:text-forest">Cancel</a>
            </aside>
        </form>
    </div>
</div>
@endsection
