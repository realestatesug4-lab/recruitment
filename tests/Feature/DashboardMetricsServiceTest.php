<?php

namespace Tests\Feature;

use App\Console\Commands\ComputeDashboardMetrics;
use App\Domain\Applications\Enums\ApplicationStatus;
use App\Domain\Applications\Models\Application;
use App\Domain\Companies\Models\Company;
use App\Domain\Jobs\Enums\JobStatus;
use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use App\Models\DashboardMetric;
use App\Services\DashboardMetricsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardMetricsServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_persists_summary_metrics_for_the_admin_dashboard(): void
    {
        $company = Company::factory()->create();
        User::factory()->create(['role' => 'employer']);
        $seeker = User::factory()->create(['role' => 'seeker']);

        $draftJob = Job::factory()->create([
            'company_id' => $company->id,
            'status' => JobStatus::DRAFT,
        ]);

        $publishedJob = Job::factory()->create([
            'company_id' => $company->id,
            'status' => JobStatus::PUBLISHED,
        ]);

        Application::factory()->create([
            'job_id' => $draftJob->id,
            'seeker_id' => $seeker->id,
            'status' => ApplicationStatus::SUBMITTED,
        ]);

        Application::factory()->create([
            'job_id' => $publishedJob->id,
            'seeker_id' => $seeker->id,
            'status' => ApplicationStatus::HIRED,
        ]);

        $service = new DashboardMetricsService();
        $service->storeSummaryMetrics('2026-06-27');

        $this->assertDatabaseHas('dashboard_metrics', [
            'metric_key' => 'overview',
            'period' => 'daily',
            'date' => '2026-06-27',
        ]);

        $metric = DashboardMetric::query()->where('metric_key', 'overview')->first();
        $this->assertSame(2, $metric->payload['total_jobs']);
        $this->assertSame(1, $metric->payload['published_jobs']);
        $this->assertSame(1, $metric->payload['hired_applications']);
        $this->assertSame(1, $metric->payload['companies']);
    }
}
