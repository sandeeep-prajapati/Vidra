<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'hostel_rooms';
    protected $primaryKey = 'room_id';

    protected $fillable = [
        'hostel_id',
        'room_number',
        'room_type',
        'capacity',
        'occupied',
    ];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id', 'hostel_id');
    }

    public function studentHostels()
    {
        return $this->hasMany(StudentHostel::class, 'room_id', 'room_id');
    }
}
