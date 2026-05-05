<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryMember extends Model
{
    protected $table = 'library_members';

    protected $fillable = [
        'member_type',
        'member_id',
        'membership_number',
        'max_books_allowed',
        'membership_start',
        'membership_end',
        'status',
    ];

    protected $casts = [
        'membership_start' => 'date',
        'membership_end' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(LibraryIssue::class, 'member_id');
    }

    public function fines(): HasMany
    {
        return $this->hasMany(LibraryFine::class, 'member_id');
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(LibraryReservation::class, 'member_id');
    }

    public function getActiveIssuesCount(): int
    {
        return $this->issues()
            ->whereIn('status', ['issued', 'overdue'])
            ->count();
    }

    public function canBorrowMore(): bool
    {
        return $this->getActiveIssuesCount() < $this->max_books_allowed && $this->status === 'active';
    }

    public function getTotalFinesAmount(): float
    {
        return (float) $this->fines()
            ->where('status', '!=', 'paid')
            ->sum('balance_amount');
    }
}
