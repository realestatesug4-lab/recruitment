<?php

namespace App\Events;

use App\Domain\Companies\Models\Company;
use Illuminate\Foundation\Events\Dispatchable;

class CompanyCreated
{
    use Dispatchable;

    public function __construct(public Company $company)
    {
    }
}
