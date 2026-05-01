<?php

namespace App\Packages\AttendanceManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'holidays';

    protected $primaryKey = 'holiday_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'title',
        'date',
        'description',
        'is_recurring',
    ];

    protected $casts = [
        'date'         => 'date',
        'is_recurring' => 'boolean',
    ];
}
