<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryFine extends Model
{
    protected $table = 'library_fines';

    protected $fillable = [
        'issue_id',
        'member_id',
        'fine_amount',
        'fine_per_day',
        'overdue_days',
        'paid_amount',
        'balance_amount',
        'paid_at',
        'status',
        'waived_by',
        'waive_reason',
    ];

    protected $casts = [
        'fine_amount' => 'decimal:2',
        'fine_per_day' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(LibraryIssue::class, 'issue_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(LibraryMember::class, 'member_id');
    }

    public function waivedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'waived_by');
    }

    public function recordPayment(float $amount): bool
    {
        if ($amount <= 0 || $amount > $this->balance_amount) {
            return false;
        }

        $this->paid_amount += $amount;
        $this->balance_amount -= $amount;

        if ($this->balance_amount <= 0) {
            $this->status = 'paid';
            $this->paid_at = now();
            $this->balance_amount = 0;
        } else {
            $this->status = 'partial';
        }

        $this->save();
        return true;
    }

    public function waiveFine(int $userId, string $reason): void
    {
        $this->status = 'waived';
        $this->waived_by = $userId;
        $this->waive_reason = $reason;
        $this->balance_amount = 0;
        $this->save();
    }
}
