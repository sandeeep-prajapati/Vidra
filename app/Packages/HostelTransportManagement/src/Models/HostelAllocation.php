<?php

namespace App\Packages\HostelTransportManagement\Models;

use Illuminate\Database\Eloquent\Model;

class HostelAllocation extends Model
{
    protected $table = 'hostel_allocations';

    protected $primaryKey = 'allocation_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        // Define fillable fields
    ];
}
