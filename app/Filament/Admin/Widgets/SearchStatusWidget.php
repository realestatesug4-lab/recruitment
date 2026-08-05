<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Concerns\CachesDashboardStats;
use Filament\Widgets\Widget;

class SearchStatusWidget extends Widget
{
    use CachesDashboardStats;

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected string $view = 'filament.admin.widgets.search-status';

    protected function getViewData(): array
    {
        return [
            'search' => $this->dashboardStats()['search'] ?? [],
        ];
    }
}
