<?php

namespace App\Services\Search;

use App\Domain\Applications\Models\Application;
use App\Domain\Companies\Models\Company;
use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\SeekerProfile;
use App\Domain\Users\Models\User;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class ElasticsearchIndexService
{
    protected ?Client $client = null;

    public function __construct()
    {
        $this->client = $this->buildClient();
    }

    public function ensureIndices(): void
    {
        if ($this->client === null) {
            return;
        }

        $this->client->indices()->create([
            'index' => 'jobs',
            'body' => $this->mapping('jobs'),
        ], ['ignore' => [400]]);

        $this->client->indices()->create([
            'index' => 'companies',
            'body' => $this->mapping('companies'),
        ], ['ignore' => [400]]);

        $this->client->indices()->create([
            'index' => 'candidates',
            'body' => $this->mapping('candidates'),
        ], ['ignore' => [400]]);

        $this->client->indices()->create([
            'index' => 'applications',
            'body' => $this->mapping('applications'),
        ], ['ignore' => [400]]);
    }

    public function indexJob(Job $job): void
    {
        if ($this->client === null) {
            return;
        }

        $this->client->index([
            'index' => 'jobs',
            'id' => $job->uuid,
            'body' => [
                'document_type' => 'job',
                'id' => $job->uuid,
                'title' => $job->title,
                'description' => $job->description,
                'location' => $job->location,
                'job_type' => $job->job_type?->value ?? $job->job_type,
                'experience_level' => $job->experience_level?->value ?? $job->experience_level,
                'company' => [
                    'id' => $job->company?->uuid,
                    'name' => $job->company?->name,
                    'industry' => $job->company?->industry,
                    'location' => $job->company?->location,
                ],
                'status' => $job->status?->value ?? $job->status,
                'salary_min' => $job->salary_min,
                'salary_max' => $job->salary_max,
                'published_at' => $job->published_at?->toIso8601String(),
                'is_remote' => $this->hasRemoteColumn() ? (bool) $job->is_remote : false,
                'skills' => $job->skills()->pluck('name')->all(),
                'categories' => $job->categories()->pluck('name')->all(),
                'created_at' => $job->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function indexCompany(Company $company): void
    {
        if ($this->client === null) {
            return;
        }

        $this->client->index([
            'index' => 'companies',
            'id' => $company->uuid,
            'body' => [
                'document_type' => 'company',
                'id' => $company->uuid,
                'name' => $company->name,
                'description' => $company->description,
                'industry' => $company->industry,
                'location' => $company->location,
                'verification_status' => $company->verification_status,
                'is_featured' => (bool) $company->is_featured,
                'size' => $company->size,
                'website' => $company->website,
                'slug' => $company->slug,
                'open_roles' => $company->jobs()->published()->count(),
                'created_at' => $company->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function indexCandidate(User $user): void
    {
        if ($this->client === null) {
            return;
        }

        $profile = $user->seekerProfile;
        $this->client->index([
            'index' => 'candidates',
            'id' => $user->uuid,
            'body' => [
                'document_type' => 'candidate',
                'id' => $user->uuid,
                'name' => $user->name,
                'email' => $user->email,
                'headline' => $profile?->headline,
                'bio' => $profile?->bio,
                'location' => $profile?->location,
                'experience_level' => $profile?->experience_level,
                'skills' => $profile?->skills()->pluck('name')->all() ?? [],
                'created_at' => $user->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function indexApplication(Application $application): void
    {
        if ($this->client === null) {
            return;
        }

        $this->client->index([
            'index' => 'applications',
            'id' => $application->uuid,
            'body' => [
                'document_type' => 'application',
                'id' => $application->uuid,
                'job' => [
                    'id' => $application->job?->uuid,
                    'title' => $application->job?->title,
                    'company' => $application->job?->company?->name,
                ],
                'candidate' => [
                    'id' => $application->user?->uuid,
                    'name' => $application->user?->name,
                    'email' => $application->user?->email,
                ],
                'status' => $application->status?->value ?? $application->status,
                'match_score' => (float) ($application->match_score ?? 0),
                'applied_at' => $application->applied_at?->toIso8601String() ?? $application->created_at?->toIso8601String(),
                'created_at' => $application->created_at?->toIso8601String(),
            ],
        ]);
    }

    public function search(string $index, string $keyword, array $filters = []): Collection
    {
        if ($this->client === null) {
            return new Collection();
        }

        $query = [
            'bool' => [
                'must' => !empty($keyword) ? [[
                    'multi_match' => [
                        'query' => $keyword,
                        'fields' => $this->defaultFields($index),
                        'type' => 'best_fields',
                    ],
                ]] : [],
            ],
        ];

        foreach ($filters as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $query['bool']['filter'][] = ['term' => [$field => $value]];
        }

        $response = $this->client->search([
            'index' => $index,
            'body' => [
                'query' => $query,
                'size' => 12,
            ],
        ]);

        return collect($response['hits']['hits'] ?? [])
            ->map(fn (array $hit) => $hit['_source']);
    }

    protected function mapping(string $index): array
    {
        return [
            'mappings' => [
                'properties' => match ($index) {
                    'jobs' => [
                        'title' => ['type' => 'text', 'analyzer' => 'standard'],
                        'description' => ['type' => 'text'],
                        'location' => ['type' => 'keyword'],
                        'job_type' => ['type' => 'keyword'],
                        'experience_level' => ['type' => 'keyword'],
                        'company' => ['type' => 'object'],
                        'status' => ['type' => 'keyword'],
                        'salary_min' => ['type' => 'integer'],
                        'salary_max' => ['type' => 'integer'],
                        'published_at' => ['type' => 'date'],
                        'is_remote' => ['type' => 'boolean'],
                        'skills' => ['type' => 'keyword'],
                        'categories' => ['type' => 'keyword'],
                    ],
                    'companies' => [
                        'name' => ['type' => 'text'],
                        'description' => ['type' => 'text'],
                        'industry' => ['type' => 'keyword'],
                        'location' => ['type' => 'keyword'],
                        'verification_status' => ['type' => 'keyword'],
                        'is_featured' => ['type' => 'boolean'],
                        'size' => ['type' => 'integer'],
                        'website' => ['type' => 'keyword'],
                        'slug' => ['type' => 'keyword'],
                        'open_roles' => ['type' => 'integer'],
                    ],
                    'candidates' => [
                        'name' => ['type' => 'text'],
                        'email' => ['type' => 'keyword'],
                        'headline' => ['type' => 'text'],
                        'bio' => ['type' => 'text'],
                        'location' => ['type' => 'keyword'],
                        'experience_level' => ['type' => 'keyword'],
                        'skills' => ['type' => 'keyword'],
                    ],
                    'applications' => [
                        'job' => ['type' => 'object'],
                        'candidate' => ['type' => 'object'],
                        'status' => ['type' => 'keyword'],
                        'match_score' => ['type' => 'float'],
                        'applied_at' => ['type' => 'date'],
                    ],
                    default => [],
                },
            ],
        ];
    }

    protected function defaultFields(string $index): array
    {
        return match ($index) {
            'jobs' => ['title^4', 'description^2', 'company.name^3', 'location^2', 'skills'],
            'companies' => ['name^4', 'description^2', 'industry^2', 'location^2'],
            'candidates' => ['name^4', 'headline^3', 'bio^2', 'skills'],
            'applications' => ['job.title^4', 'candidate.name^4', 'job.company^3', 'status'],
            default => ['title^4'],
        };
    }

    protected function buildClient(): ?Client
    {
        $hosts = config('services.elasticsearch.hosts', ['http://127.0.0.1:9200']);

        if (empty($hosts)) {
            return null;
        }

        return ClientBuilder::create()->setHosts($hosts)->build();
    }

    protected function hasRemoteColumn(): bool
    {
        return Schema::hasColumn('job_posts', 'is_remote');
    }
}
