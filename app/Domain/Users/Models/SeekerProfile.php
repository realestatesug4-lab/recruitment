<?php
namespace App\Domain\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Domain\Jobs\Models\Skill;

class SeekerProfile extends Model
{
    protected $table = 'seeker_profiles';

    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'location',
        'experience_level',
        'resume_url',
    ];

    public function skills(): BelongsToMany { return $this->belongsToMany(Skill::class, 'seeker_profile_skill'); }
}
