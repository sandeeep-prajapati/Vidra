<?php

namespace App\Packages\ExamManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $table = 'exam_schedules';
    protected $primaryKey = 'schedule_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'exam_id', 'class_id', 'subject_id', 'exam_date',
        'start_time', 'end_time', 'total_marks', 'passing_marks',
    ];

    protected $casts = [
        'exam_date'  => 'date',
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'exam_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(\App\Packages\ClassManagement\Models\SchoolClass::class, 'class_id', 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Packages\SubjectManagement\Models\Subject::class, 'subject_id', 'subject_id');
    }

    public function studentMarks()
    {
        return $this->hasMany(StudentMark::class, 'schedule_id', 'schedule_id');
    }
}
