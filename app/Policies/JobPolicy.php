<?php
namespace App\Policies;

use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;

class JobPolicy
{
    public function update(User $user, Job $job): bool
    {
        return $user->employerProfile && $user->employerProfile->company_id === $job->company_id;
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->employerProfile && $user->employerProfile->company_id === $job->company_id;
    }
}
