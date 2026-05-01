<?php

namespace App\Packages\ExamManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StudentReportCard extends Model
{
    protected $table = 'student_report_cards';
    protected $primaryKey = 'report_card_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'student_id', 'exam_id', 'total_marks', 'maximum_marks',
        'overall_percentage', 'overall_grade', 'rank_in_class', 'remarks',
    ];

    protected $casts = [
        'total_marks'        => 'decimal:2',
        'maximum_marks'      => 'decimal:2',
        'overall_percentage' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Packages\StudentManagement\Models\Student::class, 'student_id', 'student_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'exam_id');
    }
}
