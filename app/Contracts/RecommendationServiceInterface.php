<?php

namespace App\Contracts;

use Illuminate\Support\Collection;
use App\Domain\Users\Models\User;
use App\Domain\Jobs\Models\Job;

interface RecommendationServiceInterface
{
    public function recommendedJobs(User $user, int $limit = 10): Collection;
    public function similarJobs(Job $job, int $limit = 5): Collection;
}
