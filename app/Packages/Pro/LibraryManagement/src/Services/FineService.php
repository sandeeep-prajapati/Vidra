<?php

namespace App\Packages\Pro\LibraryManagement\Services;

use App\Packages\Pro\LibraryManagement\Models\LibraryFine;
use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use App\Packages\Pro\LibraryManagement\Models\LibraryMember;
use Illuminate\Database\Eloquent\Collection;

class FineService
{
    private float $finePerDay = 10.0; // Default fine per day in rupees

    public function __construct()
    {
        $this->finePerDay = config('library.fine_per_day', 10.0);
    }

    public function createFine(LibraryIssue $issue, int $overdueDays): LibraryFine
    {
        $fineAmount = $overdueDays * $this->finePerDay;

        return LibraryFine::create([
            'issue_id' => $issue->id,
            'member_id' => $issue->member_id,
            'fine_amount' => $fineAmount,
            'fine_per_day' => $this->finePerDay,
            'overdue_days' => $overdueDays,
            'paid_amount' => 0,
            'balance_amount' => $fineAmount,
            'status' => 'pending',
        ]);
    }

    public function recordPayment(LibraryFine $fine, float $amount): bool
    {
        return $fine->recordPayment($amount);
    }

    public function waiveFine(LibraryFine $fine, int $userId, string $reason): void
    {
        $fine->waiveFine($userId, $reason);
    }

    public function getMemberPendingFines(LibraryMember $member): Collection
    {
        return $member->fines()
            ->where('status', '!=', 'paid')
            ->with('issue.book')
            ->get();
    }

    public function getTotalPendingAmount(LibraryMember $member): float
    {
        return (float) $member->fines()
            ->where('status', '!=', 'paid')
            ->sum('balance_amount');
    }

    public function getUnpaidFines(): Collection
    {
        return LibraryFine::where('status', '!=', 'paid')
            ->with('member', 'issue.book')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getCollectionReport(): array
    {
        $fines = LibraryFine::all();

        return [
            'total_fines' => $fines->sum('fine_amount'),
            'collected' => $fines->where('status', 'paid')->sum('paid_amount'),
            'pending' => $fines->whereIn('status', ['pending', 'partial'])->sum('balance_amount'),
            'waived' => $fines->where('status', 'waived')->sum('fine_amount'),
        ];
    }
}
