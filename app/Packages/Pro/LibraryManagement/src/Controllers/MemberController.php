<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryMember;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MemberController extends BaseController
{
    public function index(): View
    {
        $this->authorize('view_library-management');
        $members = LibraryMember::paginate(15);
        return view('library-management::members.index', compact('members'));
    }

    public function create(): View
    {
        $this->authorize('create_library-management_item');
        return view('library-management::members.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create_library-management_item');
        $validated = $request->validate([
            'member_type' => 'required|in:student,staff',
            'member_id' => 'required|integer',
            'membership_number' => 'required|unique:library_members',
            'max_books_allowed' => 'required|integer|min:1',
            'membership_start' => 'required|date',
            'membership_end' => 'nullable|date|after:membership_start',
        ]);

        LibraryMember::create($validated);
        return redirect()->route('library-management.members.index')
            ->with('success', 'Member registered successfully');
    }

    public function edit(LibraryMember $member): View
    {
        $this->authorize('edit_library-management_item');
        return view('library-management::members.edit', compact('member'));
    }

    public function update(Request $request, LibraryMember $member): RedirectResponse
    {
        $this->authorize('edit_library-management_item');
        $validated = $request->validate([
            'max_books_allowed' => 'required|integer|min:1',
            'membership_end' => 'nullable|date',
            'status' => 'required|in:active,suspended,expired',
        ]);

        $member->update($validated);
        return redirect()->route('library-management.members.index')
            ->with('success', 'Member updated successfully');
    }

    public function show(LibraryMember $member): View
    {
        $this->authorize('view_library-management');
        $activeIssues = $member->issues()
            ->whereIn('status', ['issued', 'overdue'])
            ->with('book')
            ->get();
        $fines = $member->fines()
            ->where('status', '!=', 'paid')
            ->with('issue.book')
            ->get();

        return view('library-management::members.show', compact('member', 'activeIssues', 'fines'));
    }
}
