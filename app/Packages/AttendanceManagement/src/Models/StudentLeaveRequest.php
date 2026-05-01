<?php

namespace App\Packages\AttendanceManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLeaveRequest extends Model
{
    protected $table = 'student_leave_requests';

    protected $primaryKey = 'leave_request_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'applied_on',
        'approved_by',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'applied_on'  => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Packages\StudentManagement\Models\Student::class, 'student_id', 'student_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Packages\StaffManagement\Models\Staff::class, 'approved_by', 'staff_id');
    }
}
