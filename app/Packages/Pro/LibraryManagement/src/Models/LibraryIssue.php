<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class LibraryIssue extends Model
{
    protected $table = 'library_issues';
    protected $fillable = [
        'book_id', 'member_id', 'issued_by', 'issue_date', 'due_date',
        'return_date', 'status'
    ];
    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
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

    public function fines(): HasMany
    {
        return $this->hasMany(LibraryFine::class, 'issue_id');
    }

    public function isOverdue(): bool
    {
        return $this->status === 'active' && Carbon::now()->isAfter($this->due_date);
    }

    public function getOverdueDays(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        return Carbon::now()->diffInDays($this->due_date);
    }

    public function markAsOverdue(): void
    {
        $this->update(['status' => 'overdue']);
    }
}
