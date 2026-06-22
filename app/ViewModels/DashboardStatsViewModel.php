<?php
namespace App\ViewModels;

use Illuminate\Database\Eloquent\Collection;

class DashboardStatsViewModel
{
    public function __construct(
        public int $openJobs = 0,
        public int $totalApplications = 0,
        public ?Collection $recentApplications = null,
    ) {}

    public function jobsCount(): int { return $this->openJobs; }
    public function applicationsCount(): int { return $this->totalApplications; }
    public function recentApplications(): ?Collection { return $this->recentApplications; }
}
