<?php

namespace Tests\Unit;

use App\DTOs\SearchFilters;
use App\Services\Search\ElasticsearchSearchService;
use Tests\TestCase;

class ElasticsearchSearchServiceTest extends TestCase
{
    public function test_it_builds_a_search_payload_for_keyword_and_filters(): void
    {
        $service = new ElasticsearchSearchService();
        $filters = new SearchFilters(
            keyword: 'remote php',
            jobType: 'full-time',
            location: 'London',
            remote: true,
            perPage: 12,
        );

        $payload = $service->buildSearchPayload($filters);

        $this->assertSame('remote php', $payload['query']['bool']['must'][0]['multi_match']['query']);
        $this->assertSame('full-time', $payload['query']['bool']['filter'][0]['term']['job_type.keyword']);
        $this->assertSame('London', $payload['query']['bool']['filter'][1]['term']['location.keyword']);
        $this->assertTrue($payload['query']['bool']['filter'][2]['term']['is_remote']);
    }
}
