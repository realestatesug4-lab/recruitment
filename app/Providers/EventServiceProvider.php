<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\ApplicationSubmitted::class => [
            \App\Listeners\NotifyEmployerOfApplication::class,
            \App\Listeners\NotifyCandidateOfSubmission::class,
            \App\Listeners\UpdateApplicationAnalytics::class,
            \App\Events\RunAiScoring::class,
            \App\Listeners\UpdateSearchIndex::class,
        ],
        \App\Events\JobPublished::class => [
            \App\Listeners\IndexJobInSearch::class,
            \App\Listeners\MatchJobToSavedAlerts::class,
            \App\Listeners\NotifyCompanyFollowers::class,
        ],
        \App\Events\ApplicationStatusUpdated::class => [
            \App\Listeners\NotifySeekerOfStatusChange::class,
            \App\Listeners\LogStatusChangeAudit::class,
        ],
    ];

}
