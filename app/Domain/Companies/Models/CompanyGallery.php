<?php

namespace App\Domain\Companies\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyGallery extends Model
{
    protected $fillable = ['company_id', 'image_path', 'caption', 'sort_order'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
