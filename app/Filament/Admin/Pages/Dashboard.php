<?php

namespace App\Filament\Admin\Pages;

use App\ViewModels\AdminDashboardViewModel;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.admin-dashboard';

    public array $stats = [];

    public function mount(): void
    {
        $this->stats = (new AdminDashboardViewModel())->toArray();
    }
}
