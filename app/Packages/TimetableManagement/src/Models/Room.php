<?php

namespace App\Packages\TimetableManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'room_name',
        'room_type',
        'capacity',
        'description',
    ];

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'room_id', 'room_id');
    }

    public function specialEvents(): HasMany
    {
        return $this->hasMany(SpecialEvent::class, 'room_id', 'room_id');
    }
}
