<?php

namespace App\Packages\Pro\LibraryManagement\Services;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryCategory;
use Illuminate\Database\Eloquent\Collection;

class BookService
{
    public function createBook(array $data): LibraryBook
    {
        return LibraryBook::create($data);
    }

    public function updateBook(LibraryBook $book, array $data): LibraryBook
    {
        $book->update($data);
        return $book->fresh();
    }

    public function deleteBook(LibraryBook $book): bool
    {
        return $book->delete();
    }

    public function getActiveBooks(): Collection
    {
        return LibraryBook::where('status', 'active')
            ->with('category')
            ->get();
    }

    public function getAvailableBooks(): Collection
    {
        return LibraryBook::where('status', 'active')
            ->where('available_copies', '>', 0)
            ->with('category')
            ->get();
    }

    public function getBooksByCategory(LibraryCategory $category): Collection
    {
        return $category->books()
            ->where('status', 'active')
            ->get();
    }

    public function searchBooks(string $query): Collection
    {
        return LibraryBook::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%")
                    ->orWhere('isbn', 'like', "%{$query}%");
            })
            ->with('category')
            ->get();
    }

    public function getMostIssuedBooks(int $limit = 10): Collection
    {
        return LibraryBook::withCount('issues')
            ->orderBy('issues_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getLowStockBooks(int $threshold = 2): Collection
    {
        return LibraryBook::where('status', 'active')
            ->where('available_copies', '<=', $threshold)
            ->with('category')
            ->get();
    }

    public function getBookStats(): array
    {
        return [
            'total_books' => LibraryBook::count(),
            'active_books' => LibraryBook::where('status', 'active')->count(),
            'total_copies' => LibraryBook::sum('total_copies'),
            'available_copies' => LibraryBook::sum('available_copies'),
            'issued_copies' => LibraryBook::sum('total_copies') - LibraryBook::sum('available_copies'),
        ];
    }
}
