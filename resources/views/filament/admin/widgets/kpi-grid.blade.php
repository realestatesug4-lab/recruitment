<x-filament-widgets::widget class="admin-widget-flush">
    <section class="grid gap-3 sm:gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($kpis as $kpi)
            @php
                $toneClass = match ($kpi['tone'] ?? 'forest') {
                    'forest' => 'bg-forest/10 text-forest',
                    'mint' => 'bg-mint/10 text-sage',
                    'sage' => 'bg-sage/10 text-sage',
                    'amber' => 'bg-amber/10 text-amber-700',
                    'slate' => 'bg-slate-100 text-slate-700',
                    'deep' => 'bg-deep/10 text-deep',
                    default => 'bg-slate-100 text-slate-700',
                };
            @endphp
            <div class="admin-card admin-card-lift p-4 transition duration-300 sm:p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="admin-kicker">{{ $kpi['label'] }}</div>
                        <div class="admin-stat-value mt-2">{{ $kpi['value'] }}</div>
                    </div>
                    <div class="admin-badge {{ $toneClass }}">{{ $kpi['hint'] }}</div>
                </div>
            </div>
        @endforeach
    </section>
</x-filament-widgets::widget>
