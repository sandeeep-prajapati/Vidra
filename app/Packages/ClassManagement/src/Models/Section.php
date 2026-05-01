<?php

namespace App\Packages\ClassManagement\Models;

use App\Packages\StudentManagement\Models\Student;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';

    protected $primaryKey = 'section_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'section_name',
        'class_id',
        'capacity',
        'class_teacher_id',
        'is_active',
        'description',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function classTeacher()
    {
        return $this->belongsTo(\App\Packages\StaffManagement\Models\Staff::class, 'class_teacher_id', 'staff_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'section_id');
    }

    public function currentStudents()
    {
        return $this->hasManyThrough(
            Student::class,
            Batch::class,
            'section_id',
            'student_id',
            'section_id',
            'student_id'
        )->via('enrollments');
    }
}
