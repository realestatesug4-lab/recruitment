<?php

namespace App\Domain\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleChangeAudit extends Model
{
    protected $table = 'role_change_audits';

    protected $fillable = [
        'user_id',
        'changed_by',
        'before_roles',
        'after_roles',
        'before_is_super_admin',
        'after_is_super_admin',
    ];

    protected $casts = [
        'before_roles' => 'array',
        'after_roles' => 'array',
        'before_is_super_admin' => 'boolean',
        'after_is_super_admin' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
