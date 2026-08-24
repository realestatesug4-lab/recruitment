<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represents an advertising campaign purchased by an advertiser
 * Lifecycle: draft → pending_payment → pending_review → approved → scheduled → active → completed
 */
class AdCampaign extends Model
{
    use HasFactory;

    protected $table = 'ad_campaigns';

    protected $fillable = [
        'advertiser_id',
        'order_id',
        'name',
        'description',
        'status',                  // draft, pending_payment, pending_review, approved, scheduled, active, completed, paused, rejected, cancelled
        'starts_at',
        'ends_at',
        'budget',                  // in smallest currency unit (cents)
        'budget_spent',
        'impression_goal',
        'click_goal',
        'conversion_goal',
        'type',                    // CPM, CPC, CPA, flat
        'revive_campaign_id',
        'approved_by',             // admin user ID
        'approved_at',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'budget' => 'integer',
        'budget_spent' => 'integer',
        'impression_goal' => 'integer',
        'click_goal' => 'integer',
        'conversion_goal' => 'integer',
        'approved_at' => 'datetime',
    ];

    /**
     * Campaign status options
     */
    public static function getStatuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending_payment' => 'Pending Payment',
            'pending_review' => 'Pending Review',
            'approved' => 'Approved',
            'scheduled' => 'Scheduled',
            'active' => 'Active',
            'paused' => 'Paused',
            'completed' => 'Completed',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        ];
    }

    /**
     * Get the advertiser for this campaign
     */
    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    /**
     * Get the order associated with this campaign
     */
    public function order()
    {
        return $this->belongsTo(AdOrder::class, 'order_id');
    }

    /**
     * Get all creatives (banners) for this campaign
     */
    public function creatives()
    {
        return $this->hasMany(Creative::class, 'campaign_id');
    }

    /**
     * Get all placements (zone links) for this campaign
     */
    public function placements()
    {
        return $this->hasMany(AdPlacement::class, 'campaign_id');
    }

    /**
     * Get metrics for this campaign
     */
    public function metrics()
    {
        return $this->hasMany(AdCampaignMetrics::class, 'campaign_id');
    }

    /**
     * Get total impressions to date
     */
    public function getTotalImpressions(): int
    {
        return $this->metrics()->sum('impressions') ?? 0;
    }

    /**
     * Get total clicks to date
     */
    public function getTotalClicks(): int
    {
        return $this->metrics()->sum('clicks') ?? 0;
    }

    /**
     * Get CTR (Click-Through Rate)
     */
    public function getCTR(): float
    {
        $impressions = $this->getTotalImpressions();
        return $impressions > 0 ? ($this->getTotalClicks() / $impressions) * 100 : 0;
    }

    /**
     * Check if campaign is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if campaign can be activated
     */
    public function canActivate(): bool
    {
        return in_array($this->status, ['approved', 'scheduled', 'paused']);
    }

    /**
     * Check if campaign can be paused
     */
    public function canPause(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if campaign can be cancelled
     */
    public function canCancel(): bool
    {
        return !in_array($this->status, ['completed', 'cancelled', 'rejected']);
    }

    /**
     * Activate the campaign
     */
    public function activate(): bool
    {
        if (!$this->canActivate()) {
            return false;
        }

        return $this->update(['status' => 'active']);
    }

    /**
     * Pause the campaign
     */
    public function pause(): bool
    {
        if (!$this->canPause()) {
            return false;
        }

        return $this->update(['status' => 'paused']);
    }

    /**
     * Cancel the campaign
     */
    public function cancel(): bool
    {
        if (!$this->canCancel()) {
            return false;
        }

        return $this->update(['status' => 'cancelled']);
    }

    /**
     * Approve the campaign
     */
    public function approve(int $userId): bool
    {
        if ($this->status !== 'pending_review') {
            return false;
        }

        return $this->update([
            'status' => 'approved',
            'approved_by' => $userId,
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the campaign
     */
    public function reject(int $userId, string $reason): bool
    {
        if ($this->status !== 'pending_review') {
            return false;
        }

        return $this->update([
            'status' => 'rejected',
            'approved_by' => $userId,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Check if budget is exceeded
     */
    public function isBudgetExceeded(): bool
    {
        return $this->budget > 0 && $this->budget_spent >= $this->budget;
    }

    /**
     * Get remaining budget
     */
    public function getRemainingBudget(): int
    {
        return max(0, $this->budget - $this->budget_spent);
    }
}
