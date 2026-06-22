<?php

namespace App\Contracts;

use App\Domain\Jobs\Models\Job;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface JobRepositoryInterface
{
    public function find(int $id): ?Job;
    public function findBySlug(string $slug): ?Job;
    public function create(array $data): Job;
    public function update(Job $job, array $data): Job;
    public function delete(Job $job): bool;
    public function published(int $perPage = 15): LengthAwarePaginator;
    public function forCompany(int $companyId): Collection;
    public function featured(int $limit = 10): Collection;
}
