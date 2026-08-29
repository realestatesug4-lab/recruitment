<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Placement extends Model
{
    use HasFactory;

    protected $table = 'placements';

    protected $fillable = [
        'name',
        'slug',
        'context',
        'position',
        'device_targeting',
        'audience',
        'revive_zone_id',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'device_targeting' => 'array',
        'audience' => 'array',
    ];

    public function stats()
    {
        return $this->hasMany(AdStat::class);
    }

    public function getRecentStats($days = 30)
    {
        return $this->stats()
            ->where('date', '>=', now()->subDays($days))
            ->orderBy('date', 'desc')
            ->get();
    }
}
