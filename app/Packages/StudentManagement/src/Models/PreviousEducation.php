<?php

namespace App\Packages\StudentManagement\Models;

use Illuminate\Database\Eloquent\Model;

class PreviousEducation extends Model
{
    protected $table = 'previous_educations';

    protected $primaryKey = 'education_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'school_name',
        'board',
        'class_completed',
        'percentage',
        'year_of_passing',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
