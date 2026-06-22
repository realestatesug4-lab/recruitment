<?php
namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSearchIndex implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void {
        // placeholder: update search index for related job/user
        try {
            app(\App\Services\Search\MeiliSearchService::class)->reindex(\App\Domain\Jobs\Models\Job::class);
        } catch (\Throwable $e) {
            // ignore for now
        }
    }
}
