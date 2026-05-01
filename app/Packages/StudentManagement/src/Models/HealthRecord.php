<?php

namespace App\Packages\StudentManagement\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    protected $table = 'health_records';

    protected $primaryKey = 'health_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'height_cm',
        'weight_kg',
        'blood_group',
        'allergies',
        'medical_conditions',
        'vaccination_status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
