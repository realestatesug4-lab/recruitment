<?php

namespace App\ViewModels;

use App\Domain\Applications\Models\Application;
use App\Domain\Users\Models\SeekerProfile;
use App\Domain\Users\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeekerDashboardViewModel
{
    public function __construct(
        public User $user,
        public ?SeekerProfile $profile,
        public Collection $applications,
        public LengthAwarePaginator $savedJobs,
        public Collection $recommendedJobs = new Collection(),
    ) {}

    public function stats(): array
    {
        return [
            [
                'label' => 'Applications',
                'value' => $this->applications->count(),
                'hint'  => $this->shortlistedCount() . ' shortlisted',
                'icon'  => 'document',
                'color' => 'blue',
            ],
            [
                'label' => 'Saved jobs',
                'value' => $this->savedJobs->total(),
                'hint'  => 'Bookmarked roles',
                'icon'  => 'bookmark',
                'color' => 'amber',
            ],
            [
                'label' => 'Profile',
                'value' => $this->profileCompletion() . '%',
                'hint'  => $this->profileStatus(),
                'icon'  => 'user',
                'color'  => 'emerald',
            ],
        ];
    }

    public function profileCompletion(): int
    {
        if (! $this->profile) {
            return 15;
        }

        $fields = [
            $this->profile->headline,
            $this->profile->location,
            $this->profile->experience_level,
            $this->profile->bio,
            $this->profile->resume_url,
        ];

        $filledCount = collect($fields)->filter(fn ($value) => filled($value))->count();
        $skillBonus  = $this->profile->skills->isNotEmpty() ? 1 : 0;

        return (int) round((($filledCount + $skillBonus) / (count($fields) + 1)) * 100);
    }

    public function profileStatus(): string
    {
        if (! $this->profile) {
            return 'Complete your profile';
        }

        return $this->profileCompletion() >= 80 ? 'Strong profile' : 'Keep building';
    }

    public function hasProfile(): bool
    {
        return (bool) $this->profile;
    }

    public function shortlistedCount(): int
    {
        return $this->applications->filter(fn ($a) => in_array($a->status->value, ['shortlisted', 'interview']))->count();
    }

    public function recentApplications(): Collection
    {
        return $this->applications->take(5)->map(fn (Application $app) => [
            'uuid'    => $app->uuid,
            'title'   => $app->job?->title ?? 'Unknown role',
            'company' => $app->job?->company?->name ?? 'Unknown company',
            'status'  => Str::of($app->status->value)->replace('-', ' ')->title()->toString(),
            'when'    => $app->created_at?->diffForHumans() ?? 'Just now',
            'url'     => route('seeker.applications.show', $app->uuid),
            'job_url' => $app->job ? route('jobs.show', $app->job->slug) : '#',
            'score'   => $app->match_score,
        ]);
    }

    public function applicationTimeline(): Collection
    {
        return $this->applications->take(10)->map(fn (Application $app) => [
            'uuid'       => $app->uuid,
            'title'      => $app->job?->title ?? 'Role',
            'company'    => $app->job?->company?->name ?? 'Company',
            'status'     => $app->status->value,
            'applied_at' => $app->created_at,
            'history'    => $app->statusHistory->map(fn ($h) => [
                'from'  => $h->old_status,
                'to'    => $h->new_status,
                'note'  => $h->note,
                'when'  => $h->created_at?->diffForHumans(),
            ]),
        ]);
    }

    public function statusLabelClass(string $status): string
    {
        return match (Str::lower($status)) {
            'submitted'   => 'bg-blue-100 text-blue-700 ring-blue-600/20',
            'shortlisted' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
            'interview'   => 'bg-purple-100 text-purple-700 ring-purple-600/20',
            'hired'       => 'bg-green-100 text-green-700 ring-green-600/20',
            'rejected'    => 'bg-red-100 text-red-700 ring-red-600/20',
            default       => 'bg-gray-100 text-gray-700 ring-gray-600/20',
        };
    }
}
