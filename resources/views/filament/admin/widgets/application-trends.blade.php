<x-filament-widgets::widget>
    <div class="admin-card-padded">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <h3 class="admin-title">Application velocity</h3>
                <p class="admin-subtitle">Demand pulse across the last six months</p>
            </div>
            <div class="admin-badge-mint">Trend</div>
        </div>

        <div
            wire:ignore
            id="admin-trends-chart-{{ $this->getId() }}"
            class="admin-chart mt-4 h-56 w-full overflow-hidden rounded-2xl bg-slate-50/80 p-2 sm:mt-6 sm:h-72 sm:p-4"
        ></div>
    </div>

    @assets
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js" defer></script>
    @endassets

    @script
        <script>
            (function () {
                const renderChart = () => {
                    const element = document.getElementById('admin-trends-chart-{{ $this->getId() }}');

                    if (! element || typeof ApexCharts === 'undefined') {
                        return;
                    }

                    const chart = new ApexCharts(element, {
                        chart: {
                            type: 'area',
                            height: '100%',
                            toolbar: { show: false },
                            fontFamily: 'inherit',
                        },
                        series: [{ name: 'Applications', data: @js($series) }],
                        xaxis: { categories: @js($labels) },
                        stroke: { curve: 'smooth', width: 3 },
                        colors: ['#123aed'],
                        fill: {
                            type: 'gradient',
                            gradient: { shadeIntensity: 0.2, opacityFrom: 0.55, opacityTo: 0.08 },
                        },
                        tooltip: { theme: 'light' },
                        grid: { borderColor: 'rgba(2, 8, 33, 0.08)' },
                        dataLabels: { enabled: false },
                    });

                    chart.render();
                };

                if (typeof ApexCharts === 'undefined') {
                    window.addEventListener('load', renderChart, { once: true });
                } else {
                    renderChart();
                }
            })();
        </script>
    @endscript
</x-filament-widgets::widget>
