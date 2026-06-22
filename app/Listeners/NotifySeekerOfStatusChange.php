<?php
namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySeekerOfStatusChange implements ShouldQueue
{
    public function handle(ApplicationStatusUpdated $event): void {
        $candidate = $event->application->seekerProfile;
        if ($candidate && method_exists($candidate, 'notify')) {
            $candidate->notify(new \App\Events\NewApplicationReceived($event->application));
        }
    }
}
