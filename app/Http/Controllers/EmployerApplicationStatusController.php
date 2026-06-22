<?php
namespace App\Http\Controllers;

use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Enums\ApplicationStatus;
use App\Http\Requests\UpdateApplicationStatusRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class EmployerApplicationStatusController extends Controller
{
    public function update(Application $application, UpdateApplicationStatusRequest $request): RedirectResponse
    {
        $companyId = Auth::user()->employerProfile->company_id;

        $this->authorize('updateStatus', [$application, $companyId]);

        $oldStatus = $application->status;
        $newStatus = ApplicationStatus::from($request->input('status'));

        $application->update([
            'status' => $newStatus,
            'notes' => $request->input('notes'),
        ]);

        event(new \App\Events\ApplicationStatusUpdated(
            $application,
            $oldStatus,
            $newStatus
        ));

        return redirect()->back()->with('success', 'Application status updated.');
    }
}
