<?php

namespace App\Packages\StudentManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StudentContact extends Model
{
    protected $table = 'student_contacts';

    protected $primaryKey = 'contact_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'contact_type',
        'contact_value',
        'is_primary',
        'label',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
