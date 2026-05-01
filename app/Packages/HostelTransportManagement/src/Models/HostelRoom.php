<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    protected $table = 'hostel_rooms';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        // Define fillable fields
    ];
}
