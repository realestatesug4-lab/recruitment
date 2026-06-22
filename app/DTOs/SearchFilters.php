<?php
namespace App\DTOs;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

final class SearchFilters
{
    public function __construct(
        public ?string $keyword = null,
        public ?string $jobType = null,
        public ?string $location = null,
        public bool $remote = false,
        public int $perPage = 15,
    ){}

    public static function fromRequest(Request $request): self
    {
        return new self(
            keyword: $request->query('q') ?? $request->query('keyword'),
            jobType: $request->query('job_type') ?? $request->query('type'),
            location: $request->query('location'),
            remote: $request->boolean('remote'),
            perPage: (int) ($request->query('per_page', 15)),
        );
    }
}
