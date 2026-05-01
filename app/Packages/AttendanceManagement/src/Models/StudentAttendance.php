<?php

namespace App\Packages\AttendanceManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    protected $table = 'student_attendance';

    protected $primaryKey = 'attendance_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'batch_id',
        'remarks',
        'marked_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Packages\StudentManagement\Models\Student::class, 'student_id', 'student_id');
    }

    public function batch()
    {
        return $this->belongsTo(\App\Packages\ClassManagement\Models\Batch::class, 'batch_id', 'batch_id');
    }

    public function markedBy()
    {
        return $this->belongsTo(\App\Packages\StaffManagement\Models\Staff::class, 'marked_by', 'staff_id');
    }
}
