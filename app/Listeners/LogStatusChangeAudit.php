<?php

namespace App\Listeners;

use App\Domain\Applications\Models\ApplicationStatusHistory;
use App\Events\ApplicationStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogStatusChangeAudit implements ShouldQueue
{
    public function handle(ApplicationStatusUpdated $event): void
    {
        try {
            ApplicationStatusHistory::create([
                'application_id' => $event->application->id,
                'old_status' => $event->oldStatus?->value,
                'new_status' => $event->newStatus->value,
                'note' => $event->note,
                'changed_by' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to log application status change audit', [
                'application_id' => $event->application->uuid,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
