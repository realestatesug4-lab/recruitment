<?php

namespace App\Console\Commands;

use App\Jobs\ReindexAllJobsInElasticsearch;
use Illuminate\Console\Command;

class ReindexJobsToElasticsearch extends Command
{
    protected $signature = 'jobs:reindex-elasticsearch';

    protected $description = 'Queue a full reindex of published jobs into Elasticsearch';

    public function handle(): int
    {
        ReindexAllJobsInElasticsearch::dispatch();
        $this->info('Reindex job dispatch queued.');

        return self::SUCCESS;
    }
}
