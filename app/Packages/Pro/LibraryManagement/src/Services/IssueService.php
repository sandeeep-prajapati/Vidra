<?php

namespace App\Packages\Pro\LibraryManagement\Services;

use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryMember;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class IssueService
{
    public function issueBook($bookId, $memberId, $issuedBy, $daysToIssue = 14): ?LibraryIssue
    {
        $book = LibraryBook::find($bookId);
        $member = LibraryMember::find($memberId);

        if (!$book || !$member || !$book->isAvailable() || !$member->canBorrowMore()) {
            return null;
        }

        $issue = LibraryIssue::create([
            'book_id' => $bookId,
            'member_id' => $memberId,
            'issued_by' => $issuedBy,
            'issue_date' => Carbon::now()->toDateString(),
            'due_date' => Carbon::now()->addDays($daysToIssue)->toDateString(),
            'status' => 'active'
        ]);

        $book->decrement('available_copies');

        return $issue;
    }

    public function returnBook($issueId, $fineAmount = 0): bool
    {
        $issue = LibraryIssue::find($issueId);
        if (!$issue || $issue->status !== 'active') {
            return false;
        }

        $issue->update([
            'return_date' => Carbon::now()->toDateString(),
            'status' => 'returned'
        ]);

        $book = $issue->book;
        $book->increment('available_copies');

        return true;
    }

    public function getActiveIssues($perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('status', 'active')
            ->with(['book', 'member'])
            ->paginate($perPage);
    }

    public function getOverdueIssues(): LengthAwarePaginator
    {
        return LibraryIssue::where('status', 'active')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->with(['book', 'member'])
            ->paginate(15);
    }

    public function getMemberIssues($memberId, $perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('member_id', $memberId)
            ->with(['book', 'member'])
            ->paginate($perPage);
    }

    public function markOverdueBooks(): int
    {
        return LibraryIssue::where('status', 'active')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->update(['status' => 'overdue']);
    }
}
