<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Daily statistics aggregated from Revive Adserver
 * Provides reporting layer without querying Revive on every request
 */
class AdCampaignMetrics extends Model
{
    use HasFactory;

    protected $table = 'ad_campaign_metrics';

    protected $fillable = [
        'campaign_id',
        'zone_id',
        'creative_id',
        'date',
        'requests',
        'impressions',
        'clicks',
        'conversions',
        'ctr',            // click-through rate (%)
        'ctr_rank',       // rank for this zone on this date
        'ecpm',           // effective cost per 1000 impressions
        'revenue',
        'synced_at',
    ];

    protected $casts = [
        'date' => 'date',
        'requests' => 'integer',
        'impressions' => 'integer',
        'clicks' => 'integer',
        'conversions' => 'integer',
        'ctr' => 'float',
        'ctr_rank' => 'integer',
        'ecpm' => 'float',
        'revenue' => 'integer',
        'synced_at' => 'datetime',
    ];

    /**
     * Get the campaign for these metrics
     */
    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class);
    }

    /**
     * Get the zone for these metrics
     */
    public function zone()
    {
        return $this->belongsTo(AdZone::class);
    }

    /**
     * Get the creative for these metrics
     */
    public function creative()
    {
        return $this->belongsTo(Creative::class);
    }

    /**
     * Scope: metrics for a specific date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope: metrics for active campaigns only
     */
    public function scopeActiveCampaigns($query)
    {
        return $query->whereHas('campaign', fn ($q) => $q->where('status', 'active'));
    }

    /**
     * Calculate CTR from impressions and clicks
     */
    public static function calculateCTR(int $clicks, int $impressions): float
    {
        if ($impressions <= 0) {
            return 0;
        }
        return ($clicks / $impressions) * 100;
    }

    /**
     * Calculate eCPM (effective cost per thousand impressions)
     */
    public static function calculateECPM(int $revenue, int $impressions): float
    {
        if ($impressions <= 0) {
            return 0;
        }
        return ($revenue / ($impressions / 1000));
    }
}
