<?php

namespace App\ViewModels;

use App\Domain\Jobs\Models\Job;
use Illuminate\Support\Str;

class HomeViewModel
{
    public function hero(): array
    {
        return [
            'label' => 'Live across Kampala, Entebbe, and Jinja',
            'headline' => 'Type it.',
            'highlight' => 'Land there.',
            'suffix' => 'Done.',
            'description' => "Uganda's fast, low-data way to jobs, companies, and services. No ad-choked detours, no wasted bundle, just the useful page.",
        ];
    }

    public function platformStats(): array
    {
        return [
            ['value' => '12400', 'label' => 'Live Listings', 'suffix' => '+'],
            ['value' => '0.4', 'label' => 'Second Resolve', 'suffix' => 's', 'float' => true],
            ['value' => '340', 'label' => 'Verified Employers', 'suffix' => '+'],
        ];
    }

    public function popularSearches(): array
    {
        return ['Accountant Kampala', 'Boda Services', 'Mobile Money Agent', 'NGO Jobs', 'Remote Work', 'Plumber Near Me'];
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
                'logo_bg' => 'rgba(18,58,237,0.10)',
                'logo_color' => $job->company?->color ?? '#123aed',
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
            ['icon' => 'JB', 'name' => 'Jobs', 'count' => '4,900'],
            ['icon' => 'SH', 'name' => 'Shops & Products', 'count' => '6,200'],
            ['icon' => 'SV', 'name' => 'Services', 'count' => '2,100'],
            ['icon' => 'RE', 'name' => 'Real Estate', 'count' => '890'],
            ['icon' => 'BD', 'name' => 'Boda Services', 'count' => '740'],
            ['icon' => 'MM', 'name' => 'Mobile Money', 'count' => '510'],
            ['icon' => 'NG', 'name' => 'NGO & Development', 'count' => '1,520'],
            ['icon' => 'HC', 'name' => 'Healthcare', 'count' => '980'],
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
