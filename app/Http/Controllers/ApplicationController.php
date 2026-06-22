<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Domain\Applications\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ApplicationController extends Controller
{
    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $application = Application::create([
            'job_id' => $data['job_id'],
            'seeker_id' => Auth::id(),
            'cover_letter' => $data['cover_letter'] ?? null,
            'status' => \App\Domain\Applications\Enums\ApplicationStatus::SUBMITTED,
        ]);

        return redirect()->back()->with('success', 'Application submitted.');
    }
}
