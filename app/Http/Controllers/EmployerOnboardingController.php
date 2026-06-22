<?php

namespace App\Http\Controllers;

use App\Domain\Companies\Models\Company;
use App\Domain\Users\Models\EmployerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EmployerOnboardingController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::user()->employerProfile) {
            return redirect()->route('employer.dashboard');
        }

        return view('employer.onboarding.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'work_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'job_title' => ['required', 'string', 'max:80'],
            'website' => ['nullable', 'url', 'max:255'],
            'industry' => ['nullable', 'string', 'max:120'],
            'size' => ['nullable', 'integer', 'min:1'],
            'location' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1200'],
        ]);

        DB::transaction(function () use ($data) {
            $user = Auth::user();
            $user->forceFill(['role' => 'employer'])->save();

            $company = Company::create([
                'name' => $data['company_name'],
                'slug' => Str::slug($data['company_name']) . '-' . Str::lower(Str::random(5)),
                'website' => $data['website'] ?? null,
                'industry' => $data['industry'] ?? null,
                'size' => $data['size'] ?? null,
                'location' => $data['location'] ?? null,
                'description' => $data['description'] ?? null,
                'verification_status' => 'pending',
                'owner_id' => $user->id,
            ]);

            EmployerProfile::create([
                'user_id' => $user->id,
                'company_id' => $company->id,
                'job_title' => $data['job_title'],
                'phone' => $data['phone'],
                'bio' => 'Work email: ' . $data['work_email'],
            ]);
        });

        return redirect()
            ->route('employer.dashboard')
            ->with('success', 'Company profile submitted for verification.');
    }
}
