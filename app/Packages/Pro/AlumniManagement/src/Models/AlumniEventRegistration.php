<?php

namespace App\Packages\Pro\AlumniManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniEventRegistration extends Model
{
    protected $table = 'alumni_event_registrations';

    protected $fillable = [
        'event_id',
        'alumni_id',
        'registered_at',
        'attended',
        'feedback',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'attended'      => 'boolean',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(AlumniEvent::class, 'event_id');
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(AlumniProfile::class, 'alumni_id');
    }
}
