<?php
namespace App\DTOs;

final class CompanyData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $industry = null,
        public ?string $website = null,
        public ?string $description = null,
        public ?string $logo = null,
        public string $verificationStatus = 'pending',
        public bool $isFeatured = false,
    ){}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            slug: $data['slug'] ?? null,
            industry: $data['industry'] ?? null,
            website: $data['website'] ?? null,
            description: $data['description'] ?? null,
            logo: $data['logo'] ?? null,
            verificationStatus: $data['verification_status'] ?? 'pending',
            isFeatured: (bool) ($data['is_featured'] ?? false),
        );
    }
}
