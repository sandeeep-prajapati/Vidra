<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityManagement extends Model
{
    protected $table = 'facility_management';
    protected $primaryKey = 'facility_id';

    protected $fillable = [
        'facility_name',
        'facility_type',
        'location',
        'capacity',
        'available_capacity',
    ];

    public function bookings()
    {
        return $this->hasMany(FacilityBooking::class, 'facility_id', 'facility_id');
    }
}
