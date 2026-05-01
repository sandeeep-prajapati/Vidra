<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StaffDepartmentAssignment extends Model
{
    protected $table = 'staff_department_assignments';

    protected $primaryKey = 'assignment_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'staff_id',
        'department_id',
        'is_primary',
        'assigned_date',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'assigned_date' => 'date',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
