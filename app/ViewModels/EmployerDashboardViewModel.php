<?php

namespace App\ViewModels;

use App\Domain\Applications\Models\Application;
use App\Domain\Companies\Models\Company;
use Illuminate\Support\Collection;

class EmployerDashboardViewModel
{
    public function __construct(
        public Company $company,
        public int $openJobs,
        public int $draftJobs,
        public int $totalApplications,
        public Collection $recentApplications,
    ) {}

    public function stats(): array
    {
        return [
            ['label' => 'Open jobs', 'value' => $this->openJobs, 'hint' => 'Published roles'],
            ['label' => 'Draft jobs', 'value' => $this->draftJobs, 'hint' => 'Needs review'],
            ['label' => 'Applications', 'value' => $this->totalApplications, 'hint' => 'Across all roles'],
        ];
    }

    public function recentApplicationCards(): Collection
    {
        return $this->recentApplications->map(fn (Application $application): array => [
            'candidate' => $application->seekerProfile?->name ?? 'Candidate',
            'job' => $application->job?->title ?? 'Role',
            'status' => ucfirst($application->status->value),
            'applied_at' => $application->created_at?->diffForHumans() ?? 'Just now',
            'score' => $application->match_score,
        ]);
    }
}
