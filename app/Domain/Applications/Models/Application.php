<?php

namespace App\Domain\Applications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domain\Users\Models\User;
use App\Domain\Jobs\Models\Job;
use App\Domain\Applications\Enums\ApplicationStatus;
use Database\Factories\ApplicationFactory;

class Application extends Model
{
    use HasUuids, HasFactory;

    protected static function newFactory()
    {
        return ApplicationFactory::new();
    }

    protected $fillable = [
        'uuid',
        'job_id',
        'seeker_id',
        'status',
        'match_score',
        'cover_letter',
        'resume_path',
        'applied_at',
    ];

    protected $casts = [
        'status' => ApplicationStatus::class,
        'match_score' => 'decimal:2',
        'applied_at' => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function seekerProfile(): BelongsTo { return $this->belongsTo(User::class, 'seeker_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'seeker_id'); }
    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
    public function notes(): HasMany { return $this->hasMany(ApplicationNote::class); }
    public function statusHistory(): HasMany { return $this->hasMany(ApplicationStatusHistory::class); }

}
