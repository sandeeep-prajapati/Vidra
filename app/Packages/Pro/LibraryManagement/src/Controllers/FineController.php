<?php

namespace App\Packages\Pro\LibraryManagement\Controllers;

use App\Packages\Pro\LibraryManagement\Models\LibraryFine;
use App\Packages\Pro\LibraryManagement\Services\FineService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class FineController extends BaseController
{
    public function __construct(private FineService $fineService) {}

    public function index(): View
    {
        $fines = $this->fineService->getUnpaidFines();
        $report = $this->fineService->getCollectionReport();
        return view('library-management::fines.index', compact('fines', 'report'));
    }

    public function pending(): View
    {
        $fines = $this->fineService->getPendingFines();
        return view('library-management::fines.pending', compact('fines'));
    }

    public function recordPayment(Request $request, LibraryFine $fine)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        if (!$this->fineService->recordPayment($fine->id, $validated['amount'])) {
            return back()->with('error', 'Payment recording failed');
        }

        return redirect()->route('library.fines.index')->with('success', 'Payment recorded successfully');
    }

    public function waive(Request $request, LibraryFine $fine)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $this->fineService->waiveFine(
            $fine->id,
            $validated['reason'] ?? '',
            auth()->id()
        );

        return redirect()->route('library.fines.index')->with('success', 'Fine waived successfully');
    }

    public function report(): View
    {
        $report = $this->fineService->getCollectionReport();
        return view('library-management::fines.report', compact('report'));
    }
}
