<?php

namespace App\Domain\Analytics\Models;

use App\Domain\Jobs\Models\Job;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyJobMetric extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $primaryKey = null;
    protected $fillable = ['job_id', 'date', 'views', 'applications', 'shortlists'];

    protected $casts = [
        'date' => 'date',
        'views' => 'integer',
        'applications' => 'integer',
        'shortlists' => 'integer',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
