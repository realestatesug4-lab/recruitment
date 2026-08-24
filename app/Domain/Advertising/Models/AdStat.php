<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdStat extends Model
{
    use HasFactory;

    protected $table = 'ad_stats';

    protected $fillable = [
        'placement_id',
        'revive_zone_id',
        'date',
        'impressions',
        'clicks',
        'revenue',
        'ctr',
        'ecpm',
    ];

    protected $casts = [
        'date' => 'date',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'revenue' => 'float',
        'ctr' => 'float',
        'ecpm' => 'float',
    ];

    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }
}
