<?php

namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use App\Notifications\ApplicationStatusChangedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class NotifySeekerOfStatusChange implements ShouldQueue
{
    public function handle(ApplicationStatusUpdated $event): void
    {
        try {
            $application = $event->application->loadMissing(['job.company', 'seekerProfile']);

            $seeker = $application->seekerProfile;

            if ($seeker) {
                $seeker->notify(new ApplicationStatusChangedNotification(
                    $application,
                    $event->newStatus,
                    $event->note,
                ));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to notify seeker of status change', [
                'application_id' => $event->application->uuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
