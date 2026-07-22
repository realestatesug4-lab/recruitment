{{-- Shared filter panel — used in desktop sidebar and mobile collapsible drawer --}}
<div>
    <div class="flex items-center justify-between mb-4 sm:mb-5">
        <h3 class="font-syne font-bold text-deep text-base">Filters</h3>
        <button type="button" @click="clearAll()" class="text-xs text-sage hover:underline min-h-[44px] px-2 -mr-2">Clear all</button>
    </div>

    <div class="mb-5">
        <div class="text-xs font-semibold uppercase tracking-wider text-text-light mb-3">Type</div>
        @foreach(['Full-time','Part-time','Contract','Remote'] as $type)
        <label class="flex items-center gap-2.5 py-2 cursor-pointer group min-h-[44px]">
            <input type="checkbox"
                   @change="toggleFilter('type', '{{ $type }}')"
                   :checked="filters.type.includes('{{ $type }}')"
                   class="rounded border-forest/20 text-forest focus:ring-mint w-4 h-4">
            <span class="text-sm text-text-mid group-hover:text-deep transition">{{ $type }}</span>
        </label>
        @endforeach
    </div>

    <div class="mb-5">
        <div class="text-xs font-semibold uppercase tracking-wider text-text-light mb-3">Salary (UGX/mo)</div>
        <input type="range" min="0" max="5000000" step="100000"
               x-model="filters.salaryMax"
               @input.debounce.500ms="fetchJobs()"
               class="w-full accent-mint touch-manipulation">
        <div class="flex justify-between text-xs text-text-light mt-1">
            <span>0</span>
            <span x-text="(filters.salaryMax || 5000000).toLocaleString() + ' UGX'"></span>
        </div>
    </div>

    <label class="flex items-center gap-2.5 cursor-pointer group min-h-[44px]">
        <input type="checkbox" x-model="filters.remote" @change="fetchJobs()" class="rounded border-forest/20 text-forest focus:ring-mint w-4 h-4">
        <span class="text-sm text-text-mid group-hover:text-deep transition">Remote only</span>
    </label>
</div>
