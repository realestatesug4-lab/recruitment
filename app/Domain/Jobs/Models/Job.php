<?php

namespace App\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany};
use Illuminate\Database\Eloquent\Builder;
use App\Domain\Jobs\Enums\{JobType, JobStatus, ExperienceLevel};
use App\Domain\Companies\Models\Company;
use App\Domain\Applications\Models\Application;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\JobFactory;

class Job extends Model
{
    use HasUuids, Searchable, SoftDeletes, HasFactory;

    protected $table = 'job_posts';

    protected static function newFactory()
    {
        return JobFactory::new();
    }

    protected $fillable = [
        'uuid',
        'company_id',
        'slug',
        'title',
        'description',
        'job_type',
        'experience_level',
        'status',
        'salary_min',
        'salary_max',
        'location',
        'deadline',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'job_type'         => JobType::class,
        'status'           => JobStatus::class,
        'experience_level' => ExperienceLevel::class,
        'published_at'     => 'datetime',
        'deadline'         => 'datetime',
        'expires_at'       => 'datetime',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function getTypeAttribute(): ?JobType
    {
        return $this->job_type;
    }

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function applications(): HasMany { return $this->hasMany(Application::class); }
    public function categories(): BelongsToMany { return $this->belongsToMany(JobCategory::class, 'job_category'); }
    public function skills(): BelongsToMany { return $this->belongsToMany(Skill::class); }

    public function scopePublished(Builder $q): Builder {
        return $q->where('status', JobStatus::PUBLISHED);
    }

    // Presentation helpers moved to ViewModels/ services.

}
