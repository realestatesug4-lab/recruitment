<?php

namespace App\Domain\Applications\Models;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationNote extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['application_id', 'author_id', 'note'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
