<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Links an AdCampaign to specific AdZones
 * Represents where an ad campaign can be displayed
 */
class AdPlacement extends Model
{
    use HasFactory;

    protected $table = 'ad_placements';

    protected $fillable = [
        'campaign_id',
        'zone_id',
        'revive_campaign_id',
        'revive_zone_id',
        'starts_at',
        'ends_at',
        'priority',                // higher = higher priority in zone rotation
        'frequency_cap',           // limit impressions per user per day
        'geotargeting',           // JSON: countries, regions, cities
        'device_targeting',       // JSON: desktop, mobile, tablet
        'audience_targeting',     // JSON: job categories, locations, etc
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'priority' => 'integer',
        'frequency_cap' => 'integer',
        'geotargeting' => 'array',
        'device_targeting' => 'array',
        'audience_targeting' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the campaign for this placement
     */
    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class, 'campaign_id');
    }

    /**
     * Get the zone for this placement
     */
    public function zone()
    {
        return $this->belongsTo(AdZone::class, 'zone_id');
    }

    /**
     * Check if placement is currently active (within date range)
     */
    public function isCurrentlyActive(): bool
    {
        $now = now();
        return $this->is_active
            && ($this->starts_at === null || $this->starts_at <= $now)
            && ($this->ends_at === null || $this->ends_at >= $now);
    }
}
