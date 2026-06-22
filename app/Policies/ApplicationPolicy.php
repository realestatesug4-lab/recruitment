<?php
namespace App\Policies;

use App\Domain\Applications\Models\Application;
use App\Domain\Users\Models\User;

class ApplicationPolicy
{
    public function updateStatus(User $user, Application $application, int $companyId): bool
    {
        return $user->employerProfile && $user->employerProfile->company_id === $application->job->company_id;
    }
}
