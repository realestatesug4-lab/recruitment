<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

App::booted(function () {
    $schedule = app(Schedule::class);

    $schedule->command('dashboard:compute-metrics')->dailyAt('02:00');
    $schedule->command('revive:sync-zones')->dailyAt('01:00');
    $schedule->command('revive:sync-stats --days=1')->dailyAt('01:30');
    $schedule->command('revive:sync-stats --days=30')->dailyAt('02:30');
});
