<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Concerns\CachesDashboardStats;
use Filament\Widgets\Widget;

class DashboardHeroWidget extends Widget
{
    use CachesDashboardStats;

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.admin.widgets.dashboard-hero';

    protected function getViewData(): array
    {
        return [
            'stats' => $this->dashboardStats(),
        ];
    }
}
