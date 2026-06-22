<?php
namespace App\Events;

use App\Domain\Applications\Models\Application;
use Illuminate\Foundation\Events\Dispatchable;

class NewApplicationReceived
{
    use Dispatchable;

    public function __construct(public Application $application) {}
}
