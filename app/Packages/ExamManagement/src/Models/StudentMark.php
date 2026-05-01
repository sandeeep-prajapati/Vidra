<?php

namespace App\Packages\ExamManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMark extends Model
{
    protected $table = 'student_marks';
    protected $primaryKey = 'mark_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'student_id', 'schedule_id', 'marks_obtained', 'grade', 'remarks',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Packages\StudentManagement\Models\Student::class, 'student_id', 'student_id');
    }

    public function schedule()
    {
        return $this->belongsTo(ExamSchedule::class, 'schedule_id', 'schedule_id');
    }
}
