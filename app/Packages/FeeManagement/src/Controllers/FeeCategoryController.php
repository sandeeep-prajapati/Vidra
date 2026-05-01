<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\FeeCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = FeeCategory::query();
        if ($request->filled('search')) {
            $query->where('category_name', 'like', '%'.$request->search.'%');
        }
        $items = $query->orderBy('category_name')->paginate(20)->withQueryString();
        return view('fee-management::fee-category.index', compact('items'));
    }

    public function create(): View
    {
        return view('fee-management::fee-category.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:fee_categories,category_name',
            'description'   => 'nullable|string',
        ]);
        FeeCategory::create($validated);
        return redirect()->route('feeCategory.index')->with('success', 'Fee category created successfully.');
    }

    public function show(FeeCategory $feeCategory): View
    {
        $feeCategory->load('feeStructures.schoolClass', 'feeStructures.academicYear');
        return view('fee-management::fee-category.show', compact('feeCategory'));
    }

    public function edit(FeeCategory $feeCategory): View
    {
        return view('fee-management::fee-category.edit', compact('feeCategory'));
    }

    public function update(Request $request, FeeCategory $feeCategory): RedirectResponse
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:fee_categories,category_name,'.$feeCategory->fee_category_id.',fee_category_id',
            'description'   => 'nullable|string',
        ]);
        $feeCategory->update($validated);
        return redirect()->route('feeCategory.index')->with('success', 'Fee category updated successfully.');
    }

    public function destroy(FeeCategory $feeCategory): RedirectResponse
    {
        $feeCategory->delete();
        return redirect()->route('feeCategory.index')->with('success', 'Fee category deleted.');
    }
}
