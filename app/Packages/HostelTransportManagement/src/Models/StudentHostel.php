<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class StudentHostel extends Model
{
    protected $table = 'students_hostel';

    protected $fillable = [
        'student_id',
        'hostel_id',
        'room_id',
        'assigned_date',
        'checkout_date',
    ];

    protected $casts = [
        'assigned_date'  => 'date',
        'checkout_date'  => 'date',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id', 'hostel_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }
}
