<?php
namespace App\Http\Controllers;

use App\Domain\Users\Models\SeekerProfile;
use App\Domain\Jobs\Models\Skill;
use App\Events\CandidateProfileUpdated;
use App\Http\Requests\StoreSeekerProfileRequest;
use App\Http\Requests\UpdateSeekerProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SeekerProfileController extends Controller
{
    public function show(): View
    {
        $profile = Auth::user()->seekerProfile;

        return view('seeker.profile.show', [
            'profile' => $profile,
        ]);
    }

    public function create(): View
    {
        return view('seeker.profile.create');
    }

    public function store(StoreSeekerProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user->seekerProfile) {
            return redirect()
            ->route('seeker.profile.edit')
            ->with('error', 'You already have a profile. Please edit your existing profile.');
        }

        $data = $request->validated();
        $skills = $this->normalizeSkills($data);
        unset($data['skills'], $data['resume']);

        if ($request->hasFile('resume')) {
            $data['resume_url'] = $request->file('resume')->store('resumes', 'public');
        }

        DB::transaction(function () use ($user, $data, $skills, &$profile) {
            $profile = $user->seekerProfile()->create($data); $profile->skills()->sync($skills);
        });

        event(new CandidateProfileUpdated($user));

        $profile = Auth::user()->seekerProfile()->create($data);

        $profile->skills()->sync($skills);
        event(new CandidateProfileUpdated(Auth::user()));

        return redirect()->route('seeker.profile.show')->with('success', 'Profile created successfully.');
    }

    public function edit(): View
    {

        $profile = Auth::user()->seekerProfile;

        return view('seeker.profile.edit', [
            'profile' => $profile,
        ]);
    }

    public function update(UpdateSeekerProfileRequest $request): RedirectResponse
    {

        $user = Auth::user();
        $profile = $user->seekerProfile;

        $data = $request->validated();
        $skills = $this->normalizeSkills($data);
        unset($data['skills'], $data['resume']);

        $profile = Auth::user()->seekerProfile;
        if ($request->hasFile('resume')) {
            $data['resume_url'] = $request->file('resume')->store('resumes', 'public');
        }

        DB::transaction(function () use ($profile, $data, $skills) {
            $profile->update($data); $profile->skills()->sync($skills);
        });

        event(new CandidateProfileUpdated($user));

        return redirect()->route('seeker.profile.show')->with('success', 'Profile updated successfully.');
    }

    private function normalizeSkills(array $data): array
    {
        if (empty($data['skills'])) {
            return [];
        }

        return collect($data['skills'])
            ->filter()
            ->flatMap(fn($skill) => explode(',', $skill))
            ->map(fn($skill) => trim($skill))
            ->filter()
            ->unique(fn($skill) => Str::lower($skill))
            ->map(fn($skill) => Skill::firstOrCreate(
                ['slug' => Str::slug($skill)],
                ['name' => $skill]
            )->id)
            ->toArray();
    }
}
