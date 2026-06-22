<?php

namespace App\ViewModels;

use App\Domain\Jobs\Models\Job;
use Illuminate\Support\Collection;

class JobCardViewModel
{
    public function __construct(public Job $job) {}

    public function title(): string {
        return $this->job->title;
    }

    public function company(): string {
        return $this->job->company->name;
    }

    public function companyLogo(): ?string {
        return $this->job->company->logo_url;
    }

    public function salary(): string {
        if (!$this->job->salary_visible || !$this->job->salary_min) {
            return 'Negotiable';
        }
        return 'UGX ' . number_format($this->job->salary_min / 1000) . 'K'
             . ($this->job->salary_max
                ? ' – ' . number_format($this->job->salary_max / 1000) . 'K'
                : '+');
    }

    public function postedAgo(): string {
        return $this->job->published_at?->diffForHumans() ?? 'Just now';
    }

    public function typeBadgeColor(): string {
        return match($this->job->type->value) {
            'full-time' => 'mint',
            'contract'  => 'amber',
            'remote'    => 'blue',
            default     => 'gray',
        };
    }

    /** @param Collection<Job> $jobs */
    public static function collection(Collection $jobs): Collection {
        return $jobs->map(fn($job) => new self($job));
    }
}
