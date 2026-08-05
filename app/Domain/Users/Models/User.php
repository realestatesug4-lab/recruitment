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
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

#[Fillable(['uuid', 'name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /**
     * Keep a snapshot of the roles prior to save so we can audit changes.
     */
    protected array $originalRoles = [];

    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUuids, Notifiable, HasRoles, SoftDeletes;

    protected static function booted(): void
    {
        static::retrieved(function (User $user) {
            $user->originalRoles = $user->roles()->pluck('name')->toArray();
        });

        static::saving(function (User $user) {
            if ($user->exists && $user->getOriginal('is_super_admin') === true && $user->is_super_admin === false) {
                $user->is_super_admin = true;
            }

            if ($user->email === config('admin.super_admin_email')) {
                $user->is_super_admin = true;
            }
        });

        static::saved(function (User $user) {
            try {
                if ($user->is_super_admin && ! $user->hasRole('admin')) {
                    $user->assignRole('admin');
                }

                $currentRoles = $user->roles()->pluck('name')->toArray();
                $rolesChanged = $user->originalRoles !== $currentRoles;
                $isSuperAdminChanged = $user->wasChanged('is_super_admin');

                if ($rolesChanged || $isSuperAdminChanged) {
                    RoleChangeAudit::create([
                        'user_id' => $user->id,
                        'changed_by' => optional(auth()->user())->id,
                        'before_roles' => $user->originalRoles,
                        'after_roles' => $currentRoles,
                        'before_is_super_admin' => $user->getOriginal('is_super_admin'),
                        'after_is_super_admin' => $user->is_super_admin,
                    ]);

                    $user->originalRoles = $currentRoles;
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to persist role change audit', ['error' => $e->getMessage()]);
            }
        });
    }

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function isAdmin(): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        return $this->isSuperAdmin() || isset($this->role) && $this->role === 'admin';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function seekerProfile(): HasOne
    {
        return $this->hasOne(SeekerProfile::class);
    }

    public function employerProfile(): HasOne
    {
        return $this->hasOne(EmployerProfile::class);
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(\App\Domain\Jobs\Models\SavedJob::class);
    }
}
