<?php

namespace App\Services;

use App\Domain\Applications\Enums\ApplicationStatus;
use App\Domain\Applications\Models\Application;
use App\Domain\Companies\Models\Company;
use App\Domain\Jobs\Enums\JobStatus;
use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use App\Models\DashboardMetric;
use Illuminate\Support\Carbon;

class DashboardMetricsService
{
    public function storeSummaryMetrics(?string $date = null): DashboardMetric
    {
        $date = $date ? Carbon::parse($date)->toDateString() : Carbon::today()->toDateString();

        $payload = [
            'total_jobs' => Job::query()->count(),
            'published_jobs' => Job::query()->where('status', JobStatus::PUBLISHED->value)->count(),
            'draft_jobs' => Job::query()->where('status', JobStatus::DRAFT->value)->count(),
            'companies' => Company::query()->count(),
            'users' => User::query()->count(),
            'seekers' => User::query()->where('role', 'seeker')->count(),
            'employers' => User::query()->where('role', 'employer')->count(),
            'applications' => Application::query()->count(),
            'submitted_applications' => Application::query()->where('status', ApplicationStatus::SUBMITTED->value)->count(),
            'shortlisted_applications' => Application::query()->where('status', ApplicationStatus::SHORTLISTED->value)->count(),
            'interview_applications' => Application::query()->where('status', ApplicationStatus::INTERVIEW->value)->count(),
            'hired_applications' => Application::query()->where('status', ApplicationStatus::HIRED->value)->count(),
            'rejected_applications' => Application::query()->where('status', ApplicationStatus::REJECTED->value)->count(),
        ];

        return DashboardMetric::updateOrCreate(
            ['metric_key' => 'overview', 'period' => 'daily', 'date' => $date],
            ['payload' => $payload]
        );
    }
}
