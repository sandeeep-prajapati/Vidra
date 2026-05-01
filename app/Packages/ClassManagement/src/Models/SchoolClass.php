<?php

namespace App\Packages\ClassManagement\Models;

use App\Packages\StudentManagement\Models\Student;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $primaryKey = 'class_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'class_name',
        'class_code',
        'description',
        'academic_year_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'class_id');
    }

    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            Batch::class,
            'class_id',
            'student_id',
            'class_id',
            'student_id'
        )->via('enrollments');
    }
}
