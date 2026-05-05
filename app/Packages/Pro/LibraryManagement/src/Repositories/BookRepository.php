<?php

namespace App\Packages\Pro\LibraryManagement\Repositories;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use Illuminate\Pagination\LengthAwarePaginator;

class BookRepository
{
    public function getPaginated($perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::with('category')->paginate($perPage);
    }

    public function getByCategory($categoryId, $perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::where('category_id', $categoryId)
            ->with('category')
            ->paginate($perPage);
    }

    public function search($query, $perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->with('category')
            ->paginate($perPage);
    }

    public function getAvailable($perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::where('available_copies', '>', 0)
            ->where('status', 'active')
            ->with('category')
            ->paginate($perPage);
    }

    public function getById($id): ?LibraryBook
    {
        return LibraryBook::with('category')->find($id);
    }

    public function create(array $data): LibraryBook
    {
        return LibraryBook::create($data);
    }

    public function update($id, array $data): bool
    {
        return LibraryBook::find($id)->update($data) ?? false;
    }

    public function delete($id): bool
    {
        return LibraryBook::find($id)->delete() ?? false;
    }
}
