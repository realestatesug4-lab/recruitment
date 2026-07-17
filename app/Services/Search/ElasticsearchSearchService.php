<?php

namespace App\Services\Search;

use App\Contracts\SearchServiceInterface;
use App\DTOs\SearchFilters;
use App\Domain\Jobs\Models\Job;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ElasticsearchSearchService implements SearchServiceInterface
{
    protected ?Client $client = null;

    public function __construct()
    {
        $this->client = $this->buildClient();
    }

    public function buildSearchPayload(SearchFilters $filters): array
    {
        $must = [];
        if (!empty($filters->keyword)) {
            $must[] = [
                'multi_match' => [
                    'query' => $filters->keyword,
                    'fields' => ['title^4', 'description^2', 'location^2', 'company.name^3'],
                    'type' => 'best_fields',
                ],
            ];
        }

        $filter = [];
        if (!empty($filters->jobType)) {
            $filter[] = ['term' => ['job_type.keyword' => $filters->jobType]];
        }

        if (!empty($filters->location)) {
            $filter[] = ['term' => ['location.keyword' => $filters->location]];
        }

        if ($filters->remote) {
            $filter[] = ['term' => ['is_remote' => true]];
        }

        $query = [
            'bool' => array_filter([
                'must' => $must ?: null,
                'filter' => $filter ?: null,
            ], static fn ($value) => $value !== null),
        ];

        return [
            'index' => config('services.elasticsearch.index', 'job_posts'),
            'query' => $query,
            'body' => [
                'query' => $query,
                'size' => max(1, $filters->perPage),
                'from' => 0,
            ],
        ];
    }

    public function searchJobs(SearchFilters $filters): LengthAwarePaginator
    {
        if ($this->client !== null) {
            try {
                $payload = $this->buildSearchPayload($filters);
                $response = $this->client->search($payload);
                $ids = collect($response['hits']['hits'] ?? [])
                    ->pluck('_id')
                    ->filter()
                    ->values()
                    ->all();

                if (!empty($ids)) {
                    $query = Job::query()->published()->with('company')->whereIn('uuid', $ids);

                    return $this->paginateFromIds($query, $ids, $filters->perPage);
                }
            } catch (\Throwable) {
                // Fall back to the database query if Elasticsearch is unavailable.
            }
        }

        return $this->searchLocally($filters);
    }

    public function suggest(string $query, int $limit = 5): Collection
    {
        $filters = new SearchFilters(keyword: $query, perPage: $limit);

        if ($this->client !== null) {
            try {
                $payload = $this->buildSearchPayload($filters);
                $response = $this->client->search($payload);
                $hits = collect($response['hits']['hits'] ?? []);

                if ($hits->isNotEmpty()) {
                    $jobIds = $hits->pluck('_id')->filter()->values()->all();

                    return Job::query()
                        ->published()
                        ->with('company')
                        ->whereIn('uuid', $jobIds)
                        ->get()
                        ->map(fn (Job $job) => [
                            'id' => $job->uuid,
                            'title' => $job->title,
                            'company' => $job->company?->name,
                        ]);
                }
            } catch (\Throwable) {
                // Fall back to the database query if Elasticsearch is unavailable.
            }
        }

        return Job::query()
            ->published()
            ->with('company')
            ->when($query !== '', fn ($q) => $q->where(function ($sub) use ($query) {
                $sub->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            }))
            ->limit($limit)
            ->get()
            ->map(fn (Job $job) => [
                'id' => $job->uuid,
                'title' => $job->title,
                'company' => $job->company?->name,
            ]);
    }

    public function reindex(string $model): void
    {
        if ($model !== Job::class && !is_subclass_of($model, Job::class)) {
            return;
        }

        $this->reindexAllJobs();
    }

    public function reindexAllJobs(): void
    {
        if ($this->client === null) {
            return;
        }

        try {
            $jobs = Job::query()->published()->get();

            foreach ($jobs as $job) {
                $this->indexJob($job);
            }
        } catch (\Throwable) {
            // Ignore index failures so queue listeners remain resilient.
        }
    }

    public function indexJob(Job $job): void
    {
        if ($this->client === null) {
            return;
        }

        try {
            $this->client->index([
                'index' => config('services.elasticsearch.index', 'job_posts'),
                'id' => $job->uuid,
                'body' => [
                    'title' => $job->title,
                    'description' => $job->description,
                    'location' => $job->location,
                    'job_type' => $job->job_type?->value ?? $job->job_type,
                    'company' => [
                        'name' => $job->company?->name,
                    ],
                    'status' => $job->status?->value ?? $job->status,
                    'published_at' => $job->published_at?->toAtomString(),
                    'salary_max' => $job->salary_max,
                    'is_remote' => $this->hasRemoteColumn() ? (bool) $job->is_remote : false,
                ],
            ]);
        } catch (\Throwable) {
            // Ignore index failures so queue listeners remain resilient.
        }
    }

    protected function buildClient(): ?Client
    {
        $hosts = config('services.elasticsearch.hosts', ['http://127.0.0.1:9200']);

        if (empty($hosts)) {
            return null;
        }

        return ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }

    protected function searchLocally(SearchFilters $filters): LengthAwarePaginator
    {
        $query = Job::query()
            ->published()
            ->with('company');

        if (!empty($filters->keyword)) {
            $query->where(function ($sub) use ($filters) {
                $sub->where('title', 'like', "%{$filters->keyword}%")
                    ->orWhere('description', 'like', "%{$filters->keyword}%")
                    ->orWhere('location', 'like', "%{$filters->keyword}%");
            });
        }

        if (!empty($filters->jobType)) {
            $query->where('job_type', $filters->jobType);
        }

        if (!empty($filters->location)) {
            $query->where('location', 'like', "%{$filters->location}%" );
        }

        if ($filters->remote && $this->hasRemoteColumn()) {
            $query->where('is_remote', true);
        }

        return $query->paginate($filters->perPage);
    }

    protected function paginateFromIds($query, array $ids, int $perPage): LengthAwarePaginator
    {
        $page = (int) request()->query('page', 1);
        $ordered = $query->get()->sortBy(function (Job $job) use ($ids) {
            return array_search($job->uuid, $ids, true);
        })->values();

        $items = $ordered->forPage($page, $perPage)->values();
        $total = $ordered->count();

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->getRequestUri()]
        );
    }

    protected function hasRemoteColumn(): bool
    {
        return Schema::hasColumn('job_posts', 'is_remote');
    }
}
