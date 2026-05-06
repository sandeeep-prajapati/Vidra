<?php

namespace App\Packages\Pro\AlumniManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniEducation extends Model
{
    protected $table = 'alumni_education';

    protected $fillable = [
        'alumni_id',
        'institution_name',
        'degree',
        'field_of_study',
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
