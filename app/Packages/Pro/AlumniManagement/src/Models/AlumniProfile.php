<?php

namespace App\Packages\Pro\AlumniManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlumniProfile extends Model
{
    use HasFactory;

    protected $table = 'alumni_profiles';

    protected $fillable = [
        'student_id',
        'full_name',
        'email',
        'phone',
        'graduation_year',
        'graduation_class',
        'current_city',
        'current_country',
        'profile_photo',
        'bio',
        'linkedin_url',
        'website_url',
        'is_verified',
        'verified_at',
        'verified_by',
        'status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function education(): HasMany
    {
        return $this->hasMany(AlumniEducation::class, 'alumni_id');
    }

    public function employment(): HasMany
    {
        return $this->hasMany(AlumniEmployment::class, 'alumni_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(AlumniDonation::class, 'alumni_id');
    }

    public function mentorships(): HasMany
    {
        return $this->hasMany(AlumniMentorship::class, 'mentor_alumni_id');
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(AlumniEventRegistration::class, 'alumni_id');
    }

    public function getCurrentEmploymentAttribute(): ?AlumniEmployment
    {
        return $this->employment()->where('is_current', true)->first();
    }

    public function getCurrentEducationAttribute(): ?AlumniEducation
    {
        return $this->education()->where('is_current', true)->first();
    }
}
