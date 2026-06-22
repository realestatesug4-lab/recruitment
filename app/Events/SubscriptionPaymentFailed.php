<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SubscriptionPaymentFailed
{
    use Dispatchable;

    public function __construct(public int $userId, public array $details = []) {}
}
//billing changes failed
