<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class TransportVehicle extends Model
{
    protected $table = 'transport_vehicles';

    protected $primaryKey = 'vehicle_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        // Define fillable fields
    ];
}
