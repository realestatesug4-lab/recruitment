<?php

namespace App\Http\Controllers;

use App\Contracts\SearchServiceInterface;
use App\Domain\Jobs\Models\Job;
use App\DTOs\SearchFilters;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class JobApiController extends Controller
{
    public function __construct(protected SearchServiceInterface $search)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = SearchFilters::fromRequest($request);
        $filters->keyword = $request->query('q', $request->query('keyword'));
        $paginator = $this->search->searchJobs($filters);

        return response()->json([
            'data' => $paginator->getCollection()->map(fn (Job $job): array => [
                'id' => $job->id,
                'slug' => $job->slug,
                'title' => $job->title,
                'company' => $job->company?->name ?? 'Unknown company',
                'color' => $job->company?->color ?? '#1B4332',
                'location' => $job->location ?? 'Uganda',
                'type' => match ($job->job_type->value) {
                    'full-time' => 'Full-time',
                    'contract' => 'Contract',
                    'remote' => 'Remote',
                    default => Str::of($job->job_type->value)->replace('-', ' ')->title()->toString(),
                },
                'salary_min' => $job->salary_min,
                'salary_max' => $job->salary_max,
                'posted_at' => $job->published_at?->diffForHumans() ?? $job->created_at->diffForHumans(),
            ]),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function suggest(Request $request): JsonResponse
    {
        $query = (string) $request->query('q', '');
        $suggestions = $this->search->suggest($query, 5);

        return response()->json($suggestions);
    }

    public function indexLegacy(Request $request): JsonResponse
    {
        $query = Job::published()->with('company');

        $location = $request->string('location')->trim()->toString();
        if ($location !== '') {
            $query->where('location', 'ILIKE', "%{$location}%");
        }

        $types = collect($request->input('type', $request->input('type', [])))
            ->merge($request->input('type', []))
            ->filter()
            ->map(fn (string $type) => Str::of($type)->lower()->replace(' ', '-')->toString())
            ->unique()
            ->values();

        if ($types->isNotEmpty()) {
            $query->whereIn('job_type', $types);
        }

        if ($request->filled('salaryMax') && is_numeric($request->input('salaryMax'))) {
            $query->where(function ($salaryQuery) use ($request): void {
                $salaryQuery
                    ->whereNull('salary_min')
                    ->orWhere('salary_min', '<=', (int) $request->input('salaryMax'));
            });
        }

        if ($request->boolean('remote')) {
            $query->where('is_remote', true);
        }

        match ($request->string('sort')->toString()) {
            'salary_desc' => $query->orderByDesc('salary_max')->orderByDesc('salary_min'),
            'date' => $query->latest('published_at'),
            default => $query->latest('published_at'),
        };

        $jobs = $query->paginate(15)->withQueryString();

        return response()->json([
            'data' => $jobs->getCollection()->map(fn (Job $job): array => [
                'id' => $job->id,
                'slug' => $job->slug,
                'title' => $job->title,
                'company' => $job->company?->name ?? 'Unknown company',
                'color' => $job->company?->color ?? '#1B4332',
                'location' => $job->location ?? 'Uganda',
                'type' => match ($job->job_type->value) {
                    'full-time' => 'Full-time',
                    'contract' => 'Contract',
                    'remote' => 'Remote',
                    default => Str::of($job->job_type->value)->replace('-', ' ')->title()->toString(),
                },
                'salary_min' => $job->salary_min,
                'salary_max' => $job->salary_max,
                'posted_at' => $job->published_at?->diffForHumans() ?? $job->created_at->diffForHumans(),
            ]),
            'meta' => [
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
            ],
        ]);
    }
}
