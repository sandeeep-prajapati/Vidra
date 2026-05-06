<?php

namespace App\Packages\Pro\AlumniManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniDonation extends Model
{
    protected $table = 'alumni_donations';

    protected $fillable = [
        'alumni_id',
        'amount',
        'currency',
        'purpose',
        'donated_at',
        'receipt_number',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'donated_at' => 'date',
    ];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(AlumniProfile::class, 'alumni_id');
    }
}
