<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Concerns\CachesDashboardStats;
use Filament\Widgets\Widget;

class ApplicationTrendsWidget extends Widget
{
    use CachesDashboardStats;

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected string $view = 'filament.admin.widgets.application-trends';

    protected function getViewData(): array
    {
        $trends = $this->dashboardStats()['trends'] ?? [];

        return [
            'labels' => $trends['labels'] ?? [],
            'series' => $trends['series'] ?? [],
        ];
    }
}
