<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Actions\PostJobAction;
use App\DTOs\JobData;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Domain\Jobs\Models\Job;
use App\Domain\Jobs\Models\JobCategory;
use App\Domain\Jobs\Models\Skill;
use App\ViewModels\JobDetailViewModel;

class EmployerJobController extends Controller
{
    public function index(): View
    {
        $companyId = Auth::user()->employerProfile->company_id;
        $jobs = Job::where('company_id', $companyId)
            ->with('categories', 'skills')
            ->paginate(15);

        return view('employer.jobs.index', compact('jobs'));
    }

    public function create(): View
    {
        return view('employer.jobs.create', [
            'categories' => JobCategory::orderBy('name')->get(),
            'skills' => Skill::orderBy('name')->get(),
            'company' => Auth::user()->employerProfile->company,
        ]);
    }

    public function store(
        StoreJobRequest $request,
        PostJobAction $action
    ): RedirectResponse {
        $job = $action->execute(
            JobData::fromRequest($request),
            Auth::user()->employerProfile->company_id
        );

        return redirect()
            ->route('employer.jobs.edit', $job->slug)
            ->with('success', 'Job created as draft.');
    }

    public function edit(Job $job): View
    {
        $this->authorize('update', $job);
        return view('employer.jobs.edit', [
            'job' => new JobDetailViewModel($job),
        ]);
    }

    public function update(Job $job, UpdateJobRequest $request): RedirectResponse
    {
        $this->authorize('update', $job);

        $data = $request->validated();

        if (array_key_exists('type', $data)) {
            $data['job_type'] = $data['type'];
            unset($data['type']);
        }

        $job->update($data);

        return redirect()
            ->route('employer.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job): RedirectResponse
    {
        $this->authorize('delete', $job);

        $job->delete();

        return redirect()
            ->route('employer.jobs.index')
            ->with('success', 'Job removed.');
    }
}
