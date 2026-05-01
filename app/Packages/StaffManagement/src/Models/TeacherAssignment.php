<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAssignment extends Model
{
    protected $table = 'teacher_assignments';

    protected $primaryKey = 'assignment_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['staff_id', 'class_name', 'subject'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
