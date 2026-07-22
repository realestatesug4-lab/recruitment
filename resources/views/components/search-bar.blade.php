@props(['placeholder' => 'Job title, company, or skill...'])

<div
    x-data="searchBar()"
    class="search-bar relative w-full max-w-6xl mx-auto mt-3"
>
    <div class="glass rounded-xl shadow-md overflow-hidden">
        {{-- Mobile: stacked layout --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:gap-0">
            <div class="flex items-center flex-1 min-w-0 px-4 py-3 sm:py-1.5 sm:px-5">
                <svg class="w-4 h-4 text-text-light flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <input
                    x-model="query"
                    @input.debounce.300ms="fetchSuggestions"
                    @keydown.enter="submit"
                    type="search"
                    inputmode="search"
                    placeholder="{{ $placeholder }}"
                    aria-label="Search jobs"
                    class="flex-1 min-w-0 ml-2.5 bg-transparent border-none outline-none text-sm text-deep placeholder-text-light font-dm focus:ring-0"
                >
            </div>

            <div class="hidden sm:block w-px h-7 bg-forest/10 mx-2 flex-shrink-0"></div>

            <div class="flex items-center justify-between gap-3 px-4 py-2.5 sm:py-0 sm:px-4 border-t sm:border-t-0 border-forest/8 bg-white/30 sm:bg-transparent">
                <div class="flex items-center gap-1.5 text-sm text-text-mid min-w-0">
                    <svg class="w-3.5 h-3.5 text-mint flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    <span class="truncate" x-text="location"></span>
                </div>
                <button
                    @click="submit"
                    type="button"
                    class="flex-shrink-0 bg-forest text-white rounded-full px-5 sm:px-7 py-2.5 sm:py-3 text-sm font-medium hover:bg-sage transition active:scale-[0.98] min-h-[44px]"
                >
                    Search Jobs
                </button>
            </div>
        </div>
    </div>

    <div
        x-show="suggestions.length > 0 && open"
        x-transition
        @click.outside="open = false"
        class="absolute top-full left-0 right-0 mt-2 glass rounded-lg overflow-hidden z-50 shadow-lg max-h-64 overflow-y-auto"
    >
        <template x-for="s in suggestions" :key="s.id">
            <div
                @click="selectSuggestion(s)"
                class="px-4 py-3.5 text-sm text-deep hover:bg-forest/6 cursor-pointer flex items-center gap-3 active:bg-forest/10 min-h-[44px]"
            >
                <svg class="w-3 h-3 text-text-light flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <span class="truncate" x-text="s.title"></span>
                <span x-text="s.company" class="ml-auto text-xs text-text-light truncate max-w-[40%]"></span>
            </div>
        </template>
    </div>
</div>
