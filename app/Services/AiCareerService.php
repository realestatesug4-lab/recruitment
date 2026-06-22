<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiCareerService
{
    public function generateCv(array $profile, string $jobDescription): string
    {
        $response = Http::withHeaders([
            'x-api-key'         => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type'      => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model'      => 'claude-sonnet-4-20250514',
            'max_tokens' => 2000,
            'system'     => 'You are an expert career coach specialised in Ugandan and East African job markets. Generate a professional, ATS-optimised CV tailored to the specific job description provided.',
            'messages'   => [[
                'role'    => 'user',
                'content' => "Profile:\n" . json_encode($profile) . "\n\nJob Description:\n" . $jobDescription,
            ]],
        ]);

        return $response->json('content.0.text');
    }

    public function generateCoverLetter(array $profile, string $jobDescription): string
    {
        // Simple template-based cover letter
        $name = $profile['name'] ?? 'Candidate';
        $company = $profile['company'] ?? 'Hiring Team';
        return "Dear {$company},\n\nI, {$name}, am excited to apply...\n\n(Generated cover letter placeholder)";
    }

    public function mockInterview(string $role, string $question): string
    {
        // placeholder: return a suggested answer
        return "Suggested answer for role {$role}: \nThis would be a structured response to '{$question}'.";
    }

    public function salaryCoach(string $role, string $location, int $yearsExp): string
    {
        // placeholder salary guidance
        return "For {$role} in {$location} with {$yearsExp} years experience, a typical salary range is UGX 1,500,000 - UGX 4,000,000 (approx).";
    }
}

