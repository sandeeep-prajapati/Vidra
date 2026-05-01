<?php

namespace App\Packages\AttendanceManagement\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $table = 'teacher_attendance';

    protected $primaryKey = 'attendance_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'staff_id',
        'date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(\App\Packages\StaffManagement\Models\Staff::class, 'staff_id', 'staff_id');
    }
}
