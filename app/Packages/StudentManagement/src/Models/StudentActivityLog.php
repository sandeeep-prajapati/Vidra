<?php

namespace App\Packages\StudentManagement\Models;

use App\Packages\RbacManagement\Models\User;
use Illuminate\Database\Eloquent\Model;

class StudentActivityLog extends Model
{
    protected $table = 'student_activity_logs';

    protected $primaryKey = 'log_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    protected $fillable = [
        'student_id',
        'activity_type',
        'description',
        'old_value',
        'new_value',
        'changed_by',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
