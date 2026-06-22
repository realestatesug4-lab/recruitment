<?php

namespace App\Domain\Recommendations\Models;

use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobRecommendation extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = null;
    protected $fillable = ['user_id', 'job_id', 'score', 'generated_at'];

    protected $casts = [
        'score' => 'decimal:4',
        'generated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
