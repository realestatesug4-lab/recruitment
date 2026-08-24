<?php

namespace App\Http\Controllers;

use App\Services\AiCareerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AiCareerController extends Controller
{
    public function __construct(private AiCareerService $aiService) {}

    public function index(): View
    {
        return view('seeker.ai-tools.index', [
            'profile' => Auth::user()->seekerProfile?->load('skills'),
        ]);
    }

    public function coverLetter(Request $request): JsonResponse
    {
        $request->validate([
            'job_title'       => 'required|string|max:255',
            'company_name'    => 'required|string|max:255',
            'job_description' => 'required|string|max:5000',
        ]);

        $profile = $this->buildProfileArray();

        $profile['job_title']    = $request->input('job_title');
        $profile['company_name'] = $request->input('company_name');

        $result = $this->aiService->generateCoverLetter($profile, $request->input('job_description'));

        return response()->json(['content' => $result]);
    }

    public function interview(Request $request): JsonResponse
    {
        $request->validate([
            'role'     => 'required|string|max:255',
            'question' => 'required|string|max:1000',
        ]);

        $result = $this->aiService->mockInterview(
            $request->input('role'),
            $request->input('question'),
        );

        return response()->json(['content' => $result]);
    }

    public function salary(Request $request): JsonResponse
    {
        $request->validate([
            'role'           => 'required|string|max:255',
            'location'       => 'required|string|max:255',
            'years_experience' => 'required|integer|min:0|max:50',
        ]);

        $result = $this->aiService->salaryCoach(
            $request->input('role'),
            $request->input('location'),
            (int) $request->input('years_experience'),
        );

        return response()->json(['content' => $result]);
    }

    public function cv(Request $request): JsonResponse
    {
        $request->validate([
            'job_description' => 'required|string|max:5000',
        ]);

        $profile = $this->buildProfileArray();
        $result  = $this->aiService->generateCv($profile, $request->input('job_description'));

        return response()->json(['content' => $result]);
    }

    private function buildProfileArray(): array
    {
        $user    = Auth::user();
        $profile = $user->seekerProfile?->load('skills');

        return [
            'name'             => $user->name,
            'email'            => $user->email,
            'headline'         => $profile?->headline ?? '',
            'bio'              => $profile?->bio ?? '',
            'location'         => $profile?->location ?? '',
            'experience_level' => $profile?->experience_level ?? '',
            'skills'           => $profile?->skills->pluck('name')->implode(', ') ?? '',
        ];
    }
}
