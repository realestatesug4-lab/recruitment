<?php

namespace App\Providers;

use App\View\Components\SmartAdComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $appUrl = self::normalizeAppUrl(
            (string) config('app.url', 'http://localhost'),
            $this->app->environment()
        );

        config(['app.url' => $appUrl]);
        URL::forceRootUrl($appUrl);

        if ($this->app->environment('production') || $this->app->request->isSecure()) {
            URL::forceScheme('https');
        }

        if ($this->app->environment('production') || !config('app.debug', false)) {
            config(['debugbar' => ['enabled' => false]]);
        }

        // Keep this EMPTY for Filament styling
        // Filament handles assets via Vite automatically
        Blade::component('smart-ad-component', SmartAdComponent::class);
    }

    public static function normalizeAppUrl(string $appUrl, ?string $environment = null): string
    {
        $appUrl = trim($appUrl);

        if ($appUrl === '') {
            return 'http://localhost';
        }

        if (!preg_match('#^https?://#i', $appUrl)) {
            $scheme = ($environment === 'production') ? 'https://' : 'http://';
            $appUrl = $scheme . $appUrl;
        }

        return rtrim($appUrl, '/');
    }
}
