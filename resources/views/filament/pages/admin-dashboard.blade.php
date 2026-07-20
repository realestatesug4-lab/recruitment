<div class="space-y-6">
    <section class="rounded-[28px] border border-slate-200/70 bg-gradient-to-br from-forest/10 via-white to-mint/10 p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-sage">Executive overview</p>
                <h2 class="mt-2 text-3xl font-syne font-bold text-deep">Administrator command center</h2>
                <p class="mt-2 text-sm leading-6 text-text-mid">Track hiring performance, evaluate pipeline health, monitor search readiness, and keep operations moving from a single analytics surface.</p>
            </div>
            <div class="rounded-2xl border border-white/70 bg-white/70 px-4 py-3 text-sm text-text-mid shadow-sm">
                <div class="font-semibold text-deep">Live signals</div>
                <div class="mt-1">Jobs, applications, companies, and search indices</div>
            </div>
        </div>

        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl border border-white/70 bg-white/70 p-4">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-text-light">Focus</div>
                <div class="mt-2 text-lg font-semibold text-deep">Hiring velocity</div>
            </div>
            <div class="rounded-2xl border border-white/70 bg-white/70 p-4">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-text-light">Signal</div>
                <div class="mt-2 text-lg font-semibold text-deep">Search layer is monitored</div>
            </div>
            <div class="rounded-2xl border border-white/70 bg-white/70 p-4">
                <div class="text-xs font-semibold uppercase tracking-[0.2em] text-text-light">Operations</div>
                <div class="mt-2 text-lg font-semibold text-deep">Recent activity is live</div>
            </div>
        </div>
    </section>

    <section class="grid gap-4 xl:grid-cols-3">
        @foreach(($stats['kpis'] ?? []) as $kpi)
            <div class="rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-text-light">{{ $kpi['label'] }}</div>
                        <div class="mt-2 text-3xl font-syne font-bold text-deep">{{ $kpi['value'] }}</div>
                    </div>
                    <div class="rounded-full bg-{{ $kpi['tone'] }}/10 px-3 py-1 text-xs font-semibold uppercase text-{{ $kpi['tone'] }}">{{ $kpi['hint'] }}</div>
                </div>
            </div>
        @endforeach
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.35fr_0.95fr]">
        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-deep">Application velocity</h3>
                    <p class="text-sm text-text-mid">Demand pulse across the last six months</p>
                </div>
                <div class="rounded-full bg-mint/10 px-3 py-1 text-xs font-semibold uppercase text-sage">Trend</div>
            </div>
            <div class="mt-6 h-72">
                <div id="admin-trends-chart"></div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-deep">Pipeline health</h3>
                    <p class="text-sm text-text-mid">Distribution of current applications</p>
                </div>
                <div class="rounded-full bg-forest/10 px-3 py-1 text-xs font-semibold uppercase text-forest">Stage</div>
            </div>
            <div class="mt-6 space-y-4">
                @foreach(($stats['pipeline'] ?? []) as $stage)
                    <div>
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="font-semibold text-deep">{{ $stage['label'] }}</span>
                            <span class="text-text-mid">{{ $stage['count'] }} · {{ $stage['percentage'] }}%</span>
                        </div>
                        <div class="h-2 rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-gradient-to-r {{ $stage['color'] }}" style="width: {{ $stage['percentage'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-deep">Search & indexing status</h3>
                    <p class="text-sm text-text-mid">Elasticsearch readiness and managed indices</p>
                </div>
                <div class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold uppercase text-slate-600">Search layer</div>
            </div>
            <div class="mt-6 space-y-4">
                <div class="rounded-2xl border border-slate-200/70 bg-slate-50 p-4">
                    <div class="flex items-center justify-between">
                        <span class="font-semibold text-deep">Availability</span>
                        <span class="rounded-full {{ ($stats['search']['available'] ?? false) ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} px-3 py-1 text-xs font-semibold uppercase">
                            {{ ($stats['search']['available'] ?? false) ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                    <div class="mt-2 text-sm text-text-mid">{{ $stats['search']['service'] ?? 'Search engine unavailable' }}</div>
                </div>
                @foreach(($stats['search']['indices'] ?? []) as $index)
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200/70 bg-white px-4 py-3">
                        <span class="font-medium text-deep">{{ ucfirst($index['name']) }}</span>
                        <span class="text-sm text-sage">{{ $index['status'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200/70 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-deep">Recent activity</h3>
                    <p class="text-sm text-text-mid">Latest jobs, applications, and companies</p>
                </div>
                <div class="rounded-full bg-forest/10 px-3 py-1 text-xs font-semibold uppercase text-forest">Operations</div>
            </div>
            <div class="mt-6 space-y-3">
                @foreach(($stats['recent_activity'] ?? []) as $item)
                    <a href="{{ $item['url'] ?? '#' }}" class="block rounded-2xl border border-slate-200/70 bg-slate-50 p-4 transition hover:border-forest/40 hover:bg-white">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-sm font-semibold text-deep">{{ $item['title'] }}</div>
                                <div class="mt-1 text-xs uppercase tracking-[0.2em] text-text-light">{{ $item['type'] }} · {{ $item['meta'] }}</div>
                            </div>
                            <div class="text-right text-xs text-text-mid">
                                <div class="font-semibold capitalize text-deep">{{ $item['value'] }}</div>
                                <div class="mt-1">{{ $item['when'] }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('admin-trends-chart');
        if (!container) {
            return;
        }

        const labels = @json($stats['trends']['labels'] ?? []);
        const series = @json($stats['trends']['series'] ?? []);

        const chart = new ApexCharts(container, {
            chart: { type: 'area', height: 260, toolbar: { show: false } },
            series: [{ name: 'Applications', data: series }],
            xaxis: { categories: labels },
            stroke: { curve: 'smooth', width: 3 },
            colors: ['#10B981'],
            fill: { type: 'gradient', gradient: { shadeIntensity: 0.2, opacityFrom: 0.6, opacityTo: 0.1 } },
            tooltip: { theme: 'light' },
            grid: { borderColor: '#E5E7EB' },
        });

        chart.render();
    });
</script>
