<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class AiToolUsed
{
    use Dispatchable;

    public function __construct(public string $tool, public int $userId, public array $meta = []) {}
}
// a seeker generates CV, covel letter, interview prep
