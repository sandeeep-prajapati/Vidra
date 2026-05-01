<?php

namespace App\Packages\FeeManagement\Controllers;

use App\Http\Controllers\Controller;
use App\Packages\FeeManagement\Models\Expense;
use App\Packages\FeeManagement\Models\FeePayment;
use App\Packages\FeeManagement\Models\FinancialReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialReportController extends Controller
{
    public function index(): View
    {
        $items = FinancialReport::orderByDesc('generated_at')->paginate(20);
        return view('fee-management::financial-report.index', compact('items'));
    }

    public function create(): View
    {
        return view('fee-management::financial-report.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'report_type'         => 'required|in:Income,Expense',
            'report_period_start' => 'required|date',
            'report_period_end'   => 'required|date|after_or_equal:report_period_start',
        ]);

        if ($validated['report_type'] === 'Income') {
            $total = FeePayment::whereBetween('payment_date', [
                $validated['report_period_start'],
                $validated['report_period_end'],
            ])->sum('amount_paid');
        } else {
            $total = Expense::whereBetween('expense_date', [
                $validated['report_period_start'],
                $validated['report_period_end'],
            ])->sum('amount');
        }

        FinancialReport::create([
            'report_type'         => $validated['report_type'],
            'report_period_start' => $validated['report_period_start'],
            'report_period_end'   => $validated['report_period_end'],
            'total_amount'        => $total,
            'generated_at'        => now(),
        ]);

        return redirect()->route('financialReport.index')->with('success', 'Financial report generated successfully.');
    }

    public function show(FinancialReport $financialReport): View
    {
        return view('fee-management::financial-report.show', compact('financialReport'));
    }

    public function edit(FinancialReport $financialReport): View
    {
        return view('fee-management::financial-report.edit', compact('financialReport'));
    }

    public function update(Request $request, FinancialReport $financialReport): RedirectResponse
    {
        return redirect()->route('financialReport.index');
    }

    public function destroy(FinancialReport $financialReport): RedirectResponse
    {
        $financialReport->delete();
        return redirect()->route('financialReport.index')->with('success', 'Report deleted.');
    }
}
