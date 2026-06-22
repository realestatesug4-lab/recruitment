<?php

namespace App\Domain\Users\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Users\Models\SeekerProfile;
use App\Domain\Users\Models\EmployerProfile;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['uuid', 'name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable, HasRoles, SoftDeletes;

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function seekerProfile(): HasOne { return $this->hasOne(SeekerProfile::class); }
    public function employerProfile(): HasOne { return $this->hasOne(EmployerProfile::class); }
    public function savedJobs(): HasMany { return $this->hasMany(\App\Domain\Jobs\Models\SavedJob::class); }

}
