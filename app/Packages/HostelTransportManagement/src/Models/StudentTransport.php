<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransport extends Model
{
    protected $table = 'students_transport';

    protected $fillable = [
        'student_id',
        'transport_id',
        'pickup_location',
        'drop_location',
        'assigned_date',
        'leave_date',
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'leave_date'    => 'date',
    ];

    public function transportation()
    {
        return $this->belongsTo(Transportation::class, 'transport_id', 'transport_id');
    }
}
