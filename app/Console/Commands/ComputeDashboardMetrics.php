<?php

namespace App\Console\Commands;

use App\Services\DashboardMetricsService;
use Illuminate\Console\Command;

class ComputeDashboardMetrics extends Command
{
    protected $signature = 'dashboard:compute-metrics {date?}';

    protected $description = 'Compute and persist admin dashboard metrics';

    public function handle(DashboardMetricsService $service): int
    {
        $service->storeSummaryMetrics($this->argument('date'));

        $this->info('Dashboard metrics computed successfully.');

        return self::SUCCESS;
    }
}
