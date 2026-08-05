<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Concerns\CachesDashboardStats;
use Filament\Widgets\Widget;

class KpiGridWidget extends Widget
{
    use CachesDashboardStats;

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.kpi-grid';

    protected function getViewData(): array
    {
        return [
            'kpis' => $this->dashboardStats()['kpis'] ?? [],
        ];
    }
}
