<?php

namespace App\ViewModels;

use App\Domain\Applications\Enums\ApplicationStatus;
use App\Domain\Applications\Models\Application;
use App\Domain\Companies\Models\Company;
use App\Domain\Jobs\Enums\JobStatus;
use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use App\Models\DashboardMetric;
use App\Services\Search\ElasticsearchIndexService;
use Illuminate\Support\Carbon;
use Throwable;

class AdminDashboardViewModel
{
    public function __construct(protected array $payload = []) {}

    public function toArray(): array
    {
        $metrics = $this->resolveMetrics();

        return [
            'kpis' => [
                [
                    'label' => 'Total jobs',
                    'value' => number_format($metrics['total_jobs']),
                    'hint' => 'All roles tracked',
                    'tone' => 'forest',
                ],
                [
                    'label' => 'Published jobs',
                    'value' => number_format($metrics['published_jobs']),
                    'hint' => 'Live and open',
                    'tone' => 'mint',
                ],
                [
                    'label' => 'Companies',
                    'value' => number_format($metrics['companies']),
                    'hint' => 'Active partners',
                    'tone' => 'sage',
                ],
                [
                    'label' => 'Users',
                    'value' => number_format($metrics['users']),
                    'hint' => 'Seekers and employers',
                    'tone' => 'amber',
                ],
                [
                    'label' => 'Applications',
                    'value' => number_format($metrics['applications']),
                    'hint' => 'Current submissions',
                    'tone' => 'slate',
                ],
                [
                    'label' => 'Hired',
                    'value' => number_format($metrics['hired_applications']),
                    'hint' => 'Successful placements',
                    'tone' => 'deep',
                ],
            ],
            'pipeline' => $this->pipeline(),
            'trends' => $this->trends(),
            'search' => $this->searchLayer(),
            'recent_activity' => $this->recentActivity(),
        ];
    }

    protected function resolveMetrics(): array
    {
        $base = [
            'total_jobs' => $this->safeCount(Job::query()),
            'published_jobs' => $this->safeCount(Job::query()->where('status', JobStatus::PUBLISHED->value)),
            'draft_jobs' => $this->safeCount(Job::query()->where('status', JobStatus::DRAFT->value)),
            'companies' => $this->safeCount(Company::query()),
            'users' => $this->safeCount(User::query()),
            'seekers' => $this->safeCount(User::query()->where('role', 'seeker')),
            'employers' => $this->safeCount(User::query()->where('role', 'employer')),
            'applications' => $this->safeCount(Application::query()),
            'submitted_applications' => $this->safeCount(Application::query()->where('status', ApplicationStatus::SUBMITTED->value)),
            'shortlisted_applications' => $this->safeCount(Application::query()->where('status', ApplicationStatus::SHORTLISTED->value)),
            'interview_applications' => $this->safeCount(Application::query()->where('status', ApplicationStatus::INTERVIEW->value)),
            'hired_applications' => $this->safeCount(Application::query()->where('status', ApplicationStatus::HIRED->value)),
            'rejected_applications' => $this->safeCount(Application::query()->where('status', ApplicationStatus::REJECTED->value)),
        ];

        try {
            $metric = DashboardMetric::query()
                ->where('metric_key', 'overview')
                ->latest('date')
                ->first();

            if ($metric?->payload) {
                return array_merge($base, $metric->payload);
            }
        } catch (Throwable) {
            // Ignore missing dashboard_metrics table or other database issues.
        }

        return array_merge($base, $this->payload);
    }

    protected function pipeline(): array
    {
        $statuses = [
            ['key' => ApplicationStatus::SUBMITTED->value, 'label' => 'Submitted', 'color' => 'from-amber-500 to-orange-400'],
            ['key' => ApplicationStatus::SHORTLISTED->value, 'label' => 'Shortlisted', 'color' => 'from-sky-500 to-cyan-400'],
            ['key' => ApplicationStatus::INTERVIEW->value, 'label' => 'Interview', 'color' => 'from-violet-500 to-fuchsia-400'],
            ['key' => ApplicationStatus::HIRED->value, 'label' => 'Hired', 'color' => 'from-emerald-500 to-lime-400'],
            ['key' => ApplicationStatus::REJECTED->value, 'label' => 'Rejected', 'color' => 'from-rose-500 to-red-400'],
        ];

        $total = max(1, $this->safeCount(Application::query()));

        return collect($statuses)->map(function (array $status) use ($total): array {
            $count = $this->safeCount(Application::query()->where('status', $status['key']));

            return [
                'label' => $status['label'],
                'count' => $count,
                'percentage' => (int) round(($count / $total) * 100),
                'color' => $status['color'],
            ];
        })->values()->all();
    }

    protected function trends(): array
    {
        $labels = [];
        $series = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('M Y');
            $series[] = $this->safeCount(
                Application::query()
                    ->where('created_at', '>=', $date->copy()->startOfMonth())
                    ->where('created_at', '<=', $date->copy()->endOfMonth())
            );
        }

        return [
            'labels' => $labels,
            'series' => $series,
        ];
    }

    protected function searchLayer(): array
    {
        $hosts = config('services.elasticsearch.hosts', []);
        $service = new ElasticsearchIndexService();
        $indices = ['jobs', 'companies', 'candidates', 'applications'];

        return [
            'available' => ! empty($hosts),
            'indices' => collect($indices)->map(fn (string $index) => [
                'name' => $index,
                'status' => $hosts ? 'Ready' : 'Offline',
            ])->values()->all(),
            'service' => method_exists($service, 'ensureIndices') ? 'Elasticsearch indexing enabled' : 'Search engine unavailable',
        ];
    }

    protected function recentActivity(): array
    {
        try {
            $applications = Application::query()->latest()->take(4)->get()->map(fn (Application $application) => [
                'type' => 'Application',
                'title' => $application->job?->title ?? 'Application',
                'meta' => $application->user?->name ?? 'Candidate',
                'value' => $application->status?->value ?? $application->status,
                'when' => $application->created_at?->diffForHumans() ?? 'recently',
                'url' => $this->safeRoute('employer.applications.show', $application->uuid ?? $application->id),
            ]);

            $jobs = Job::query()->latest()->take(3)->get()->map(fn (Job $job) => [
                'type' => 'Job',
                'title' => $job->title,
                'meta' => $job->company?->name ?? 'Company',
                'value' => $job->status?->value ?? $job->status,
                'when' => $job->created_at?->diffForHumans() ?? 'recently',
                'url' => $this->safeRoute('jobs.show', $job->slug),
            ]);

            $companies = Company::query()->latest()->take(3)->get()->map(fn (Company $company) => [
                'type' => 'Company',
                'title' => $company->name,
                'meta' => $company->industry ?? 'Partner',
                'value' => 'Verified',
                'when' => $company->created_at?->diffForHumans() ?? 'recently',
                'url' => $this->safeRoute('companies.show', $company->slug),
            ]);

            return $applications->concat($jobs)->concat($companies)->take(6)->values()->all();
        } catch (Throwable) {
            return [];
        }
    }

    protected function safeCount($query): int
    {
        try {
            return (int) $query->count();
        } catch (Throwable) {
            return 0;
        }
    }

    protected function safeRoute(string $name, mixed $parameter = null): string
    {
        try {
            return route($name, $parameter);
        } catch (Throwable) {
            return '#';
        }
    }
}
