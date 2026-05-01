<?php

namespace App\Packages\TimetableManagement\Models;

use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\ClassManagement\Models\Section;
use App\Packages\StaffManagement\Models\Staff;
use App\Packages\SubjectManagement\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timetable extends Model
{
    protected $table = 'timetables';
    protected $primaryKey = 'timetable_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'class_id',
        'section_id',
        'academic_year_id',
        'day_id',
        'period_id',
        'subject_id',
        'teacher_id',
        'room_id',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'academic_year_id');
    }

    public function day(): BelongsTo
    {
        return $this->belongsTo(Day::class, 'day_id', 'day_id');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id', 'period_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'teacher_id', 'staff_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }

    public function substituteAssignments(): HasMany
    {
        return $this->hasMany(SubstituteAssignment::class, 'timetable_id', 'timetable_id');
    }
}
