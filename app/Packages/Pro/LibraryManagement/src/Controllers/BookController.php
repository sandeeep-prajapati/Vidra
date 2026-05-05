<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryCategory;
use App\Packages\Pro\LibraryManagement\Services\BookService;
use App\Packages\Pro\LibraryManagement\Repositories\BookRepository;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BookController extends BaseController
{
    public function __construct(
        private BookService $bookService,
        private BookRepository $bookRepository
    ) {}

    public function index(): View
    {
        $this->authorize('view_library-management');
        $books = $this->bookRepository->all();
        return view('library-management::books.index', compact('books'));
    }

    public function create(): View
    {
        $this->authorize('create_library-management_item');
        $categories = LibraryCategory::all();
        return view('library-management::books.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create_library-management_item');
        $validated = $request->validate([
            'category_id' => 'required|exists:library_categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|unique:library_books',
            'edition' => 'nullable|string|max:50',
            'total_copies' => 'required|integer|min:1',
            'published_year' => 'nullable|integer|min:1900',
            'rack_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['available_copies'] = $validated['total_copies'];
        $this->bookService->createBook($validated);

        return redirect()->route('library-management.books.index')
            ->with('success', 'Book added successfully');
    }

    public function edit(LibraryBook $book): View
    {
        $this->authorize('edit_library-management_item');
        $categories = LibraryCategory::all();
        return view('library-management::books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, LibraryBook $book): RedirectResponse
    {
        $this->authorize('edit_library-management_item');
        $validated = $request->validate([
            'category_id' => 'required|exists:library_categories,id',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|unique:library_books,isbn,' . $book->id,
            'edition' => 'nullable|string|max:50',
            'total_copies' => 'required|integer|min:1',
            'published_year' => 'nullable|integer',
            'rack_number' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $this->bookService->updateBook($book, $validated);
        return redirect()->route('library-management.books.index')
            ->with('success', 'Book updated successfully');
    }

    public function destroy(LibraryBook $book): RedirectResponse
    {
        $this->authorize('delete_library-management_item');
        $this->bookService->deleteBook($book);
        return redirect()->route('library-management.books.index')
            ->with('success', 'Book deleted successfully');
    }

    public function search(Request $request): View
    {
        $this->authorize('view_library-management');
        $query = $request->input('q');
        $books = $query ? $this->bookRepository->search($query) : collect();
        return view('library-management::books.search', compact('books', 'query'));
    }
}
