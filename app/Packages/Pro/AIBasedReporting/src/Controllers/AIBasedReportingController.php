<?php

namespace App\Packages\Pro\AIBasedReporting\Controllers\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AIBasedReportingController extends BaseController
{
    public function index(): View
    {
        if (!auth()->user()->can('view_a-i-based-reporting')) {
            abort(403, 'Unauthorized');
        }

        return view('a-i-based-reporting::index', [
            'title' => 'AIBasedReporting',
            'message' => 'Welcome to AIBasedReporting!',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->can('create_a-i-based-reporting_item')) {
            abort(403);
        }

        // Your store logic here

        return redirect()->route('a-i-based-reporting.index')
            ->with('success', 'Item created successfully');
    }

    public function edit(Request $request, $id): View
    {
        if (!auth()->user()->can('edit_a-i-based-reporting_item')) {
            abort(403);
        }

        return view('a-i-based-reporting::edit', [
            'id' => $id,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('edit_a-i-based-reporting_item')) {
            abort(403);
        }

        // Your update logic here

        return redirect()->route('a-i-based-reporting.index')
            ->with('success', 'Item updated successfully');
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        if (!auth()->user()->can('delete_a-i-based-reporting_item')) {
            abort(403);
        }

        // Your delete logic here

        return redirect()->route('a-i-based-reporting.index')
            ->with('success', 'Item deleted successfully');
    }
}