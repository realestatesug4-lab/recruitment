<?php

namespace App\Domain\Billing\Models;

use App\Domain\Companies\Models\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids;

    public const UPDATED_AT = null;

    protected $fillable = [
        'uuid',
        'company_id',
        'amount',
        'currency',
        'provider',
        'transaction_reference',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
