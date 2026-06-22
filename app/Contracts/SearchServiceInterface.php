<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\DTOs\SearchFilters;

interface SearchServiceInterface
{
    public function searchJobs(SearchFilters $filters): LengthAwarePaginator;
    public function suggest(string $query, int $limit = 5): Collection;
    public function reindex(string $model): void;
}
