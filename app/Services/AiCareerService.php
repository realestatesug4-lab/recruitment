<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCareerService
{
    private function callClaude(string $system, string $userPrompt, int $maxTokens = 2000): string
    {
        $key = config('services.anthropic.key');

        if (blank($key)) {
            return "[AI service unavailable — API key not configured]";
        }

        try {
            $response = Http::timeout(60)->withHeaders([
                'x-api-key'         => $key,
                'anthropic-version' => '2023-06-01',
                'content-type'      => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-sonnet-4-20250514',
                'max_tokens' => $maxTokens,
                'system'     => $system,
                'messages'   => [['role' => 'user', 'content' => $userPrompt]],
            ]);

            if ($response->successful()) {
                return $response->json('content.0.text') ?? 'No response generated.';
            }

            Log::warning('Claude API call failed', ['status' => $response->status(), 'body' => $response->body()]);
            return "AI service returned an error. Please try again shortly.";
        } catch (\Throwable $e) {
            Log::error('Claude API exception', ['error' => $e->getMessage()]);
            return "AI service is temporarily unavailable. Please try again later.";
        }
    }

    public function generateCv(array $profile, string $jobDescription): string
    {
        return $this->callClaude(
            'You are an expert career coach specialised in Ugandan and East African job markets. Generate a professional, ATS-optimised CV tailored to the specific job description provided. Use clean formatting with clear sections: Summary, Experience, Education, Skills. Use bullet points with action verbs and quantifiable achievements.',
            "Profile:\n" . json_encode($profile, JSON_PRETTY_PRINT) . "\n\nJob Description:\n" . $jobDescription,
        );
    }

    public function generateCoverLetter(array $profile, string $jobDescription): string
    {
        return $this->callClaude(
            'You are a professional cover letter writer specialising in East African job markets. Write a compelling, concise cover letter (max 400 words) that highlights the candidate\'s strongest qualifications for the role. Use a warm but professional tone. Structure: opening hook, 2-3 body paragraphs connecting experience to role requirements, confident close with call to action.',
            "Candidate Profile:\n" . json_encode($profile, JSON_PRETTY_PRINT) . "\n\nJob Description:\n" . $jobDescription,
        );
    }

    public function mockInterview(string $role, string $question): string
    {
        return $this->callClaude(
            'You are an expert interview coach for the East African job market. Given a job role and an interview question, provide: 1) A brief strategy for answering this type of question, 2) A model answer framework using the STAR method (Situation, Task, Action, Result), 3) Key points to emphasise, 4) Common pitfalls to avoid. Keep the coaching practical and actionable.',
            "Role: {$role}\n\nInterview Question: {$question}",
            1500,
        );
    }

    public function salaryCoach(string $role, string $location, int $yearsExp): string
    {
        return $this->callClaude(
            'You are a salary negotiation expert for the East African job market. Provide a comprehensive salary analysis including: 1) Typical salary range for this role and experience level, 2) Factors that influence compensation, 3) Negotiation strategies and talking points, 4) Benefits and perks to consider, 5) How to research company-specific ranges. Be specific with numbers in UGX and USD where relevant.',
            "Role: {$role}\nLocation: {$location}\nYears of Experience: {$yearsExp}",
            1200,
        );
    }
}
