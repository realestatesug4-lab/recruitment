<?php
namespace App\DTOs;

use App\Support\ValueObjects\SalaryRange;
use App\Domain\Jobs\Enums\{JobType, ExperienceLevel};
use App\Http\Requests\StoreJobRequest;

final class JobData
{
    public function __construct(
        public string $title,
        public string $description,
        public JobType $type,
        public ExperienceLevel $experienceLevel,
        public string $location,
        public ?SalaryRange $salaryRange,
        public ?\DateTimeInterface $deadline,
        public ?\DateTimeInterface $expiresAt = null,
        public array $categoryIds = [],
        public array $skills = [],
    ) {}

    public static function fromRequest(StoreJobRequest $request): self {
        $validated = $request->validated();

        return new self(
            title:           $validated['title'],
            description:     $validated['description'],
            type:            JobType::from($validated['type']),
            experienceLevel: ExperienceLevel::from($validated['experience_level']),
            location:        $validated['location'] ?? 'Uganda',
            salaryRange:     isset($validated['salary_min'])
                                ? new SalaryRange($validated['salary_min'], $validated['salary_max'] ?? null)
                                : null,
            deadline:        isset($validated['deadline']) ? new \DateTime($validated['deadline']) : null,
            expiresAt:        isset($validated['expires_at']) ? new \DateTime($validated['expires_at']) : null,
            categoryIds:     $validated['categories'] ?? [],
            skills:          $validated['skills'] ?? [],
        );
    }
}
