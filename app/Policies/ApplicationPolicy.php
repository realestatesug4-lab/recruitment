<?php
namespace App\Policies;

use App\Domain\Applications\Models\Application;
use App\Domain\Users\Models\User;

class ApplicationPolicy
{
    private function isAdmin(User $user): bool
    {
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        return isset($user->role) && $user->role === 'admin';
    }

    public function updateStatus(User $user, Application $application, int $companyId): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $user->employerProfile && $user->employerProfile->company_id === $application->job->company_id;
    }
}
