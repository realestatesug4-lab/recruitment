<?php

namespace App\Filament\Admin\Widgets;

use App\Models\DashboardMetric;
use Filament\Widgets\Widget;

class StatsOverview extends Widget
{
    protected string $view = 'filament.widgets.stats-overview';

    public array $stats = [];

    public function mount(): void
    {
        $metric = DashboardMetric::query()
            ->where('metric_key', 'overview')
            ->latest('date')
            ->first();

        $this->stats = $metric?->payload ?? [
            'total_jobs' => 0,
            'published_jobs' => 0,
            'applications' => 0,
            'hired_applications' => 0,
            'companies' => 0,
            'users' => 0,
        ];
    }
}
