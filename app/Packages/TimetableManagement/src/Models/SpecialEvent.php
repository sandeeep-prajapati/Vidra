<?php

namespace App\Packages\TimetableManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialEvent extends Model
{
    protected $table = 'special_events';
    protected $primaryKey = 'event_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'event_name',
        'event_date',
        'start_time',
        'end_time',
        'description',
        'room_id',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'room_id');
    }
}
