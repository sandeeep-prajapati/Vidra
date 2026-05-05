<?php

namespace App\Packages\Pro\LibraryManagement\Repositories;

use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class IssueRepository
{
    public function getActive($perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('status', 'active')
            ->with(['book', 'member'])
            ->paginate($perPage);
    }

    public function getOverdue($perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('status', 'active')
            ->where('due_date', '<', Carbon::now()->toDateString())
            ->with(['book', 'member'])
            ->paginate($perPage);
    }

    public function getByMember($memberId, $perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('member_id', $memberId)
            ->with(['book', 'member'])
            ->paginate($perPage);
    }

    public function getById($id): ?LibraryIssue
    {
        return LibraryIssue::with(['book', 'member'])->find($id);
    }

    public function create(array $data): LibraryIssue
    {
        return LibraryIssue::create($data);
    }

    public function update($id, array $data): bool
    {
        return LibraryIssue::find($id)->update($data) ?? false;
    }

    public function getReturned($perPage = 15): LengthAwarePaginator
    {
        return LibraryIssue::where('status', 'returned')
            ->with(['book', 'member'])
            ->paginate($perPage);
    }
}
