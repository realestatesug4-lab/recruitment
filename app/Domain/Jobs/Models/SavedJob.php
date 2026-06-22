<?php
namespace App\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\Users\Models\User;

class SavedJob extends Model
{
    protected $table = 'saved_jobs';
    protected $fillable = ['user_id', 'job_id'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function job(): BelongsTo { return $this->belongsTo(Job::class); }
}
