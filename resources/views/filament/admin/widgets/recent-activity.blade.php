<x-filament-widgets::widget>
    <div class="admin-card-padded">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <h3 class="admin-title">Recent activity</h3>
                <p class="admin-subtitle">Latest jobs, applications, and companies</p>
            </div>
            <div class="admin-badge-forest">Operations</div>
        </div>

        <div class="mt-4 space-y-3 sm:mt-6">
            @forelse ($items as $item)
                <a href="{{ $item['url'] ?? '#' }}" class="admin-interactive-row">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <div class="truncate text-sm font-semibold text-deep">{{ $item['title'] }}</div>
                            <div class="mt-1 text-xs uppercase tracking-[0.2em] text-text-light">
                                {{ $item['type'] }} · {{ $item['meta'] }}
                            </div>
                        </div>
                        <div class="shrink-0 text-left text-xs text-text-mid sm:text-right">
                            <div class="font-semibold capitalize text-deep">{{ $item['value'] }}</div>
                            <div class="mt-1">{{ $item['when'] }}</div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="admin-panel-surface text-sm text-text-mid">No recent activity yet.</div>
            @endforelse
        </div>
    </div>
</x-filament-widgets::widget>
