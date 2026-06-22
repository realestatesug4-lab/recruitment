<div wire:loading.class="opacity-60 pointer-events-none transition-opacity">

    {{-- Search input with Livewire binding --}}
    <div class="glass rounded-xl px-6 py-3 flex items-center gap-4 mb-6">
        <input wire:model.live.debounce.400ms="query"
               type="text"
               placeholder="Search jobs…"
               class="flex-1 bg-transparent text-sm outline-none font-dm text-deep placeholder-text-light">
        <div wire:loading wire:target="query">
            {{-- Spinner --}}
            <svg class="animate-spin w-4 h-4 text-mint" ...></svg>
        </div>
    </div>

    {{-- Results --}}
    <div class="space-y-3">
        @forelse($jobs as $job)
            <x-job-card :job="$job" />
        @empty
            <div class="glass rounded-2xl p-10 text-center text-text-light text-sm">
                No jobs found. Try different filters.
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $jobs->links('vendor.pagination.glass') }}
    </div>
</div>
