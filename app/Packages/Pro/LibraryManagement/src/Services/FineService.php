<?php

namespace App\Packages\Pro\LibraryManagement\Services;

use App\Packages\Pro\LibraryManagement\Models\LibraryFine;
use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class FineService
{
    private $finePerDay = 10.0;

    public function createFine($issueId, $overdueDays): ?LibraryFine
    {
        $issue = LibraryIssue::find($issueId);
        if (!$issue) {
            return null;
        }

        $fineAmount = $overdueDays * $this->finePerDay;

        return LibraryFine::create([
            'issue_id' => $issueId,
            'member_id' => $issue->member_id,
            'fine_amount' => $fineAmount,
            'paid_amount' => 0,
            'balance_amount' => $fineAmount,
            'status' => 'pending'
        ]);
    }

    public function recordPayment($fineId, $amount): bool
    {
        $fine = LibraryFine::find($fineId);
        return $fine ? $fine->recordPayment($amount) : false;
    }

    public function waiveFine($fineId, $reason = '', $waivedBy = null): bool
    {
        $fine = LibraryFine::find($fineId);
        return $fine ? $fine->waiveFine($reason, $waivedBy) : false;
    }

    public function getUnpaidFines($perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::where('status', 'pending')
            ->with(['member', 'issue.book'])
            ->paginate($perPage);
    }

    public function getPendingFines(): LengthAwarePaginator
    {
        return LibraryFine::whereIn('status', ['pending'])
            ->with(['member', 'issue.book'])
            ->paginate(15);
    }

    public function getCollectionReport(): array
    {
        return [
            'total_fines' => LibraryFine::sum('fine_amount'),
            'collected' => LibraryFine::where('status', 'paid')->sum('paid_amount'),
            'pending' => LibraryFine::where('status', 'pending')->sum('balance_amount'),
            'waived' => LibraryFine::where('status', 'waived')->count(),
        ];
    }

    public function getMemberFines($memberId): LengthAwarePaginator
    {
        return LibraryFine::where('member_id', $memberId)
            ->with('issue.book')
            ->paginate(15);
    }

    public function calculateFineForIssue($issueId): float
    {
        $issue = LibraryIssue::find($issueId);
        if (!$issue || !$issue->isOverdue()) {
            return 0;
        }

        return $issue->getOverdueDays() * $this->finePerDay;
    }
}
