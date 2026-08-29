<?php

namespace App\Domain\Users\Models;

use App\Domain\Companies\Models\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class EmployerProfile extends Model
{
    use HasUuids;

    protected $table = 'employer_profiles';

    protected $fillable = [
        'uuid',
        'user_id',
        'company_id',
        'title',
        'job_title',
        'phone',
        'bio',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (self $profile) {
            $profile->logAudit('created');
        });

        static::updated(function (self $profile) {
            $profile->logAudit('updated');
        });

        static::deleted(function (self $profile) {
            $profile->logAudit('deleted');
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function logAudit(string $event): void
    {
        try {
            $payload = $this->fresh(['user', 'company'])?->toArray() ?? $this->toArray();

            Log::info('Employer profile audit', [
                'event' => $event,
                'actor_id' => auth()->id(),
                'profile_id' => $this->id,
                'uuid' => $this->uuid,
                'user_id' => $this->user_id,
                'company_id' => $this->company_id,
                'data' => $payload,
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to log employer profile audit', [
                'event' => $event,
                'profile_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
