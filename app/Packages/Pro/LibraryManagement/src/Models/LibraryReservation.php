<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryReservation extends Model
{
    protected $table = 'library_reservations';
    protected $fillable = ['book_id', 'member_id', 'reserved_at', 'status'];
    protected $casts = [
        'reserved_at' => 'datetime',
        'status' => 'string'
    ];
    public $timestamps = true;

    public function book(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class, 'book_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(LibraryMember::class, 'member_id');
    }
}
