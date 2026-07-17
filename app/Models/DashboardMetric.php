<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardMetric extends Model
{
    protected $fillable = [
        'metric_key',
        'period',
        'date',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
