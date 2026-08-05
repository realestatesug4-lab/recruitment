<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Concerns\CachesDashboardStats;
use Filament\Widgets\Widget;

class PipelineHealthWidget extends Widget
{
    use CachesDashboardStats;

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 1,
    ];

    protected string $view = 'filament.admin.widgets.pipeline-health';

    protected function getViewData(): array
    {
        return [
            'pipeline' => $this->dashboardStats()['pipeline'] ?? [],
        ];
    }
}
