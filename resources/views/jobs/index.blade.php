@extends('layouts.app', ['page' => 'jobs'])

@section('title', 'Browse Jobs - JobsUG')

@section('content')
<div class="page-wrap py-6 sm:py-8 lg:py-10" x-data="jobFilters()" x-init="init()">

  {{-- Page header --}}
  <div class="fade-section mb-6 sm:mb-8">
    <div class="inline-flex items-center gap-2 rounded-full bg-mint/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-wide text-sage mb-3">
      <span class="h-1.5 w-1.5 rounded-full bg-mint"></span>
      Job search
    </div>
    <h1 class="font-syne text-2xl sm:text-3xl lg:text-4xl font-bold text-deep tracking-tight">Find your next role</h1>
    <p class="mt-2 text-sm sm:text-base text-text-mid max-w-xl">Browse opportunities across Uganda — filter by type, salary, and remote options.</p>
  </div>

  {{-- Mobile filter toggle --}}
  <div class="lg:hidden mb-4">
    <button
      type="button"
      @click="filtersOpen = !filtersOpen"
      :aria-expanded="filtersOpen"
      class="w-full glass rounded-xl px-4 py-3.5 flex items-center justify-between min-h-[48px] active:scale-[0.99] transition-transform"
    >
      <span class="flex items-center gap-2 text-sm font-semibold text-deep">
        <svg class="w-4 h-4 text-sage" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h18M7 8h10M10 12h4M12 16h0"/>
        </svg>
        Filters
        <span x-show="activeFilterCount > 0" x-text="activeFilterCount" class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full bg-mint/20 text-sage text-xs font-bold"></span>
      </span>
      <svg class="w-4 h-4 text-text-light transition-transform duration-200" :class="{ 'rotate-180': filtersOpen }" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
      </svg>
    </button>

    <div
      x-show="filtersOpen"
      x-collapse
      x-cloak
      class="mt-2 glass rounded-xl p-5 overflow-hidden"
    >
      <x-job-filters-panel />
    </div>
  </div>

  <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">

    {{-- Desktop sidebar filters --}}
    <aside class="w-full lg:w-72 flex-shrink-0 hidden lg:block">
      <div class="glass rounded-2xl p-5 lg:p-6 sticky top-24">
        <x-job-filters-panel />
      </div>
    </aside>

    {{-- Jobs List --}}
    <div class="flex-1 min-w-0">

      {{-- Sort bar --}}
      <div class="flex flex-col xs:flex-row xs:items-center justify-between gap-3 mb-4 sm:mb-5">
        <span class="text-sm text-text-mid">
          <span x-text="total.toLocaleString()"></span> jobs found
        </span>
        <select x-model="sort" @change="fetchJobs()" class="glass text-sm rounded-full px-4 py-2.5 border-0 outline-none min-h-[44px] w-full xs:w-auto">
          <option value="relevance">Most Relevant</option>
          <option value="date">Most Recent</option>
          <option value="salary_desc">Highest Salary</option>
          <option value="featured">Featured</option>
        </select>
      </div>

      {{-- Loading skeleton --}}
      <div x-show="loading" class="space-y-3">
        <template x-for="i in 5" :key="i">
          <div class="glass rounded-2xl p-4 sm:p-5 animate-pulse h-20 sm:h-24"></div>
        </template>
      </div>

      {{-- Job cards --}}
      <div x-show="!loading" class="space-y-3">
        <template x-for="job in jobs" :key="job.id">
          <a
            :href="`/jobs/${job.slug}`"
            class="job-card glass rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 active:scale-[0.99] no-underline group"
          >
            <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
              <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center font-syne font-bold text-white flex-shrink-0 text-base"
                   :style="`background:${job.color}`"
                   x-text="job.company.charAt(0)"></div>
              <div class="flex-1 min-w-0">
                <div class="font-semibold text-deep text-sm sm:text-base group-hover:text-forest transition-colors truncate" x-text="job.title"></div>
                <div class="text-xs text-text-light mt-0.5 truncate" x-text="`${job.company} · ${job.location}`"></div>
              </div>
            </div>
            <div class="flex items-center justify-between sm:justify-end gap-2 sm:gap-3 pl-14 sm:pl-0 flex-shrink-0">
              <span class="text-xs px-3 py-1 rounded-full font-medium"
                    :class="{
                      'bg-mint/10 text-sage': job.type === 'Full-time',
                      'bg-amber/10 text-amber-700': job.type === 'Contract',
                      'bg-blue-100 text-blue-700': job.type === 'Remote',
                      'bg-forest/10 text-forest': !['Full-time','Contract','Remote'].includes(job.type),
                    }"
                    x-text="job.type"></span>
              <span class="text-xs text-text-light whitespace-nowrap" x-text="job.posted_at"></span>
              <svg class="hidden sm:block w-4 h-4 text-text-light group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
          </a>
        </template>

        <div x-show="!loading && jobs.length === 0" class="glass rounded-2xl p-8 sm:p-10 text-center">
          <p class="text-text-mid text-sm">No jobs match your filters. Try adjusting your criteria.</p>
          <button @click="clearAll(); filtersOpen = false" class="mt-4 text-sm font-medium text-sage hover:underline">Clear all filters</button>
        </div>
      </div>

      {{-- Pagination --}}
      <div class="flex justify-center mt-6 sm:mt-8 gap-2">
        <button type="button" @click="page--; fetchJobs()" :disabled="page === 1"
                class="glass px-4 sm:px-5 py-2.5 rounded-full text-sm font-medium disabled:opacity-40 hover:bg-forest/7 transition min-h-[44px] active:scale-[0.98]">
          ← Previous
        </button>
        <button type="button" @click="page++; fetchJobs()"
                class="glass px-4 sm:px-5 py-2.5 rounded-full text-sm font-medium hover:bg-forest/7 transition min-h-[44px] active:scale-[0.98]">
          Next →
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
