<?php

namespace App\Http\Controllers;

use App\Domain\Applications\Enums\ApplicationStatus;
use App\Domain\Applications\Models\Application;
use App\Events\ApplicationStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AtsController extends Controller
{
    /**
     * Kanban board: applications grouped by status column.
     */
    public function index(): View
    {
        $companyId = Auth::user()->employerProfile->company_id;

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

        $oldStatus = $application->status;
        $newStatus = ApplicationStatus::from($request->input('status'));

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Status unchanged.');
        }

        // Update status and fire event — listener handles history + notification
        $application->update(['status' => $newStatus]);
        event(new ApplicationStatusUpdated($application, $oldStatus, $newStatus, $request->input('note')));

        return back()->with('success', "Candidate moved to {$newStatus->value}.");
    }
}
