<?php
namespace App\Domain\Jobs\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobCategory extends Model
{
    use HasUuids;

    protected $table = 'job_categories';

    protected $fillable = ['uuid', 'slug', 'name'];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function jobs(): BelongsToMany { return $this->belongsToMany(Job::class, 'job_category'); }
}
