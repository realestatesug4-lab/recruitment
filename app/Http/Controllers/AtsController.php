<?php

namespace App\Http\Controllers;

use App\Domain\Applications\Enums\ApplicationStatus;
use App\Domain\Applications\Models\Application;
use App\Events\ApplicationStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AtsController extends Controller
{
    /**
     * Kanban board: applications grouped by status column.
     */
    public function index(): View
    {
        $user = Auth::user();

        if (! $user) {
            abort(403, 'Unauthorized.');
        }

        $companyId = $user->employerProfile?->company_id;

        if (! $companyId) {
            return redirect()->route('employer.onboarding.create')->with('error', 'Set up your company profile first.');
        }

        $columns = collect(ApplicationStatus::cases())
            ->filter(fn (ApplicationStatus $s) => $s !== ApplicationStatus::DRAFT)
            ->mapWithKeys(function (ApplicationStatus $status) use ($companyId) {
                $apps = Application::whereHas('job', fn ($q) => $q->where('company_id', $companyId))
                    ->where('status', $status)
                    ->with(['seekerProfile', 'job'])
                    ->latest()
                    ->get();

                return [$status->value => $apps];
            });

        return view('employer.ats.index', [
            'columns' => $columns,
            'statuses' => ApplicationStatus::cases(),
        ]);
    }

    /**
     * Move an application to a new status from the kanban.
     */
    public function updateStatus(Application $application, Request $request): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:' . collect(ApplicationStatus::cases())->map->value->implode(','),
            'note'   => 'nullable|string|max:1000',
        ]);

        $newStatus = ApplicationStatus::from($request->input('status'));

        return DB::transaction(function () use ($application, $newStatus, $request) {
            $lockedApplication = Application::query()
                ->whereKey($application->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $oldStatus = $lockedApplication->status;

            if ($oldStatus === $newStatus) {
                return back()->with('success', 'Status unchanged.');
            }

            $lockedApplication->update(['status' => $newStatus]);
            event(new ApplicationStatusUpdated($lockedApplication, $oldStatus, $newStatus, $request->input('note')));

            return back()->with('success', "Candidate moved to {$newStatus->value}.");
        });
    }
}
