<?php
namespace App\DTOs;

final class UserProfileData
{
    public function __construct(
        public int $userId,
        public array $skills = [],
        public ?string $summary = null,
        public ?string $headline = null,
        public ?string $phone = null,
        public ?string $location = null,
        public ?string $resume = null,
    ){}
}
