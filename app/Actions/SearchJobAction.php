<?php

namespace App\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Contracts\SearchServiceInterface;
use App\DTOs\SearchFilters;

class SearchJobAction
{
    private SearchServiceInterface $search;

    public function __construct(SearchServiceInterface $search) {
        $this->search = $search;
    }

    public function execute(SearchFilters $filters): LengthAwarePaginator {
        return $this->search->searchJobs($filters);
    }
}
