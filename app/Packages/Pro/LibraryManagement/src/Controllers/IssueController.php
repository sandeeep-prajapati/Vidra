<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryBook;
use App\Packages\Pro\LibraryManagement\Models\LibraryMember;
use App\Packages\Pro\LibraryManagement\Models\LibraryIssue;
use App\Packages\Pro\LibraryManagement\Services\IssueService;
use App\Packages\Pro\LibraryManagement\Services\FineService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class IssueController extends BaseController
{
    public function __construct(
        private IssueService $issueService,
        private FineService $fineService
    ) {}

    public function index(): View
    {
        $issues = $this->issueService->getActiveIssues();
        return view('library-management::issues.index', compact('issues'));
    }

    public function create(): View
    {
        $books = LibraryBook::where('available_copies', '>', 0)->get();
        $members = LibraryMember::where('status', 'active')->get();
        return view('library-management::issues.create', compact('books', 'members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:library_books,id',
            'member_id' => 'required|exists:library_members,id',
        ]);

        $issue = $this->issueService->issueBook(
            $validated['book_id'],
            $validated['member_id'],
            auth()->id() ?? 1
        );

        if (!$issue) {
            return back()->with('error', 'Cannot issue book. Check availability and member borrowing limits.');
        }

        return redirect()->route('library.issues.index')->with('success', 'Book issued successfully');
    }

    public function returnBook(Request $request, LibraryIssue $issue)
    {
        $this->issueService->returnBook($issue->id);

        $fineAmount = $this->fineService->calculateFineForIssue($issue->id);
        if ($fineAmount > 0) {
            $this->fineService->createFine($issue->id, $issue->getOverdueDays());
        }

        return redirect()->route('library.issues.index')->with('success', 'Book returned successfully');
    }

    public function overdue(): View
    {
        $issues = $this->issueService->getOverdueIssues();
        return view('library-management::issues.overdue', compact('issues'));
    }

    public function memberIssues(LibraryMember $member): View
    {
        $issues = $this->issueService->getMemberIssues($member->id);
        return view('library-management::issues.member', compact('issues', 'member'));
    }
}
