<?php

namespace App\Packages\Pro\LibraryManagement\Repositories;

use App\Packages\Pro\LibraryManagement\Models\LibraryFine;
use Illuminate\Pagination\LengthAwarePaginator;

class FineRepository
{
    public function getPending($perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::where('status', 'pending')
            ->with(['member', 'issue.book'])
            ->paginate($perPage);
    }

    public function getAll($perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::with(['member', 'issue.book'])
            ->paginate($perPage);
    }

    public function getById($id): ?LibraryFine
    {
        return LibraryFine::with(['member', 'issue.book'])->find($id);
    }

    public function getByMember($memberId, $perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::where('member_id', $memberId)
            ->with('issue.book')
            ->paginate($perPage);
    }

    public function create(array $data): LibraryFine
    {
        return LibraryFine::create($data);
    }

    public function update($id, array $data): bool
    {
        return LibraryFine::find($id)->update($data) ?? false;
    }

    public function getPaid($perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::where('status', 'paid')
            ->with(['member', 'issue.book'])
            ->paginate($perPage);
    }

    public function getWaived($perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::where('status', 'waived')
            ->with(['member', 'issue.book'])
            ->paginate($perPage);
    }
}
