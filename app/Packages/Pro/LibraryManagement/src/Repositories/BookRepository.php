<?php

namespace App\Packages\Pro\LibraryManagement\Repositories;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository
{
    public function all(int $perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::with('category')
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function findById(int $id): ?LibraryBook
    {
        return LibraryBook::with('category')->find($id);
    }

    public function findByIsbn(string $isbn): ?LibraryBook
    {
        return LibraryBook::where('isbn', $isbn)->first();
    }

    public function getByCategory(int $categoryId, int $perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::where('category_id', $categoryId)
            ->with('category')
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function search(string $query, int $perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::where(function ($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%")
                ->orWhere('isbn', 'like', "%{$query}%");
        })
            ->with('category')
            ->orderBy('title')
            ->paginate($perPage);
    }

    public function getAvailable(): Collection
    {
        return LibraryBook::where('status', 'active')
            ->where('available_copies', '>', 0)
            ->with('category')
            ->orderBy('title')
            ->get();
    }
}
