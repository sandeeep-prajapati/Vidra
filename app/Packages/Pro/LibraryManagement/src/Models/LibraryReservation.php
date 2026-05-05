<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryReservation extends Model
{
    protected $table = 'library_reservations';

    protected $fillable = [
        'book_id',
        'member_id',
        'reserved_at',
        'notified_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'notified_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(LibraryMember::class, 'member_id');
    }

    public function markAsNotified(): void
    {
        $this->update([
            'status' => 'notified',
            'notified_at' => now(),
        ]);
    }

    public function markAsFulfilled(): void
    {
        $this->update(['status' => 'fulfilled']);
    }

    public function cancelReservation(): void
    {
        $this->update(['status' => 'cancelled']);
    }
}
