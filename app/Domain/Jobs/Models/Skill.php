<?php
namespace App\Domain\Jobs\Models;

use App\Domain\Users\Models\SeekerProfile;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasUuids;

    protected $table = 'skills';

    protected $fillable = ['uuid', 'slug', 'name'];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function jobs(): BelongsToMany { return $this->belongsToMany(Job::class); }
    public function seekerProfiles(): BelongsToMany { return $this->belongsToMany(SeekerProfile::class, 'seeker_profile_skill'); }
}
