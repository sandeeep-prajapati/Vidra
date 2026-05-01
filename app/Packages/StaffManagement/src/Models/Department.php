<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $primaryKey = 'department_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['department_name', 'description'];

    public function staff()
    {
        return $this->hasMany(Staff::class, 'department_id');
    }

    public function staffAssignments()
    {
        return $this->hasMany(StaffDepartmentAssignment::class, 'department_id');
    }

    public function assignedStaff()
    {
        return $this->belongsToMany(Staff::class, 'staff_department_assignments', 'department_id', 'staff_id')
            ->withPivot(['is_primary', 'assigned_date'])
            ->withTimestamps();
    }
}
