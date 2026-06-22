<?php

namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Events\NewApplicationReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyEmployerOfApplication implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void {
        $event->application->job->company->employers->each(
            fn($employer) => $employer->notify(
                new NewApplicationReceived($event->application)
            )
        );
    }
}

