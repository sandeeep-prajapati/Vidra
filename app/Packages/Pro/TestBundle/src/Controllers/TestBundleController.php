<?php

namespace App\Packages\Pro\TestBundle\Controllers\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TestBundleController extends BaseController
{
    public function index(): View
    {
        if (!auth()->user()->can('view_test-bundle')) {
            abort(403, 'Unauthorized');
        }

        return view('test-bundle::index', [
            'title' => 'TestBundle',
            'message' => 'Welcome to TestBundle!',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->can('create_test-bundle_item')) {
            abort(403);
        }

        // Your store logic here

        return redirect()->route('test-bundle.index')
            ->with('success', 'Item created successfully');
    }

    public function edit(Request $request, $id): View
    {
        if (!auth()->user()->can('edit_test-bundle_item')) {
            abort(403);
        }

        return view('test-bundle::edit', [
            'id' => $id,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('edit_test-bundle_item')) {
            abort(403);
        }

        // Your update logic here

        return redirect()->route('test-bundle.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('delete_test-bundle_item')) {
            abort(403);
        }

        // Your delete logic here

        return redirect()->route('test-bundle.index')
            ->with('success', 'Item deleted successfully');
    }
}