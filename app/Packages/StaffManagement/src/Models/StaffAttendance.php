<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $table = 'staff_attendance';

    protected $primaryKey = 'attendance_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['staff_id', 'date', 'status', 'remarks'];

    protected $casts = [
        'date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
