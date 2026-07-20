<?php

namespace App\Filament\Admin\Widgets;

use App\ViewModels\AdminDashboardViewModel;
use Filament\Widgets\Widget;

class ExecutiveOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.executive-overview';

    public array $data = [];

    public function mount(): void
    {
        $this->data = (new AdminDashboardViewModel())->toArray();
    }
}
