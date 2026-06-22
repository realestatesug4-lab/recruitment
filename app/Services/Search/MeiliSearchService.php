<?php

namespace App\Services\Search;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\SearchServiceInterface;
use App\DTOs\SearchFilters;
use Illuminate\Support\Collection;
use App\Domain\Jobs\Models\Job;
use Illuminate\Support\Facades\Artisan;

class MeiliSearchService implements SearchServiceInterface
{
    public function searchJobs(SearchFilters $filters): LengthAwarePaginator {
        return Job::search($filters->keyword ?? '')
            ->where('status', 'published')
            ->when($filters->type,     fn($q) => $q->where('type', $filters->type))
            ->when($filters->location, fn($q) => $q->where('location', $filters->location))
            ->when($filters->remote,   fn($q) => $q->where('is_remote', true))
            ->paginate($filters->perPage);
    }

    public function suggest(string $query, int $limit = 5): Collection {
        return Job::search($query)->take($limit)->get()
            ->map(fn($job) => ['id' => $job->id, 'title' => $job->title, 'company' => $job->company->name]);
    }

    public function reindex(string $model): void {
        Artisan::call('scout:import', ['model' => $model]);
    }
}
