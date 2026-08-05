<?php

namespace App\Domain\Advertising\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertiser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'website',
        'contact_name',
        'contact_email',
        'status',
        'external_advertiser_id',
        'notes',
    ];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }
}
