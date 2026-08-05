<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'advertiser_id',
        'name',
        'type',
        'objective',
        'budget_total',
        'budget_spent',
        'start_at',
        'end_at',
        'status',
        'priority',
        'targeting',
        'external_campaign_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'targeting' => 'array',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function creatives()
    {
        return $this->hasMany(Creative::class);
    }
}
