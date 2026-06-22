<?php

namespace App\Repositories;

use App\Contracts\JobRepositoryInterface;
use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Enums\JobStatus;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class JobRepository implements JobRepositoryInterface
{
    public function find(int $id): ?Job {
        return Job::find($id);
    }

    public function findBySlug(string $slug): ?Job {
        return Job::where('slug', $slug)->first();
    }

    public function create(array $data): Job {
        return Job::create($data);
    }

    public function update(Job $job, array $data): Job {
        $job->update($data);
        return $job->fresh();
    }

    public function delete(Job $job): bool {
        return $job->delete();
    }

    public function published(int $perPage = 15): LengthAwarePaginator {
        return Job::where('status', JobStatus::PUBLISHED)
            ->whereNotNull('published_at')
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function forCompany(int $companyId): Collection {
        return Job::where('company_id', $companyId)->latest()->get();
    }

    public function featured(int $limit = 10): Collection {
        return Job::where('status', JobStatus::PUBLISHED)
            ->where('is_featured', true)
            ->with('company')
            ->take($limit)
            ->get();
    }
}
