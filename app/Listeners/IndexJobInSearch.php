<?php
namespace App\Listeners;

use App\Events\JobPublished;
use App\Jobs\IndexJobInElasticsearch;
use Illuminate\Contracts\Queue\ShouldQueue;

class IndexJobInSearch implements ShouldQueue
{
    public function handle(JobPublished $event): void {
        IndexJobInElasticsearch::dispatch($event->job);
    }
}
