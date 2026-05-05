<?php

namespace App\Packages\Pro\LibraryManagement\Repositories;

use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class IssueRepository
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::with('book', 'member')
            ->orderBy('issue_date', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?LibraryIssue
    {
        return LibraryIssue::with('book', 'member', 'fine')->find($id);
    }

    public function getActive(int $perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::whereIn('status', ['issued', 'overdue'])
            ->with('book', 'member')
            ->orderBy('due_date', 'asc')
            ->paginate($perPage);
    }

    public function getOverdue(int $perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('status', 'overdue')
            ->with('book', 'member')
            ->orderBy('due_date', 'asc')
            ->paginate($perPage);
    }

    public function getMemberIssues(int $memberId, int $perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('member_id', $memberId)
            ->with('book')
            ->orderBy('issue_date', 'desc')
            ->paginate($perPage);
    }

    public function getByBookId(int $bookId): Collection
    {
        return LibraryIssue::where('book_id', $bookId)
            ->where('status', '!=', 'returned')
            ->with('member')
            ->get();
    }
}
