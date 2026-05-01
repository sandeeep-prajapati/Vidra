<?php

namespace App\Packages\ExamManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = 'exams';
    protected $primaryKey = 'exam_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'exam_name', 'academic_year_id', 'start_date', 'end_date', 'description', 'is_final',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_final'   => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(\App\Packages\ClassManagement\Models\AcademicYear::class, 'academic_year_id', 'academic_year_id');
    }

    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class, 'exam_id', 'exam_id');
    }

    public function reportCards()
    {
        return $this->hasMany(StudentReportCard::class, 'exam_id', 'exam_id');
    }
}
