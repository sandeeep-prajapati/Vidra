<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\Discount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountController extends Controller
{
    public function index(Request $request): View
    {
        $query = Discount::query();
        if ($request->filled('search')) {
            $query->where('discount_name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('discount_type')) {
            $query->where('discount_type', $request->discount_type);
        }
        $items = $query->orderBy('discount_name')->paginate(20)->withQueryString();
        return view('fee-management::discount.index', compact('items'));
    }

    public function create(): View
    {
        return view('fee-management::discount.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'discount_name'   => 'required|string|max:100',
            'discount_amount' => 'required|numeric|min:0',
            'discount_type'   => 'required|in:Fixed,Percentage',
            'description'     => 'nullable|string',
        ]);
        Discount::create($validated);
        return redirect()->route('discount.index')->with('success', 'Discount created successfully.');
    }

    public function show(Discount $discount): View
    {
        $discount->load('studentDiscounts.student');
        return view('fee-management::discount.show', compact('discount'));
    }

    public function edit(Discount $discount): View
    {
        return view('fee-management::discount.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount): RedirectResponse
    {
        $validated = $request->validate([
            'discount_name'   => 'required|string|max:100',
            'discount_amount' => 'required|numeric|min:0',
            'discount_type'   => 'required|in:Fixed,Percentage',
            'description'     => 'nullable|string',
        ]);
        $discount->update($validated);
        return redirect()->route('discount.index')->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount): RedirectResponse
    {
        $discount->delete();
        return redirect()->route('discount.index')->with('success', 'Discount deleted.');
    }
}
