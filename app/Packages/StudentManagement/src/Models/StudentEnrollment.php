<?php

namespace App\Packages\StudentManagement\Models;

use App\Packages\ClassManagement\Models\AcademicYear;
use App\Packages\ClassManagement\Models\Batch;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    protected $table = 'student_enrollments';

    protected $primaryKey = 'enrollment_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'batch_id',
        'academic_year_id',
        'enrollment_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
