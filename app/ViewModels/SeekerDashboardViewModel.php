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
    ) {}

    public function stats(): array
    {
        return [
            ['label' => 'Applications', 'value' => $this->applications->count(), 'hint' => $this->shortlistedCount().' shortlisted'],
            ['label' => 'Saved jobs', 'value' => $this->savedJobs->total(), 'hint' => 'Bookmarked roles'],
            ['label' => 'Profile', 'value' => $this->profileCompletion(), 'hint' => $this->profileStatus()],
        ];
    }

    public function profileCompletion(): int
    {
        if (! $this->profile) {
            return 24;
        }

        $fields = [
            $this->profile->headline,
            $this->profile->location,
            $this->profile->experience_level,
            $this->profile->bio,
        ];

        $filledCount = collect($fields)->filter(fn ($value) => filled($value))->count();

        return (int) round(($filledCount / count($fields)) * 100);
    }

    public function profileStatus(): string
    {
        return $this->profile ? 'Ready to apply' : 'Complete your profile';
    }

    public function hasProfile(): bool
    {
        return (bool) $this->profile;
    }

    public function shortlistedCount(): int
    {
        return $this->applications->where('status.value', 'shortlisted')->count();
    }

    public function recentApplications(): Collection
    {
        return $this->applications->take(5)->map(fn (Application $application) => [
            'title' => $application->job?->title ?? 'Unknown role',
            'company' => $application->job?->company?->name ?? 'Unknown company',
            'status' => Str::of($application->status->value)->replace('-', ' ')->title()->toString(),
            'when' => $application->created_at?->diffForHumans() ?? 'Just now',
            'url' => route('jobs.show', $application->job?->slug),
        ]);
    }

    public function quickActions(): array
    {
        return [
            ['label' => 'Browse jobs', 'url' => route('jobs.index'), 'icon' => '→'],
            ['label' => 'View applications', 'url' => route('seeker.applications.progress'), 'icon' => '≡'],
            ['label' => 'Edit profile', 'url' => $this->hasProfile() ? route('seeker.profile.edit') : route('seeker.profile.create'), 'icon' => '✎'],
        ];
    }

    public function statusLabelClass(string $status): string
    {
        return match (Str::lower($status)) {
            'submitted' => 'bg-blue-100 text-blue-700',
            'shortlisted' => 'bg-mint/10 text-sage',
            'interview' => 'bg-purple-100 text-purple-700',
            'hired' => 'bg-emerald-100 text-emerald-700',
            'rejected' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
