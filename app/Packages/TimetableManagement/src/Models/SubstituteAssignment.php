<?php

namespace App\Packages\TimetableManagement\Models;

use App\Packages\StaffManagement\Models\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubstituteAssignment extends Model
{
    protected $table = 'substitute_assignments';
    protected $primaryKey = 'substitute_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'timetable_id',
        'original_teacher_id',
        'substitute_teacher_id',
        'date_of_substitution',
    ];

    protected $casts = [
        'date_of_substitution' => 'date',
    ];

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class, 'timetable_id', 'timetable_id');
    }

    public function originalTeacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'original_teacher_id', 'staff_id');
    }

    public function substituteTeacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'substitute_teacher_id', 'staff_id');
    }
}
