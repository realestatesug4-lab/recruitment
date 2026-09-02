<?php

namespace App\Providers;

use App\Contracts\Advertising\AdServerInterface;
use App\Services\ReviveAdserverService;
use App\View\Components\SmartAdComponent;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AdServerInterface::class, ReviveAdserverService::class);
        $this->app->singleton(ReviveAdserverService::class, function () {
            return new ReviveAdserverService();
        });
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        config(['debugbar.enabled' => false]);

        Blade::component('smart-ad-component', SmartAdComponent::class);

        // Rate limiters
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('ai-tools', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }

}
