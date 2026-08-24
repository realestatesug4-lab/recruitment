<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents an advertising placement slot on the website
 * Maps to a Revive zone for actual ad delivery
 */
class AdZone extends Model
{
    use HasFactory;

    protected $table = 'ad_zones';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'revive_zone_id',
        'page_type',      // homepage, job_listing, job_detail, employer_profile, mobile
        'position',       // top, sidebar, inline, footer
        'width',
        'height',
        'device_type',    // desktop, mobile, tablet, all
        'supported_formats', // image, html5, native, text
        'is_active',
        'sort_order',
        'notes',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'supported_formats' => 'array',
    ];

    /**
     * Get all ad placements for this zone
     */
    public function placements()
    {
        return $this->hasMany(AdPlacement::class, 'zone_id');
    }

    /**
     * Get active campaigns for this zone
     */
    public function activeCampaigns()
    {
        return $this->belongsToMany(
            AdCampaign::class,
            'ad_placements',
            'zone_id',
            'campaign_id'
        )->where('ad_campaigns.status', 'active');
    }

    /**
     * Get all statistics for this zone
     */
    public function statistics()
    {
        return $this->hasMany(AdCampaignMetrics::class, 'zone_id');
    }

    /**
     * Get dimensions as string for display
     */
    public function getDimensions(): string
    {
        return "{$this->width}x{$this->height}";
    }

    /**
     * Check if zone is available for new campaigns
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->revive_zone_id !== null;
    }
}
