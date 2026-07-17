<?php

namespace App\Filament\Admin\Resources\Jobs\Widgets;

use Filament\Widgets\ChartWidget;

class StatsOverview extends ChartWidget
{
    protected ?string $heading = 'Stats Overview';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
