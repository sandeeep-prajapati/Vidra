<?php

namespace App\Packages\SubjectManagement\Models;

use App\Packages\ClassManagement\Models\AcademicYear;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $table = 'curriculums';

    protected $primaryKey = 'curriculum_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'subject_id',
        'academic_year_id',
        'description',
        'syllabus_document_path',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'academic_year_id');
    }

    public function lessonPlans()
    {
        return $this->hasMany(LessonPlan::class, 'curriculum_id');
    }
}
