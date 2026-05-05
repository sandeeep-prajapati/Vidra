<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use App\Packages\Pro\LibraryManagement\Models\LibraryMember;
use App\Packages\Pro\LibraryManagement\Services\IssueService;
use App\Packages\Pro\LibraryManagement\Repositories\IssueRepository;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Exception;

class IssueController extends BaseController
{
    public function __construct(
        private IssueService $issueService,
        private IssueRepository $issueRepository
    ) {}

    public function index(): View
    {
        $this->authorize('view_library-management');
        $issues = $this->issueRepository->all();
        return view('library-management::issues.index', compact('issues'));
    }

    public function create(): View
    {
        $this->authorize('create_library-management_item');
        $books = LibraryBook::where('status', 'active')->where('available_copies', '>', 0)->get();
        $members = LibraryMember::where('status', 'active')->get();
        return view('library-management::issues.create', compact('books', 'members'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create_library-management_item');
        $validated = $request->validate([
            'book_id' => 'required|exists:library_books,id',
            'member_id' => 'required|exists:library_members,id',
            'issue_days' => 'required|integer|min:1|max:90',
        ]);

        try {
            $book = LibraryBook::find($validated['book_id']);
            $member = LibraryMember::find($validated['member_id']);

            $this->issueService->issueBook(
                $book,
                $member,
                auth()->id(),
                $validated['issue_days']
            );

            return redirect()->route('library-management.issues.index')
                ->with('success', 'Book issued successfully');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function return(LibraryIssue $issue): RedirectResponse
    {
        $this->authorize('edit_library-management_item');

        try {
            $this->issueService->returnBook($issue);
            return redirect()->back()
                ->with('success', 'Book returned successfully');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function overdue(): View
    {
        $this->authorize('view_library-management');
        $issues = $this->issueRepository->getOverdue();
        return view('library-management::issues.overdue', compact('issues'));
    }

    public function markOverdue(): RedirectResponse
    {
        $this->authorize('edit_library-management_item');
        $count = $this->issueService->markOverdueBooks();
        return back()->with('success', "Marked $count books as overdue");
    }
}
