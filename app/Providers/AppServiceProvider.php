<?php

namespace App\Providers;

use App\View\Components\SmartAdComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Keep this EMPTY for Filament styling
        // Filament handles assets via Vite automatically
        Blade::component('smart-ad-component', SmartAdComponent::class);
    }
}
