<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class TransportSchedule extends Model
{
    protected $table = 'transport_schedules';

    protected $primaryKey = 'schedule_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        // Define fillable fields
    ];
}
