<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Transportation extends Model
{
    protected $table = 'transportation';
    protected $primaryKey = 'transport_id';

    protected $fillable = [
        'transport_name',
        'transport_type',
        'capacity',
        'route',
        'departure_time',
    ];

    public function studentTransports()
    {
        return $this->hasMany(StudentTransport::class, 'transport_id', 'transport_id');
    }
}
