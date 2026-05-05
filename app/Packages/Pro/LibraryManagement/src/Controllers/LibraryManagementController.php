<?php

namespace App\Packages\Pro\LibraryManagement\Controllers\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LibraryManagementController extends BaseController
{
    public function index(): View
    {
        if (!auth()->user()->can('view_library-management')) {
            abort(403, 'Unauthorized');
        }

        return view('library-management::index', [
            'title' => 'LibraryManagement',
            'message' => 'Welcome to LibraryManagement!',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->can('create_library-management_item')) {
            abort(403);
        }

        // Your store logic here

        return redirect()->route('library-management.index')
            ->with('success', 'Item created successfully');
    }

    public function edit(Request $request, $id): View
    {
        if (!auth()->user()->can('edit_library-management_item')) {
            abort(403);
        }

        return view('library-management::edit', [
            'id' => $id,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('edit_library-management_item')) {
            abort(403);
        }

        // Your update logic here

        return redirect()->route('library-management.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('delete_library-management_item')) {
            abort(403);
        }

        // Your delete logic here

        return redirect()->route('library-management.index')
            ->with('success', 'Item deleted successfully');
    }
}