<?php

namespace App\Filament\Admin\Concerns;

use App\ViewModels\AdminDashboardViewModel;

trait CachesDashboardStats
{
    protected static ?array $cachedDashboardStats = null;

    protected function dashboardStats(): array
    {
        if (static::$cachedDashboardStats === null) {
            static::$cachedDashboardStats = (new AdminDashboardViewModel())->toArray();
        }

        return static::$cachedDashboardStats;
    }
}
