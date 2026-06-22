<?php
namespace App\Domain\Companies\Models;

use App\Domain\Jobs\Models\Job;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\database\Eloquent\Factories\HasFactory;
use Database\Factories\CompanyFactory;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasUuids, SoftDeletes, HasFactory;

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }

    protected $fillable = [
        'uuid',
        'slug',
        'name',
        'description',
        'industry',
        'website',
        'logo_url',
        'verification_status',
        'is_featured',
        'color',
        'size',
        'location',
        'owner_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (!$company->slug) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function jobs(): HasMany { return $this->hasMany(Job::class); }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }
    public function galleries(): HasMany { return $this->hasMany(CompanyGallery::class); }
    public function reviews(): HasMany { return $this->hasMany(CompanyReview::class); }
}
