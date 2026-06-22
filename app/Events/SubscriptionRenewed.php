<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SubscriptionRenewed
{
    use Dispatchable;

    public function __construct(public int $userId, public array $meta = []) {}
}
// billing cycle completed successfully
