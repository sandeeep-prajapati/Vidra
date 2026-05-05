<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryMember;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class MemberController extends BaseController
{
    public function index(): View
    {
        $members = LibraryMember::paginate(15);
        return view('library-management::members.index', compact('members'));
    }

    public function create(): View
    {
        return view('library-management::members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_type' => 'required|in:student,teacher,staff',
            'member_id' => 'required|unique:library_members',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:library_members',
            'phone' => 'nullable|string',
            'max_books_allowed' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $validated['membership_number'] = 'LM-' . strtoupper(uniqid());
        LibraryMember::create($validated);

        return redirect()->route('library.members.index')->with('success', 'Member registered successfully');
    }

    public function show(LibraryMember $member): View
    {
        $member->load('issues', 'fines');
        return view('library-management::members.show', compact('member'));
    }

    public function edit(LibraryMember $member): View
    {
        return view('library-management::members.edit', compact('member'));
    }

    public function update(Request $request, LibraryMember $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "nullable|email|unique:library_members,email,{$member->id}",
            'phone' => 'nullable|string',
            'max_books_allowed' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $member->update($validated);

        return redirect()->route('library.members.show', $member)->with('success', 'Member updated successfully');
    }

    public function destroy(LibraryMember $member)
    {
        $member->delete();
        return redirect()->route('library.members.index')->with('success', 'Member deleted successfully');
    }
}
