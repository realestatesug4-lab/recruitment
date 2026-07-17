<?php

namespace App\Console\Commands;

use App\Services\Search\ElasticsearchIndexService;
use Illuminate\Console\Command;

class InitializeElasticsearchIndices extends Command
{
    protected $signature = 'elastic:init-indices';

    protected $description = 'Create the dedicated Elasticsearch indices for jobs, companies, candidates, and applications';

    public function handle(ElasticsearchIndexService $service): int
    {
        $service->ensureIndices();
        $this->info('Elasticsearch indices initialized.');

        return self::SUCCESS;
    }
}
