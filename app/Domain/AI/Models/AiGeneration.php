<?php

namespace App\Domain\Ai\Models;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiGeneration extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['user_id', 'tool_type', 'prompt', 'response', 'tokens_used', 'cost'];

    protected $casts = [
        'tokens_used' => 'integer',
        'cost' => 'decimal:4',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
