<?php

namespace App\Packages\TimetableManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Day extends Model
{
    protected $table = 'days';
    protected $primaryKey = 'day_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'day_name',
    ];

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'day_id', 'day_id');
    }
}
