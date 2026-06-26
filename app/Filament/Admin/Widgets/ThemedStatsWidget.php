<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Domain\Users\Models\User;
use App\Domain\Jobs\Models\Job;
use App\Domain\Applications\Models\Application;

class ThemedStatsWidget extends Widget
{
    protected ?string $heading = 'Platform Overview';
    protected string $view = 'filament.widgets.themed-stats';

    public ?int $users = 0;
    public ?int $jobs = 0;
    public ?int $applications = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = User::count();
        $this->jobs = Job::where('status', 'published')->count();
        $this->applications = Application::count();
    }
}
