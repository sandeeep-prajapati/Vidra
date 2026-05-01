<?php

namespace App\Packages\SubjectManagement\Models;

use App\Packages\ClassManagement\Models\SchoolClass;
use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $table = 'class_subjects';

    protected $primaryKey = 'class_subject_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'class_id',
        'subject_id',
        'is_mandatory',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }
}
