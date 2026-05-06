<?php

namespace App\Packages\Pro\AlumniManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniEmployment extends Model
{
    protected $table = 'alumni_employment';

    protected $fillable = [
        'alumni_id',
        'company_name',
        'designation',
        'industry',
        'start_year',
        'end_year',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(AlumniProfile::class, 'alumni_id');
    }
}
