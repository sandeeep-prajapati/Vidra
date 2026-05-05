<?php

namespace App\Packages\Pro\LibraryManagement\Services;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryCategory;
use Illuminate\Pagination\LengthAwarePaginator;

class BookService
{
    public function getAllBooks($perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::with('category')->paginate($perPage);
    }

    public function getBooksByCategory($categoryId, $perPage = 15): LengthAwarePaginator
    {
        return LibraryBook::where('category_id', $categoryId)
            ->with('category')
            ->paginate($perPage);
    }

    public function searchBooks($query): LengthAwarePaginator
    {
        return LibraryBook::where('title', 'like', "%{$query}%")
            ->orWhere('author', 'like', "%{$query}%")
            ->orWhere('isbn', 'like', "%{$query}%")
            ->with('category')
            ->paginate(15);
    }

    public function createBook(array $data): LibraryBook
    {
        return LibraryBook::create($data);
    }

    public function updateBook($bookId, array $data): bool
    {
        return LibraryBook::find($bookId)->update($data);
    }

    public function deleteBook($bookId): bool
    {
        return LibraryBook::find($bookId)->delete();
    }

    public function getAvailableBooks(): LengthAwarePaginator
    {
        return LibraryBook::where('available_copies', '>', 0)
            ->where('status', 'active')
            ->with('category')
            ->paginate(15);
    }

    public function getBookStatistics(): array
    {
        return [
            'total_books' => LibraryBook::count(),
            'available_copies' => LibraryBook::sum('available_copies'),
            'total_copies' => LibraryBook::sum('total_copies'),
            'categories' => LibraryCategory::count(),
        ];
    }
}
