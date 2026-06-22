<?php
namespace App\Events;

use App\Domain\Applications\Models\Application;
use App\Domain\Applications\Enums\ApplicationStatus;
use Illuminate\Foundation\Events\Dispatchable;

class ApplicationStatusUpdated
{
    use Dispatchable;

    public function __construct(public Application $application, public ?ApplicationStatus $oldStatus, public ApplicationStatus $newStatus, public ?string $note = null) {}
}
