<?php

namespace App\Providers;

use App\Domain\Applications\Models\Application;
use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use App\Policies\ApplicationPolicy;
use App\Policies\JobPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Job::class => JobPolicy::class,
        Application::class => ApplicationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }

            return null;
        });
    }
}
