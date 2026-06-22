@extends('layouts.app')

@section('title', 'Company Onboarding - JobsUG')

@section('content')
<div class="page-wrap py-10">
    <div class="mx-auto max-w-5xl">
        <div class="mb-8 grid gap-5 lg:grid-cols-[1fr_320px] lg:items-end">
            <div>
                <div class="text-sm font-semibold uppercase tracking-wide text-sage">Company onboarding</div>
                <h1 class="mt-2 font-syne text-4xl font-bold text-deep">Set up your hiring workspace</h1>
                <p class="mt-3 max-w-2xl text-text-mid">Create a company profile recruiters can use immediately. Jobs remain drafts while your company is pending verification.</p>
            </div>
            <div class="rounded-lg bg-white/70 p-4 text-sm text-text-mid shadow-sm">
                <div class="font-semibold text-deep">Verification status</div>
                <div class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Pending after submission</div>
            </div>
        </div>

        <form method="POST" action="{{ route('employer.onboarding.store') }}" class="grid gap-6 lg:grid-cols-[1fr_300px]">
            @csrf

            <div class="glass rounded-lg p-6">
                <div class="mb-6 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-mint text-sm font-bold text-forest">1</span>
                    <div>
                        <h2 class="font-syne text-2xl font-bold text-deep">Company basics</h2>
                        <p class="text-sm text-text-mid">Only the essentials first. You can refine the profile later.</p>
                    </div>
                </div>

                <div class="grid gap-5">
                    <div>
                        <x-input-label for="company_name" value="Company name" />
                        <x-text-input id="company_name" name="company_name" class="mt-2" value="{{ old('company_name') }}" required />
                        <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="work_email" value="Work email" />
                            <x-text-input id="work_email" name="work_email" type="email" class="mt-2" value="{{ old('work_email', auth()->user()->email) }}" required />
                            <x-input-error :messages="$errors->get('work_email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone number" />
                            <x-text-input id="phone" name="phone" class="mt-2" value="{{ old('phone') }}" placeholder="+256..." required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <x-input-label for="job_title" value="Your role" />
                            <select id="job_title" name="job_title" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep outline-none focus:border-mint focus:ring-2 focus:ring-mint/30">
                                @foreach(['Recruiter', 'HR Manager', 'Admin', 'Founder'] as $role)
                                    <option value="{{ $role }}" @selected(old('job_title') === $role)>{{ $role }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('job_title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="website" value="Website" />
                            <x-text-input id="website" name="website" type="url" class="mt-2" value="{{ old('website') }}" placeholder="https://example.co.ug" />
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <div>
                            <x-input-label for="industry" value="Industry" />
                            <x-text-input id="industry" name="industry" class="mt-2" value="{{ old('industry') }}" />
                            <x-input-error :messages="$errors->get('industry')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="size" value="Team size" />
                            <x-text-input id="size" name="size" type="number" min="1" class="mt-2" value="{{ old('size') }}" />
                            <x-input-error :messages="$errors->get('size')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="location" value="Headquarters" />
                            <x-text-input id="location" name="location" class="mt-2" value="{{ old('location', 'Kampala') }}" />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" value="Company description" />
                        <textarea id="description" name="description" rows="5" class="mt-2 w-full rounded-lg border border-white/80 bg-white/70 px-4 py-3 text-sm text-deep shadow-sm outline-none transition focus:border-mint focus:ring-2 focus:ring-mint/30">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="glass rounded-lg p-5">
                    <h2 class="font-syne text-xl font-bold text-deep">Review path</h2>
                    <div class="mt-4 space-y-3 text-sm text-text-mid">
                        <div class="flex gap-3"><span class="font-bold text-sage">1</span><span>Email and phone are captured for trust checks.</span></div>
                        <div class="flex gap-3"><span class="font-bold text-sage">2</span><span>Your company appears as pending verification.</span></div>
                        <div class="flex gap-3"><span class="font-bold text-sage">3</span><span>You can prepare draft jobs while review is underway.</span></div>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-full bg-mint px-6 py-3 text-sm font-semibold text-forest transition hover:bg-[#6dc99a]">
                    Submit company profile
                </button>
            </aside>
        </form>
    </div>
</div>
@endsection
