<?php
namespace App\Listeners;

use App\Events\ApplicationStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogStatusChangeAudit implements ShouldQueue
{
    public function handle(ApplicationStatusUpdated $event): void {
        // placeholder: write audit log
    }
}
