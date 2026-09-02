<?php

namespace App\Domain\Users\Models;

use App\Domain\Jobs\Models\Skill;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

class SeekerProfile extends Model
{
    use HasUuids;

    protected $table = 'seeker_profiles';

    protected $fillable = [
        'uuid',
        'user_id',
        'headline',
        'bio',
        'location',
        'experience_level',
        'resume_url',
    ];

    protected static function booted(): void
    {
        static::created(function (self $profile) {
            Log::info('Seeker profile audit', [
                'event' => 'created',
                'actor_id' => auth()->id(),
                'profile_id' => $profile->id,
                'uuid' => $profile->uuid,
                'data' => $profile->fresh()->toArray(),
            ]);
        });

        static::updated(function (self $profile) {
            Log::info('Seeker profile audit', [
                'event' => 'updated',
                'actor_id' => auth()->id(),
                'profile_id' => $profile->id,
                'uuid' => $profile->uuid,
                'data' => $profile->fresh()->toArray(),
            ]);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'seeker_profile_skill');
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
