<?php

namespace App\Domain\Applications\Models;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStatusHistory extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'application_status_history';

    protected $fillable = ['application_id', 'old_status', 'new_status', 'changed_by'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
