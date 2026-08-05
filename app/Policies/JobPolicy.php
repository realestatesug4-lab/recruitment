<?php
namespace App\Policies;

use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;

class JobPolicy
{
    private function isAdmin(User $user): bool
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        return isset($user->role) && $user->role === 'admin';
    }

    public function update(User $user, Job $job): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->employerProfile && $user->employerProfile->company_id === $job->company_id;
    }

    public function delete(User $user, Job $job): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->employerProfile && $user->employerProfile->company_id === $job->company_id;
    }
}
