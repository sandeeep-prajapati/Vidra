<?php

namespace App\Packages\SubjectManagement\Models;

use App\Packages\ClassManagement\Models\SchoolClass;
use App\Packages\ClassManagement\Models\Section;
use App\Packages\StaffManagement\Models\Staff;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectMapping extends Model
{
    protected $table = 'teacher_subject_mappings';

    protected $primaryKey = 'mapping_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id',
        'section_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Staff::class, 'teacher_id', 'staff_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'section_id');
    }
}
