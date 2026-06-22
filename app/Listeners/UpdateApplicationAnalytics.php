<?php
namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateApplicationAnalytics implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void {
        // placeholder: increment analytics counters
        // e.g., dispatch job to aggregate metrics
    }
}
