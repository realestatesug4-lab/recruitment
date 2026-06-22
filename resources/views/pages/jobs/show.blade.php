@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="glass rounded-3xl p-8 shadow-lg">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row justify-between gap-6 mb-8">
            <div class="flex-1">
                <div class="mb-3">
                    <span class="inline-block px-3 py-1 rounded-full bg-mint/10 text-forest text-xs font-semibold uppercase tracking-widest">
                        {{ $job->type }}
                    </span>
                </div>
                <h1 class="text-4xl font-syne font-bold text-deep mb-2">{{ $job->title }}</h1>
                <div class="flex flex-col lg:flex-row gap-4 text-text-mid mt-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-sage" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z"/></svg>
                        {{ $job->company->name }}
                    </div>
                    @if($job->location)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-sage" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        {{ $job->location }}
                    </div>
                    @endif
                    @if($job->salary_min)
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-sage" fill="currentColor" viewBox="0 0 20 20"><path d="M8.16 2.75a.75.75 0 00-1.32 0l-3.5 8.75H7a.75.75 0 000 1.5H3.75a.75.75 0 00-.75.75v1a.75.75 0 00.75.75h2.5v.75a.75.75 0 001.5 0v-.75h1a.75.75 0 000-1.5h-1v-.75l.84-2.1h2.66a.75.75 0 000-1.5H9.5l-1.34-3.35z"/></svg>
                        UGX {{ number_format($job->salary_min) }} – {{ number_format($job->salary_max) }}/mo
                    </div>
                    @endif
                </div>
            </div>
            <div class="flex flex-col gap-3">
                @auth
                    <form action="{{ route('seeker.saved-jobs.toggle', $job) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-full {{ auth()->user()->savedJobs()->where('job_id', $job->id)->exists() ? 'bg-forest/10 text-forest' : 'bg-gray-100 text-text-mid hover:bg-gray-200' }} transition font-semibold">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                            {{ auth()->user()->savedJobs()->where('job_id', $job->id)->exists() ? 'Saved' : 'Save job' }}
                        </button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-gray-100 text-text-mid hover:bg-gray-200 transition font-semibold">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                        Save job
                    </a>
                @endguest
            </div>
        </div>

        {{-- Job Details Grid --}}
        <div class="grid gap-6 lg:grid-cols-3 mb-10">
            <div class="lg:col-span-2">
                <div class="mb-8">
                    <h2 class="text-xl font-syne font-bold text-deep mb-4">About the role</h2>
                    <div class="prose prose-sm max-w-none text-text-mid leading-relaxed">
                        {!! str($job->description)->replace(['<p>', '</p>'], ['<p class="mb-4">', '</p>'])->replace(['<li>', '</li>'], ['<li class="ml-4 mb-2">', '</li>']) !!}
                    </div>
                </div>

                @if($job->skills->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-syne font-bold text-deep mb-4">Required skills</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($job->skills as $skill)
                            <span class="px-3 py-2 rounded-full bg-forest/10 text-forest text-sm font-medium">{{ $skill->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($job->categories->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-lg font-syne font-bold text-deep mb-4">Categories</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($job->categories as $category)
                            <span class="px-3 py-2 rounded-full bg-mint/10 text-sage text-sm font-medium">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($job->deadline)
                <div class="glass rounded-2xl p-4 mb-8 bg-amber-50 border border-amber-200">
                    <p class="text-sm text-amber-900">
                        <strong>Application deadline:</strong> {{ $job->deadline->format('F j, Y') }}
                        <span class="block text-xs mt-1">{{ $job->deadline->diffForHumans() }}</span>
                    </p>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="glass rounded-2xl p-6 sticky top-24 space-y-6">
                    {{-- Company Info --}}
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-widest text-text-light mb-3">About the company</h3>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center font-syne font-bold text-white flex-shrink-0" style="background: {{ $job->company->color ?? '#1B4332' }}">
                                {{ strtoupper(substr($job->company->name, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('companies.show', $job->company) }}" class="font-semibold text-deep hover:text-forest transition">
                                    {{ $job->company->name }}
                                </a>
                                @if($job->company->industry)
                                <p class="text-xs text-text-light mt-1">{{ $job->company->industry }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Job Meta --}}
                    <div class="border-t border-gray-200 pt-4">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-text-light">Experience</span>
                                <span class="font-medium text-deep">{{ $job->experience_level ?? 'Not specified' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-text-light">Posted</span>
                                <span class="font-medium text-deep">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-text-light">Applications</span>
                                <span class="font-medium text-deep">{{ $job->applications->count() }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- CTA --}}
                    @auth
                        @if(auth()->user()->seekerProfile)
                            <a href="{{ route('seeker.applications.create', $job) }}" class="block w-full text-center bg-mint text-forest font-semibold py-3 rounded-full hover:bg-[#6dc99a] transition">
                                Apply now
                            </a>
                        @else
                            <a href="{{ route('seeker.profile.create') }}" class="block w-full text-center bg-mint text-forest font-semibold py-3 rounded-full hover:bg-[#6dc99a] transition">
                                Complete profile to apply
                            </a>
                        @endif
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="block w-full text-center bg-mint text-forest font-semibold py-3 rounded-full hover:bg-[#6dc99a] transition">
                            Sign in to apply
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    {{-- Similar Jobs --}}
    @if($job->skills->isNotEmpty())
    <div class="mt-16">
        <h2 class="text-3xl font-syne font-bold text-deep mb-6">Similar opportunities</h2>
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach($job->skills->first()->jobs()->where('id', '!=', $job->id)->published()->limit(3)->get() as $similar)
                <x-job-card :job="$similar" />
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('head')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "JobPosting",
  "title": "{{ $job->title }}",
  "description": "{{ strip_tags($job->description) }}",
  "datePosted": "{{ $job->created_at->toIso8601String() }}",
  "validThrough": "{{ $job->deadline?->toIso8601String() }}",
  "employmentType": "{{ strtoupper(str_replace('-', '_', $job->type)) }}",
  "hiringOrganization": {
    "@type": "Organization",
    "name": "{{ $job->company->name }}",
    "sameAs": "{{ $job->company->website }}"
  },
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "{{ $job->location }}",
      "addressCountry": "UG"
    }
  }
  @if($job->salary_min)
  ,"baseSalary": {
    "@type": "MonetaryAmount",
    "currency": "UGX",
    "value": {
      "@type": "QuantitativeValue",
      "minValue": {{ $job->salary_min }},
      "maxValue": {{ $job->salary_max }},
      "unitText": "MONTH"
    }
  }
  @endif
}
</script>
@endpush
@endsection
