<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class LibraryIssue extends Model
{
    protected $table = 'library_issues';

    protected $fillable = [
        'book_id',
        'member_id',
        'issued_by',
        'issue_date',
        'due_date',
        'return_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
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

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'issued_by');
    }

    public function fine(): HasOne
    {
        return $this->hasOne(LibraryFine::class, 'issue_id');
    }

    public function isOverdue(): bool
    {
        return $this->status !== 'returned' && Carbon::now()->gt($this->due_date);
    }

    public function getOverdueDays(): int
    {
        if ($this->status === 'returned') {
            return max(0, $this->return_date->diffInDays($this->due_date));
        }
        return max(0, Carbon::now()->diffInDays($this->due_date));
    }

    public function markAsOverdue(): void
    {
        if ($this->isOverdue()) {
            $this->update(['status' => 'overdue']);
        }
    }
}
