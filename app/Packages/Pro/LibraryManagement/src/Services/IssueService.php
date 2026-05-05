<?php

namespace App\Packages\Pro\LibraryManagement\Services;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use App\Packages\Pro\LibraryManagement\Models\LibraryMember;
use App\Packages\Pro\LibraryManagement\Models\LibraryFine;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class IssueService
{
    public function __construct(
        private FineService $fineService
    ) {}

    public function issueBook(
        LibraryBook $book,
        LibraryMember $member,
        int $userId,
        int $issueDays = 14
    ): LibraryIssue {
        if (!$member->canBorrowMore()) {
            throw new Exception('Member has reached maximum book limit or account is inactive');
        }

        if (!$book->isAvailable()) {
            throw new Exception('Book is not available');
        }

        $issue = LibraryIssue::create([
            'book_id' => $book->id,
            'member_id' => $member->id,
            'issued_by' => $userId,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays($issueDays)->toDateString(),
            'status' => 'issued',
        ]);

        $book->decrement('available_copies');

        return $issue;
    }

    public function returnBook(LibraryIssue $issue): LibraryIssue
    {
        $issue->update([
            'return_date' => now()->toDateString(),
            'status' => 'returned',
        ]);

        $issue->book->increment('available_copies');

        // Calculate and create fine if overdue
        if ($issue->isOverdue()) {
            $overdueDays = $issue->getOverdueDays();
            $this->fineService->createFine($issue, $overdueDays);
        }

        return $issue->fresh();
    }

    public function markOverdueBooks(): int
    {
        $count = 0;
        LibraryIssue::where('status', '!=', 'returned')
            ->where('due_date', '<', now()->toDateString())
            ->each(function (LibraryIssue $issue) use (&$count) {
                $issue->markAsOverdue();
                $count++;
            });

        return $count;
    }

    public function getOverdueIssues(): Collection
    {
        return LibraryIssue::where('status', 'overdue')
            ->with('book', 'member')
            ->get();
    }

    public function getActiveIssues(): Collection
    {
        return LibraryIssue::whereIn('status', ['issued', 'overdue'])
            ->with('book', 'member')
            ->get();
    }

    public function getMemberIssueHistory(LibraryMember $member): Collection
    {
        return $member->issues()
            ->with('book')
            ->orderBy('issue_date', 'desc')
            ->get();
    }
}
