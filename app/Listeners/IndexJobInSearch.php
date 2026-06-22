<?php
namespace App\Listeners;

use App\Events\JobPublished;
use Illuminate\Contracts\Queue\ShouldQueue;

class IndexJobInSearch implements ShouldQueue
{
    public function handle(JobPublished $event): void {
        try {
            app(\App\Services\Search\MeiliSearchService::class)->reindex(get_class($event->job));
        } catch (\Throwable $e) {
        }
    }
}
