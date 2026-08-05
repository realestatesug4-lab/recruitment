<x-filament-widgets::widget>
    <div class="admin-card-padded">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <h3 class="admin-title">Pipeline health</h3>
                <p class="admin-subtitle">Distribution of current applications</p>
            </div>
            <div class="admin-badge-forest">Stage</div>
        </div>

        <div class="mt-4 space-y-3 sm:mt-6 sm:space-y-4">
            @foreach ($pipeline as $stage)
                @php
                    $gradientClass = match ($stage['label'] ?? '') {
                        'Submitted' => 'from-amber-500 to-orange-400',
                        'Shortlisted' => 'from-sky-500 to-cyan-400',
                        'Interview' => 'from-violet-500 to-fuchsia-400',
                        'Hired' => 'from-emerald-500 to-lime-400',
                        'Rejected' => 'from-rose-500 to-red-400',
                        default => 'from-slate-500 to-slate-400',
                    };
                @endphp
                <div>
                    <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                        <span class="font-semibold text-deep">{{ $stage['label'] }}</span>
                        <span class="text-text-mid">{{ $stage['count'] }} · {{ $stage['percentage'] }}%</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                        <div
                            class="h-2 rounded-full bg-gradient-to-r {{ $gradientClass }} transition-all duration-500"
                            style="width: {{ $stage['percentage'] }}%"
                        ></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>
