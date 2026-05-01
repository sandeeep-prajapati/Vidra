<?php

namespace App\Packages\StaffManagement\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $table = 'qualifications';

    protected $primaryKey = 'qualification_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = ['staff_id', 'degree', 'specialization', 'university_name', 'year_of_completion'];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
