<?php

namespace App\Packages\Pro\LibraryManagement\Repositories;

use App\Packages\Pro\LibraryManagement\Models\LibraryFine;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FineRepository
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::with('member', 'issue.book')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?LibraryFine
    {
        return LibraryFine::with('member', 'issue.book')->find($id);
    }

    public function getPending(int $perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::whereIn('status', ['pending', 'partial'])
            ->with('member', 'issue.book')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    public function getMemberFines(int $memberId, int $perPage = 15): LengthAwarePaginator
    {
        return LibraryFine::where('member_id', $memberId)
            ->with('issue.book')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getUnpaid(): Collection
    {
        return LibraryFine::where('status', '!=', 'paid')
            ->with('member', 'issue.book')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getTotalCollected(): float
    {
        return (float) LibraryFine::where('status', 'paid')
            ->sum('paid_amount');
    }

    public function getTotalPending(): float
    {
        return (float) LibraryFine::whereIn('status', ['pending', 'partial'])
            ->sum('balance_amount');
    }
}
