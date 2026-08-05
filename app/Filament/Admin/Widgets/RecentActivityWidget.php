<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Concerns\CachesDashboardStats;
use Filament\Widgets\Widget;

class RecentActivityWidget extends Widget
{
    use CachesDashboardStats;

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected string $view = 'filament.admin.widgets.recent-activity';

    protected function getViewData(): array
    {
        return [
            'items' => $this->dashboardStats()['recent_activity'] ?? [],
        ];
    }
}
