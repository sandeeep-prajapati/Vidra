<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $primaryKey = 'staff_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'first_name', 'last_name', 'date_of_birth', 'gender',
        'phone_number', 'email', 'address', 'nationality',
        'joining_date', 'department_id', 'designation',
        'employment_type', 'status', 'photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departmentAssignments()
    {
        return $this->hasMany(StaffDepartmentAssignment::class, 'staff_id');
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'staff_department_assignments', 'staff_id', 'department_id')
            ->withPivot(['is_primary', 'assigned_date'])
            ->withTimestamps();
    }

    public function qualifications()
    {
        return $this->hasMany(Qualification::class, 'staff_id');
    }

    public function teacherAssignments()
    {
        return $this->hasMany(TeacherAssignment::class, 'staff_id');
    }

    public function attendances()
    {
        return $this->hasMany(StaffAttendance::class, 'staff_id');
    }

    public function salaries()
    {
        return $this->hasMany(SalaryDetail::class, 'staff_id')->orderByDesc('payment_date');
    }

    public function latestSalary()
    {
        return $this->hasOne(SalaryDetail::class, 'staff_id')->latestOfMany('salary_id');
    }

    public function performanceReviews()
    {
        return $this->hasMany(PerformanceReview::class, 'staff_id')->orderByDesc('review_period_end');
    }

    public function leaveRequests()
    {
        return $this->hasMany(StaffLeaveRequest::class, 'staff_id')->orderByDesc('start_date');
    }
}
