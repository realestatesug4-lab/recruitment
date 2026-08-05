<x-filament-widgets::widget>
    <div class="admin-card-padded">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <h3 class="admin-title">Search & indexing status</h3>
                <p class="admin-subtitle">Elasticsearch readiness and managed indices</p>
            </div>
            <div class="admin-badge-slate">Search layer</div>
        </div>

        <div class="mt-4 space-y-3 sm:mt-6 sm:space-y-4">
            <div class="admin-panel-surface">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <span class="font-semibold text-deep">Availability</span>
                    <span @class([
                        'admin-badge',
                        ($search['available'] ?? false)
                            ? 'bg-emerald-100 text-emerald-700'
                            : 'bg-amber-100 text-amber-700',
                    ])>
                        {{ ($search['available'] ?? false) ? 'Online' : 'Offline' }}
                    </span>
                </div>
                <div class="mt-2 text-sm text-text-mid">{{ $search['service'] ?? 'Search engine unavailable' }}</div>
            </div>

            @foreach ($search['indices'] ?? [] as $index)
                <div class="flex flex-col gap-1 rounded-2xl border border-slate-200/70 bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <span class="font-medium text-deep">{{ ucfirst($index['name']) }}</span>
                    <span class="text-sm text-sage">{{ $index['status'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
