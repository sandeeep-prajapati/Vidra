<?php

namespace App\Packages\Pro\AlumniManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlumniEvent extends Model
{
    protected $table = 'alumni_events';

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'venue',
        'event_type',
        'organizer_alumni_id',
        'max_attendees',
        'registration_deadline',
        'status',
    ];

    protected $casts = [
        'event_date'            => 'datetime',
        'registration_deadline' => 'date',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(AlumniProfile::class, 'organizer_alumni_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(AlumniEventRegistration::class, 'event_id');
    }

    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->count();
    }

    public function isFull(): bool
    {
        return $this->max_attendees && $this->registered_count >= $this->max_attendees;
    }
}
