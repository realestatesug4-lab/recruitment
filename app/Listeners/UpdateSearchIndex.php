<?php
namespace App\Listeners;

use App\Events\ApplicationSubmitted;
use App\Jobs\ReindexAllJobsInElasticsearch;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSearchIndex implements ShouldQueue
{
    public function handle(ApplicationSubmitted $event): void {
        ReindexAllJobsInElasticsearch::dispatch();
    }
}
