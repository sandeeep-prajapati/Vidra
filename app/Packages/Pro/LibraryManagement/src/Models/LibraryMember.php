<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LibraryMember extends Model
{
    protected $table = 'library_members';
    protected $fillable = [
        'member_type', 'member_id', 'membership_number',
        'name', 'email', 'phone', 'max_books_allowed', 'status'
    ];
    protected $casts = ['status' => 'string'];
    public $timestamps = true;

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
        return $this->issues()->where('status', 'active')->count();
    }

    public function canBorrowMore(): bool
    {
        return $this->getActiveIssuesCount() < $this->max_books_allowed;
    }

    public function getTotalFinesAmount(): float
    {
        return (float) $this->fines()->where('status', 'pending')->sum('balance_amount');
    }
}
