<?php

namespace App\Queries;

use App\Domain\Jobs\Models\Job;

class PopularJobsQuery
{
    public function handle(int $days = 7, int $limit = 10) {
        return Job::published()
            ->where('published_at', '>=', now()->subDays($days))
            ->orderByDesc('views_count')
            ->take($limit)
            ->get();
    }
}
