<x-filament-widgets::widget>
    <div class="admin-card-padded">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <h3 class="admin-title">Applications pipeline</h3>
                <p class="admin-subtitle">Latest candidates by stage</p>
            </div>
            <div class="admin-badge-mint">Kanban</div>
        </div>

        <div class="-mx-1 flex gap-3 overflow-x-auto px-1 pb-2 snap-x snap-mandatory md:mx-0 md:grid md:grid-cols-3 md:overflow-visible md:pb-0 lg:grid-cols-5">
            @foreach ($this->columns as $column)
                <div class="min-w-[17rem] shrink-0 snap-start rounded-2xl border border-slate-200/70 bg-slate-50/80 p-3 md:min-w-0">
                    <div class="mb-3 text-sm font-semibold text-deep">{{ $column['status'] }}</div>
                    <div class="space-y-3">
                        @forelse ($column['items'] as $item)
                            <a
                                href="{{ $item['url'] }}"
                                class="admin-interactive-row block bg-white text-sm text-deep no-underline"
                            >
                                <div class="truncate font-semibold">{{ $item['title'] }}</div>
                                <div class="mt-1 truncate text-xs text-text-mid">{{ $item['candidate'] }} · {{ $item['when'] }}</div>
                            </a>
                        @empty
                            <div class="rounded-xl border border-dashed border-slate-200 px-3 py-4 text-xs text-text-light">
                                No items in this stage
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
