<?php
namespace App\DTOs;

final class AiGenerationRequest
{
    public function __construct(
        public int $userId,
        public string $toolType,
        public string $prompt,
        public array $context = [],
        public ?int $maxTokens = null,
        public ?string $response = null,
        public int $tokensUsed = 0,
        public float $cost = 0,
    ){}
}
