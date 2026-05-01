<?php

namespace App\Packages\StudentManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $primaryKey = 'student_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'blood_group',
        'nationality',
        'religion',
        'current_address',
        'permanent_address',
        'phone_number',
        'email',
        'profile_photo',
        'date_of_admission',
        'admission_number',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_admission' => 'date',
    ];

    public function parentInfo()
    {
        return $this->hasOne(ParentInfo::class, 'student_id');
    }

    public function previousEducations()
    {
        return $this->hasMany(PreviousEducation::class, 'student_id');
    }

    public function healthRecord()
    {
        return $this->hasOne(HealthRecord::class, 'student_id')->latestOfMany('health_id');
    }

    public function healthRecords()
    {
        return $this->hasMany(HealthRecord::class, 'student_id')->orderBy('created_at', 'desc');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'student_id');
    }

    public function promotionHistory()
    {
        return $this->hasMany(PromotionHistory::class, 'student_id');
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class, 'student_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(StudentActivityLog::class, 'student_id')->orderBy('created_at', 'desc');
    }

    public function contacts()
    {
        return $this->hasMany(StudentContact::class, 'student_id');
    }
}
