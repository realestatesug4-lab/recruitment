<?php

namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Notifications\NewApplicationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifyEmployerOfApplication implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void
    {
        try {
            $application = $event->application->loadMissing(['job.company.owner', 'seekerProfile']);

            $company = $application->job?->company;

            if (! $company) {
                return;
            }

            // Notify the company owner
            if ($company->owner) {
                $company->owner->notify(new NewApplicationNotification($application));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to notify employer of new application', [
                'application_id' => $event->application->uuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
