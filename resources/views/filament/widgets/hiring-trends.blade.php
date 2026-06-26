<div class="p-4 bg-white/70 rounded-lg">
    <div id="hiring-trends-chart" style="height:220px"></div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const options = {
                chart: { type: 'area', height: 220, toolbar: { show: false } },
                series: [{ name: 'Applications', data: {{ json_encode($this->data) }} }],
                xaxis: { categories: {{ json_encode($this->labels) }}, labels: { style: { colors: '#374151' } } },
                stroke: { curve: 'smooth' },
                colors: ['#10B981'],
                tooltip: { theme: 'light' },
            };

            const chart = new ApexCharts(document.querySelector('#hiring-trends-chart'), options);
            chart.render();
        });
    </script>
</div>
