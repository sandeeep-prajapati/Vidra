<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLeaveRequest extends Model
{
    protected $table = 'staff_leave_requests';

    protected $primaryKey = 'leave_request_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'staff_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'remarks',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
