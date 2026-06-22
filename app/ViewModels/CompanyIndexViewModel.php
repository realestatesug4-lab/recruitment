<?php

namespace App\ViewModels;

use App\Domain\Companies\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CompanyIndexViewModel
{
    public function __construct(public LengthAwarePaginator $companies) {}

    public function cards(): Collection
    {
        return $this->companies->getCollection()->map(fn (Company $company): array => [
            'name' => $company->name,
            'slug' => $company->slug,
            'description' => Str::limit($company->description ?? 'Building teams across Uganda.', 120),
            'industry' => $company->industry ?? 'General',
            'location' => $company->location ?? 'Uganda',
            'color' => $company->color ?? '#1B4332',
            'initial' => Str::of($company->name)->substr(0, 1)->upper()->toString(),
            'open_roles' => $company->jobs_count ?? 0,
            'is_verified' => $company->verification_status === 'verified',
        ]);
    }

    public function totalCompanies(): int
    {
        return $this->companies->total();
    }

    public function openRoles(): int
    {
        return $this->cards()->sum('open_roles');
    }

    public function industries(): int
    {
        return $this->cards()->pluck('industry')->filter()->unique()->count();
    }
}
