<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityBooking extends Model
{
    protected $table = 'facility_bookings';
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'student_id',
        'facility_id',
        'booking_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function facility()
    {
        return $this->belongsTo(FacilityManagement::class, 'facility_id', 'facility_id');
    }
}
