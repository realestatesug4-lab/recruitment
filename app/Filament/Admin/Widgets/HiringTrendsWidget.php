<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Domain\Applications\Models\Application;
use Carbon\Carbon;

class HiringTrendsWidget extends Widget
{
    protected ?string $heading = 'Hiring Trends';
    protected string $view = 'filament.widgets.hiring-trends';

    public array $labels = [];
    public array $data = [];

    protected function setUp(): void
    {
        parent::setUp();

        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->format('M Y'));
        }

        $this->labels = $months->toArray();

        $counts = $months->map(fn($label) =>
            Application::whereBetween('created_at', [Carbon::parse($label)->startOfMonth(), Carbon::parse($label)->endOfMonth()])->count()
        );

        $this->data = $counts->toArray();
    }
}
