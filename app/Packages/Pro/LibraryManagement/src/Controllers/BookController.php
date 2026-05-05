<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryCategory;
use App\Packages\Pro\LibraryManagement\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class BookController extends BaseController
{
    public function __construct(private BookService $bookService) {}

    public function index(): View
    {
        $books = $this->bookService->getAllBooks();
        $stats = $this->bookService->getBookStatistics();
        return view('library-management::books.index', compact('books', 'stats'));
    }

    public function create(): View
    {
        $categories = LibraryCategory::all();
        return view('library-management::books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|unique:library_books',
            'edition' => 'nullable|string',
            'category_id' => 'required|exists:library_categories,id',
            'total_copies' => 'required|integer|min:1',
            'rack_number' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['available_copies'] = $validated['total_copies'];
        $this->bookService->createBook($validated);

        return redirect()->route('library.books.index')->with('success', 'Book added successfully');
    }

    public function edit(LibraryBook $book): View
    {
        $categories = LibraryCategory::all();
        return view('library-management::books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, LibraryBook $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => "nullable|unique:library_books,isbn,{$book->id}",
            'edition' => 'nullable|string',
            'category_id' => 'required|exists:library_categories,id',
            'rack_number' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $this->bookService->updateBook($book->id, $validated);

        return redirect()->route('library.books.index')->with('success', 'Book updated successfully');
    }

    public function destroy(LibraryBook $book)
    {
        $this->bookService->deleteBook($book->id);
        return redirect()->route('library.books.index')->with('success', 'Book deleted successfully');
    }

    public function search(Request $request): View
    {
        $query = $request->input('query');
        $books = $this->bookService->searchBooks($query);
        return view('library-management::books.search', compact('books', 'query'));
    }
}
