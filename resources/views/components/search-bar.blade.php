@props(['placeholder' => 'Job title, company, or skill...'])

<div
    x-data="searchBar()"
    class="relative flex items-center gap-0 glass rounded-xl px-6 py-1.5 max-w-2xl shadow-md"
>
    <svg class="w-4 h-4 text-text-light flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <circle cx="11" cy="11" r="8"></circle>
        <path d="m21 21-4.35-4.35"></path>
    </svg>

    <input
        x-model="query"
        @input.debounce.300ms="fetchSuggestions"
        @keydown.enter="submit"
        type="text"
        placeholder="{{ $placeholder }}"
        class="flex-1 ml-2.5 bg-transparent border-none outline-none text-sm text-deep placeholder-text-light font-dm focus:ring-0"
    >

    <div
        x-show="suggestions.length > 0 && open"
        x-transition
        @click.outside="open = false"
        class="absolute top-full left-0 right-0 mt-2 glass rounded-lg overflow-hidden z-50"
    >
        <template x-for="s in suggestions" :key="s.id">
            <div
                @click="selectSuggestion(s)"
                class="px-4 py-3 text-sm text-deep hover:bg-forest/6 cursor-pointer flex items-center gap-3"
            >
                <svg class="w-3 h-3 text-text-light" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
                <span x-text="s.title"></span>
                <span x-text="s.company" class="ml-auto text-xs text-text-light"></span>
            </div>
        </template>
    </div>

    <div class="w-px h-7 bg-forest/10 mx-4"></div>

    <div class="flex items-center gap-1.5 text-sm text-text-mid whitespace-nowrap">
        <svg class="w-3.5 h-3.5 text-mint" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
            <circle cx="12" cy="10" r="3"></circle>
        </svg>
        <span x-text="location"></span>
    </div>

    <button @click="submit" class="ml-2.5 bg-forest text-white rounded-full px-7 py-3 text-sm font-medium hover:bg-sage transition hover:-translate-y-px hover:shadow-md">
        Search Jobs
    </button>
</div>
