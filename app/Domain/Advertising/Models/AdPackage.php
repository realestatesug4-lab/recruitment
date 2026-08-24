<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Predefined advertising packages with pricing
 * Example: Employer Spotlight, Homepage Banner, Premium Campaign
 */
class AdPackage extends Model
{
    use HasFactory;

    protected $table = 'ad_packages';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',                   // in smallest currency unit (cents/pesewas)
        'currency',                // USD, UGX, KES, etc.
        'billing_type',            // CPM (per 1000 impressions), CPC (per click), flat
        'impression_limit',        // null = unlimited
        'click_limit',             // null = unlimited
        'duration_days',           // null = custom duration
        'zones_included',          // JSON array of allowed zones
        'supported_formats',       // image, html5, native, etc.
        'daily_impression_limit',  // null = unlimited per day
        'discount_tier_1',        // discounts for bulk
        'discount_tier_2',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'integer',
        'impression_limit' => 'integer',
        'click_limit' => 'integer',
        'duration_days' => 'integer',
        'daily_impression_limit' => 'integer',
        'zones_included' => 'array',
        'supported_formats' => 'array',
        'discount_tier_1' => 'float',
        'discount_tier_2' => 'float',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all orders for this package
     */
    public function orders()
    {
        return $this->hasMany(AdOrder::class, 'package_id');
    }

    /**
     * Calculate price with discount
     */
    public function calculatePrice(int $quantity = 1): int
    {
        $price = $this->price;

        if ($this->discount_tier_1 && $quantity >= 3) {
            $price = (int)($price * (1 - $this->discount_tier_1 / 100));
        }

        if ($this->discount_tier_2 && $quantity >= 6) {
            $price = (int)($price * (1 - $this->discount_tier_2 / 100));
        }

        return $price;
    }

    /**
     * Format price for display
     */
    public function getFormattedPrice(): string
    {
        return number_format($this->price / 100, 2) . ' ' . $this->currency;
    }
}
