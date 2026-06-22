<?php
namespace App\DTOs;

final class ApplicationData
{
    public function __construct(
        public int $jobId,
        public int $userId,
        public string $status = 'submitted',
        public ?float $matchScore = null,
        public ?string $coverLetter = null,
        public ?string $resumePath = null,
        public ?\DateTimeInterface $appliedAt = null,
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            jobId: $data['job_id'],
            userId: $data['user_id'],
            status: $data['status'] ?? 'submitted',
            matchScore: isset($data['match_score']) ? (float) $data['match_score'] : null,
            coverLetter: $data['cover_letter'] ?? null,
            resumePath: $data['resume_path'] ?? null,
            appliedAt: isset($data['applied_at']) ? new \DateTime($data['applied_at']) : null,
        );
    }
}
