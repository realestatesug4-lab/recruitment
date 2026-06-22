<?php
namespace App\Listeners;

use App\Events\JobPublished;
use Illuminate\Contracts\Queue\ShouldQueue;

class MatchJobToSavedAlerts implements ShouldQueue
{
    public function handle(JobPublished $event): void {
        // placeholder: match job to saved alerts
    }
}
