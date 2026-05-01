<?php

namespace App\Packages\SubjectManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $primaryKey = 'subject_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'subject_name',
        'subject_code',
        'subject_type',
        'description',
        'is_optional',
    ];

    protected $casts = [
        'is_optional' => 'boolean',
    ];

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'subject_id');
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class, 'subject_id');
    }

    public function textbooks()
    {
        return $this->hasMany(Textbook::class, 'subject_id');
    }

    public function teacherMappings()
    {
        return $this->hasMany(TeacherSubjectMapping::class, 'subject_id');
    }
}
