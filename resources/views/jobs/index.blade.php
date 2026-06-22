@extends('layouts.app')

@section('content')
<div class="page-wrap py-10" x-data="jobFilters()" x-init="init()">

  <div class="flex gap-6">

    {{-- Sidebar filters --}}
    <aside class="w-72 flex-shrink-0 hidden lg:block">
      <div class="glass rounded-2xl p-6 sticky top-24">
        <div class="flex items-center justify-between mb-5">
          <h3 class="font-syne font-bold text-deep">Filters</h3>
          <button @click="clearAll" class="text-xs text-sage hover:underline">Clear all</button>
        </div>

        {{-- Employment Type --}}
        <div class="mb-5">
          <div class="text-xs font-semibold uppercase tracking-wider text-text-light mb-3">Type</div>
          @foreach(['Full-time','Part-time','Contract','Remote'] as $type)
          <label class="flex items-center gap-2.5 py-1.5 cursor-pointer group">
            <input type="checkbox"
                   @change="toggleFilter('type', '{{ $type }}')"
                   :checked="filters.type.includes('{{ $type }}')"
                   class="rounded border-forest/20 text-forest focus:ring-mint">
            <span class="text-sm text-text-mid group-hover:text-deep transition">{{ $type }}</span>
          </label>
          @endforeach
        </div>

        {{-- Salary Range --}}
        <div class="mb-5">
          <div class="text-xs font-semibold uppercase tracking-wider text-text-light mb-3">Salary (UGX/mo)</div>
          <input type="range" min="0" max="5000000" step="100000"
                 x-model="filters.salaryMax"
                 @input.debounce.500ms="fetchJobs"
                 class="w-full accent-mint">
          <div class="flex justify-between text-xs text-text-light mt-1">
            <span>0</span>
            <span x-text="(filters.salaryMax || 5000000).toLocaleString() + ' UGX'"></span>
          </div>
        </div>
      </div>
    </aside>

    {{-- Jobs List --}}
    <div class="flex-1 min-w-0">

      {{-- Sort bar --}}
      <div class="flex items-center justify-between mb-5">
        <span class="text-sm text-text-mid">
          <span x-text="total.toLocaleString()"></span> jobs found
        </span>
        <select x-model="sort" @change="fetchJobs" class="glass text-sm rounded-full px-4 py-2 border-0 outline-none">
          <option value="relevance">Most Relevant</option>
          <option value="date">Most Recent</option>
          <option value="salary_desc">Highest Salary</option>
        </select>
      </div>

      {{-- Loading skeleton --}}
      <div x-show="loading" class="space-y-3">
        <template x-for="i in 5" :key="i">
          <div class="glass rounded-2xl p-5 animate-pulse h-24"></div>
        </template>
      </div>

      {{-- Job cards --}}
      <div x-show="!loading" class="space-y-3">
        <template x-for="job in jobs" :key="job.id">
          <div class="glass rounded-2xl p-5 flex items-center gap-4 hover:-translate-y-1 hover:shadow-lg transition-all duration-200 cursor-pointer"
               @click="window.location.href = `/jobs/${job.slug}`">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center font-syne font-bold text-white flex-shrink-0"
                 :style="`background:${job.color}`"
                 x-text="job.company.charAt(0)"></div>
            <div class="flex-1 min-w-0">
              <div class="font-semibold text-deep text-sm" x-text="job.title"></div>
              <div class="text-xs text-text-light mt-0.5" x-text="`${job.company} · ${job.location}`"></div>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-xs px-3 py-1 rounded-full font-medium"
                    :class="{
                      'bg-mint/10 text-sage': job.type === 'Full-time',
                      'bg-amber/10 text-amber-700': job.type === 'Contract',
                      'bg-blue-100 text-blue-700': job.type === 'Remote',
                    }"
                    x-text="job.type"></span>
              <span class="text-xs text-text-light" x-text="job.posted_at"></span>
            </div>
          </div>
        </template>
      </div>

      {{-- Pagination --}}
      <div class="flex justify-center mt-8 gap-2">
        <button @click="page--; fetchJobs()" :disabled="page === 1"
                class="glass px-5 py-2 rounded-full text-sm font-medium disabled:opacity-40 hover:bg-forest/7 transition">
          ← Previous
        </button>
        <button @click="page++; fetchJobs()"
                class="glass px-5 py-2 rounded-full text-sm font-medium hover:bg-forest/7 transition">
          Next →
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
