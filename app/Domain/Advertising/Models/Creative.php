<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Creative extends Model
{
    use HasFactory;

    protected $table = 'creatives';

    protected $fillable = [
        'campaign_id',
        'name',
        'type',
        'format',
        'title',
        'body',
        'image_url',
        'click_url',
        'cta_text',
        'html',
        'width',
        'height',
        'status',
        'metadata',
        'external_banner_id',
        'weight',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
