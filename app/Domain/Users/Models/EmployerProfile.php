<?php
namespace App\Domain\Users\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\Companies\Models\Company;

class EmployerProfile extends Model
{
    protected $table = 'employer_profiles';

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'job_title',
        'phone',
        'bio'
    ];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
}
