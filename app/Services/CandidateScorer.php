<?php
namespace App\Services;

use App\Domain\Users\Models\SeekerProfile;
use App\Domain\Jobs\Models\Job;

class CandidateScorer
{
    // minimal scorer: returns 0-100 based on naive criteria
    public function score($profile, Job $job): int
    {
        // placeholder: if profile or job missing, return 0
        if (!$profile || !$job) {
            return 0;
        }

        // naive scoring: match count of skills if available
        try {
            $profileSkills = $profile->skills ?? collect();
            $jobSkills = $job->skills ?? collect();
            $matches = $profileSkills->intersect($jobSkills->pluck('id'))->count();
            return min(100, $matches * 20);
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
