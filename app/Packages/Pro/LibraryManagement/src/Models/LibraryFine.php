<?php

namespace App\Packages\Pro\LibraryManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryFine extends Model
{
    protected $table = 'library_fines';
    protected $fillable = [
        'issue_id', 'member_id', 'fine_amount', 'paid_amount',
        'balance_amount', 'status', 'waived_by', 'waived_reason'
    ];
    protected $casts = ['status' => 'string'];
    public $timestamps = true;

    public function issue(): BelongsTo
    {
        return $this->belongsTo(LibraryIssue::class, 'issue_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(LibraryMember::class, 'member_id');
    }

    public function recordPayment(float $amount): bool
    {
        if ($amount <= 0 || $amount > $this->balance_amount) {
            return false;
        }

        $this->update([
            'paid_amount' => $this->paid_amount + $amount,
            'balance_amount' => $this->balance_amount - $amount,
            'status' => $this->balance_amount - $amount == 0 ? 'paid' : 'pending'
        ]);

        return true;
    }

    public function waiveFine(string $reason = '', ?int $waived_by = null): bool
    {
        $this->update([
            'balance_amount' => 0,
            'status' => 'waived',
            'waived_by' => $waived_by,
            'waived_reason' => $reason
        ]);

        return true;
    }
}
