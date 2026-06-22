<?php
namespace App\ViewModels;

use App\Domain\Companies\Models\Company;
use App\Domain\Jobs\Models\Job;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CompanyProfileViewModel
{
    public function __construct(public Company $company) {}

    public function name(): string { return $this->company->name; }
    public function openRolesCount(): int { return $this->company->jobs()->published()->count(); }

    public function summary(): array
    {
        return [
            'name' => $this->company->name,
            'description' => $this->company->description ?? 'A growing employer hiring talent across Uganda.',
            'industry' => $this->company->industry ?? 'General',
            'location' => $this->company->location ?? 'Uganda',
            'website' => $this->company->website,
            'color' => $this->company->color ?? '#1B4332',
            'initial' => Str::of($this->company->name)->substr(0, 1)->upper()->toString(),
            'verification_status' => ucfirst($this->company->verification_status ?? 'unverified'),
            'size' => $this->company->size ? number_format($this->company->size) . ' employees' : 'Team size not listed',
            'open_roles' => $this->openRolesCount(),
        ];
    }

    public function openRoles(): Collection
    {
        return $this->company->jobs()
            ->published()
            ->latest('published_at')
            ->limit(8)
            ->get()
            ->map(fn (Job $job): array => [
                'title' => $job->title,
                'slug' => $job->slug,
                'location' => $job->location ?? 'Uganda',
                'type' => match ($job->job_type->value) {
                    'full-time' => 'Full-time',
                    'contract' => 'Contract',
                    'remote' => 'Remote',
                    default => Str::of($job->job_type->value)->replace('-', ' ')->title()->toString(),
                },
                'posted_at' => $job->published_at?->diffForHumans() ?? 'Recently',
            ]);
    }
}
