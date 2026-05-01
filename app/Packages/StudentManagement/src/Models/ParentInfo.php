<?php

namespace App\Packages\StudentManagement\Models;

use Illuminate\Database\Eloquent\Model;

class ParentInfo extends Model
{
    protected $table = 'parents';

    protected $primaryKey = 'parent_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'father_name',
        'father_phone',
        'father_email',
        'father_occupation',
        'mother_name',
        'mother_phone',
        'mother_email',
        'mother_occupation',
        'guardian_name',
        'guardian_relationship',
        'guardian_phone',
        'guardian_address',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
