<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $table = 'hostels';
    protected $primaryKey = 'hostel_id';

    protected $fillable = [
        'hostel_name',
        'hostel_type',
        'total_capacity',
        'available_capacity',
        'location',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hostel_id', 'hostel_id');
    }

    public function studentHostels()
    {
        return $this->hasMany(StudentHostel::class, 'hostel_id', 'hostel_id');
    }
}
