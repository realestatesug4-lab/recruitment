<?php

namespace App\ViewModels;

use App\Domain\Jobs\Models\Job;
use Illuminate\Support\Str;

class HomeViewModel
{
    public function hero(): array
    {
        return [
            'label' => "Uganda's #1 Job Platform",
            'headline' => 'Find Your',
            'highlight' => 'Next Role',
            'suffix' => 'in Uganda',
            'description' => 'Connect with top employers across Kampala, Entebbe, and beyond. Thousands of vetted opportunities, one platform.',
        ];
    }

    public function platformStats(): array
    {
        return [
            ['value' => '12,400+', 'label' => 'Active Jobs'],
            ['value' => '3,200+', 'label' => 'Companies'],
            ['value' => '89K+', 'label' => 'Job Seekers'],
        ];
    }

    public function popularSearches(): array
    {
        return ['Software Engineer', 'Finance', 'Marketing', 'NGO & Development', 'Healthcare', 'Remote'];
    }

    public function latestJobs(): array
    {
        return Job::published()
            ->with('company')
            ->latest('published_at')
            ->limit(5)
            ->get()
            ->map(fn (Job $job): array => [
                'title' => $job->title,
                'company' => $job->company?->name ?? 'Unknown company',
                'location' => $job->location ?? 'Uganda',
                'type' => match ($job->job_type->value) {
                    'full-time' => 'Full-time',
                    'contract' => 'Contract',
                    'remote' => 'Remote',
                    default => Str::of($job->job_type->value)->replace('-', ' ')->title()->toString(),
                },
                'badge_class' => match ($job->job_type->value) {
                    'full-time' => 'badge-green',
                    'contract' => 'badge-amber',
                    'remote' => 'badge-blue',
                    default => 'badge-green',
                },
                'logo_bg' => 'rgba(82,183,136,0.1)',
                'logo_color' => $job->company?->color ?? '#1B4332',
                'initial' => Str::of($job->company?->name ?? $job->title)->substr(0, 1)->upper()->toString(),
            ])
            ->all();
    }

    public function featuredCompanies(): array
    {
        return [
            [
                'initial' => 'M',
                'name' => 'MTN Uganda',
                'industry' => 'Telecommunications',
                'location' => 'Kampala',
                'open_roles' => '14',
                'employees' => '5K+',
                'rating' => '4.2',
                'founded' => "'98",
                'accent' => '#FFCC00',
            ],
            [
                'initial' => 'S',
                'name' => 'Stanbic Bank',
                'industry' => 'Finance & Banking',
                'location' => 'Kampala',
                'open_roles' => '9',
                'employees' => '2K+',
                'rating' => '4.6',
                'founded' => "'02",
                'accent' => '#0066B3',
            ],
        ];
    }

    public function categories(): array
    {
        return [
            ['icon' => 'IT', 'name' => 'Technology', 'count' => '2,140'],
            ['icon' => 'FB', 'name' => 'Finance & Banking', 'count' => '1,830'],
            ['icon' => 'NG', 'name' => 'NGO & Development', 'count' => '1,520'],
            ['icon' => 'HC', 'name' => 'Healthcare', 'count' => '980'],
            ['icon' => 'MK', 'name' => 'Marketing', 'count' => '760'],
            ['icon' => 'ED', 'name' => 'Education', 'count' => '640'],
            ['icon' => 'EU', 'name' => 'Energy & Utilities', 'count' => '420'],
            ['icon' => 'CN', 'name' => 'Construction', 'count' => '380'],
        ];
    }

    public function trustedCompanies(): array
    {
        return [
            ['name' => 'MTN Uganda', 'dot' => '#FFCC00'],
            ['name' => 'Airtel Uganda', 'dot' => '#E40000'],
            ['name' => 'Stanbic Bank', 'dot' => '#002D62'],
            ['name' => 'UNICEF', 'dot' => '#009EDB'],
            ['name' => 'NITA-U', 'dot' => '#1A9C3E'],
            ['name' => 'Dfcu Bank', 'dot' => '#FF6900'],
            ['name' => 'Makerere Uni', 'dot' => '#8B5CF6'],
        ];
    }
}
