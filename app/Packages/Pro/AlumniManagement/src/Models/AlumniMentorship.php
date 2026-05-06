<?php

namespace App\Packages\Pro\AlumniManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniMentorship extends Model
{
    protected $table = 'alumni_mentorships';

    protected $fillable = [
        'mentor_alumni_id',
        'mentee_student_id',
        'mentee_name',
        'area_of_mentorship',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(AlumniProfile::class, 'mentor_alumni_id');
    }
}
