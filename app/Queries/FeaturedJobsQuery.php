<?php

namespace App\Queries;

use App\Domain\Jobs\Models\Job;

class FeaturedJobsQuery
{
    public function handle(int $limit = 10) {
        return Job::published()
            ->where('is_featured', true)
            ->with('company')
            ->take($limit)
            ->get();
    }
}


