<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Contracts\RecommendationServiceInterface;
use App\Domain\Users\Models\User;
use App\Domain\Jobs\Models\Job;

class RecommendationService implements RecommendationServiceInterface
{
    private CandidateScorer $scorer;

    public function __construct(CandidateScorer $scorer) {
        $this->scorer = $scorer;
    }

    public function recommendedJobs(User $user, int $limit = 10): Collection {
        $profile = $user->seekerProfile;

        return Job::published()
            ->whereHas('skills', fn($q) => $q->whereIn('skill_id', $profile->skills->pluck('id')))
            ->get()
            ->sortByDesc(fn($job) => $this->scorer->score($profile, $job))
            ->take($limit)
            ->values();
    }

    public function similarJobs(Job $job, int $limit = 5): Collection {
        return Job::published()
            ->where('id', '!=', $job->id)
            ->whereHas('categories', fn($q) => $q->whereIn(
                'job_category_id', $job->categories->pluck('id')
            ))
            ->take($limit)->get();
    }
}
