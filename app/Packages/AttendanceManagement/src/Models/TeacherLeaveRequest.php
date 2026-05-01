<?php

namespace App\Packages\AttendanceManagement\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherLeaveRequest extends Model
{
    protected $table = 'teacher_leave_requests';

    protected $primaryKey = 'leave_request_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'staff_id',
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

    public function staff()
    {
        return $this->belongsTo(\App\Packages\StaffManagement\Models\Staff::class, 'staff_id', 'staff_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(\App\Packages\StaffManagement\Models\Staff::class, 'approved_by', 'staff_id');
    }
}
