<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Expense::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('expense_category', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        $totalAmount = (clone $query)->sum('amount');
        $items = $query->orderByDesc('expense_date')->paginate(20)->withQueryString();
        return view('fee-management::expense.index', compact('items', 'totalAmount'));
    }

    public function create(): View
    {
        return view('fee-management::expense.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'expense_date'     => 'required|date',
            'amount'           => 'required|numeric|min:0',
            'expense_category' => 'required|string|max:100',
            'description'      => 'nullable|string',
        ]);
        Expense::create($validated);
        return redirect()->route('expense.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense): View
    {
        return view('fee-management::expense.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        return view('fee-management::expense.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $validated = $request->validate([
            'expense_date'     => 'required|date',
            'amount'           => 'required|numeric|min:0',
            'expense_category' => 'required|string|max:100',
            'description'      => 'nullable|string',
        ]);
        $expense->update($validated);
        return redirect()->route('expense.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();
        return redirect()->route('expense.index')->with('success', 'Expense deleted.');
    }
}
